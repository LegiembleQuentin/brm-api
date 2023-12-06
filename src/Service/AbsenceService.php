<?php
namespace App\Service;

use App\Entity\Absences;
use App\Filter\AbsenceFilter;
use Doctrine\ORM\EntityManagerInterface;
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
}