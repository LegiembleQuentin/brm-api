<?php

namespace App\Service;

use App\Entity\Feedback;
use App\Filter\FeedbackFilter;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use ReflectionClass;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class FeedbackService {
    private $em;
    private $validator;
    private $employeeService;

    public function __construct(EntityManagerInterface $entityManager, EmployeeService $employeeService, ValidatorInterface $validator)
    {
        $this->em = $entityManager;
        $this->validator = $validator;
        $this->employeeService = $employeeService;
    }

    /**
     * @return Feedback[]
     */
    public function getFeedbacks() : array
    {
        $feedbackRepo = $this->em->getRepository(Feedback::class);
        return $feedbackRepo->findAll();
    }

    public function getFeedbackById(int $id): ?Feedback
    {
        $feedbackRepo = $this->em->getRepository(Feedback::class);
        return $feedbackRepo->find($id);
    }

    /**
     * @return Feedback[]
     */
    public function findByFilter(FeedbackFilter $filters) : array
    {
        $feedbackRepo = $this->em->getRepository(Feedback::class);
        return $feedbackRepo->findFeedbacksByFilter($filters);
    }

    public function save(Feedback $feedback): Feedback
    {
        //SET LUTILISATEUR EN AUTEUR SI IL EST BIEN MANAGER MINIMUM LORSQUON AURA LA PUTAIN DE CONNEXION

        $author = $this->employeeService->getEmployeeById(111);

        if(!$author){
            throw new Exception('Author not found');
        }
        $feedback->setAuthor($author);

        $this->setEmployeeIfWarning($feedback);

        $errors = $this->validator->validate($feedback);
        if (count($errors) > 0){
            throw new Exception('Invalid feedback');
        }

        $feedback->setCreatedAt(new DateTimeImmutable('now'));

        $this->em->persist($feedback);
        $this->em->flush();

        return $feedback;
    }

    public function update(Feedback $feedback): Feedback
    {
        $existingFeedback = $this->em->getRepository(Feedback::class)->find($feedback);

        if (!$existingFeedback) {
            throw new Exception('Feedback not found');
        }

        $reflClass = new ReflectionClass($feedback);
        $propertyAccessor = PropertyAccess::createPropertyAccessor();
        foreach ($reflClass->getProperties() as $property) {
            $name = $property->getName();
            if ($name !== 'id') {
                $value = $propertyAccessor->getValue($feedback, $name);
                $propertyAccessor->setValue($existingFeedback, $name, $value);
            }
        }

        $this->setEmployeeIfWarning($existingFeedback);
        $author = $this->employeeService->getEmployeeById($feedback->getAuthor()->getId());
        if(!$author){
            throw new Exception('Author not found');
        }
        $existingFeedback->setAuthor($author);

        $errors = $this->validator->validate($existingFeedback);
        if (count($errors) > 0){
            throw new Exception('Invalid feedback');
        }

        $this->em->flush();

        return $existingFeedback;
    }

    public function setEmployeeIfWarning($feedback) {
        if ($feedback->isWarning() && $feedback->getEmployee() != null) {
            $employeeId = $feedback->getEmployee()->getId();
            $employee = $this->employeeService->getEmployeeById($employeeId);

            if (!$employee) {
                throw new Exception('Employee not found');
            }

            $feedback->setEmployee($employee);
        }
        //pour l'update
        if (!$feedback->isWarning()){
            $feedback->setEmployee(null);
        }
    }

    public function delete(int $id)
    {
        $feedback = $this->getFeedbackById($id);
        if (!$feedback) {
            throw new \LogicException('Feedback not provided for deletion.');
        }

        try {
            $this->em->remove($feedback);
            $this->em->flush();
        } catch (\Exception $e) {
            throw $e;
        }
    }

}