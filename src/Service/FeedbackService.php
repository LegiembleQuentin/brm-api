<?php

namespace App\Service;

use App\Entity\Feedback;
use App\Filter\FeedbackFilter;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
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

        if ($feedback->isWarning() && $feedback->getEmployee() != null){
            $employee = $this->employeeService->getEmployeeById($feedback->getEmployee()->getId());
            if (!$employee){
                throw new Exception('Employee not found');
            }
            $feedback->setEmployee($employee);
        }

        $feedback->setCreatedAt(new DateTimeImmutable('now'));

        $this->em->persist($feedback);
        $this->em->flush();

        return $feedback;
    }

}