<?php
namespace App\Service;

use App\Entity\Absences;
use App\Filter\AbsenceFilter;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class AbsenceService {
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
     * @return Absences[]
     */
    public function getAbsences() : array
    {
        $absenceRepo = $this->em->getRepository(Absences::class);
        return $absenceRepo->findAll();
    }

    /**
     * @return Absences[]
     */
    public function findByFilter(AbsenceFilter $filters) : array
    {
        $absenceRepo = $this->em->getRepository(Absences::class);
        return $absenceRepo->findAbsencesByFilter($filters);
    }

    public function save(Absences $absence): Absences
    {
        $employeeId = $absence->getEmployee()->getId();
        $employee = $this->employeeService->getEmployeeById($employeeId);

        if(!$employee){
            throw new Exception('Employee not found');
        }
        $absence->setEmployee($employee);

        $errors = $this->validator->validate($absence);
        if (count($errors) > 0){
            throw new Exception('Invalid absence: ');
        }

        $absence->setCreatedAt(new DateTimeImmutable('now'));

        $this->em->persist($absence);
        $this->em->flush();

        return $absence;
    }
}