<?php

namespace App\Service;

use App\Entity\Employee;
use Doctrine\ORM\EntityManagerInterface;

class EmployeeService
{
    private $em;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->em = $entityManager;
    }

    /**
     * @return Employee[]
     */
    public function getEmployees() : array
    {
        $employeeRepo = $this->em->getRepository(Employee::class);
        return $employeeRepo->findAll();
    }

    public function getEmployeeById(int $id): ?Employee
    {
        $employeeRepo = $this->em->getRepository(Employee::class);
        return $employeeRepo->find($id);
    }

    public function save(Employee $employee): Employee
    {
//        $employee->setCreatedAt(new DateTimeImmutable('now'));
//
//        $this->em->persist($employee);
//        $this->em->flush();

        return $employee;
    }
}
