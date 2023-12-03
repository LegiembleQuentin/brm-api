<?php

namespace App\Service;

use App\Entity\Employee;
use App\Filter\EmployeeFilter;
use DateTimeImmutable;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use ReflectionClass;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class EmployeeService
{
    private $em;
    private $restaurantService;
    private $validator;

    public function __construct(EntityManagerInterface $entityManager, RestaurantService $restaurantService, ValidatorInterface $validator)
    {
        $this->em = $entityManager;
        $this->restaurantService = $restaurantService;
        $this->validator = $validator;
    }

    /**
     * @return Employee[]
     */
    public function findAll() : array
    {
        $employeeRepo = $this->em->getRepository(Employee::class);
        return $employeeRepo->findAll();
    }

    /**
     * @return Employee[]
     */
    public function findByFilter(EmployeeFilter $filters) : array
    {
        $employeeRepo = $this->em->getRepository(Employee::class);
        return $employeeRepo->findEmployeesByFilter($filters);
    }

    public function getEmployeeById(int $id): ?Employee
    {
        $employeeRepo = $this->em->getRepository(Employee::class);
        return $employeeRepo->find($id);
    }

    public function save(Employee $employee): Employee
    {
        $restaurant = $this->restaurantService->getRestaurantById($employee->getRestaurant()->getId());

        if (!$restaurant){
            throw new Exception("Restaurant not found.");
        }

        $errors = $this->validator->validate($employee);
        if (count($errors) > 0) {
            throw new Exception("Invalid employee");
        }

        $employee->setRestaurant($restaurant);
        $employee->setCreatedAt(new DateTimeImmutable('now'));

        $this->em->persist($employee);
        $this->em->flush();

        return $employee;
    }

    public function update(Employee $employee): Employee
    {
        $existingEmployee = $this->em->getRepository(Employee::class)->find($employee->getId());
        if (!$existingEmployee) {
            throw new Exception("Employee not found.");
        }

        $errors = $this->validator->validate($employee);
        if (count($errors) > 0) {
            throw new Exception("Invalid employee" . $errors);
        }

        $restaurant = $this->restaurantService->getRestaurantById($employee->getRestaurant()->getId());
        if (!$restaurant){
            throw new Exception("Restaurant not found.");
        }

        $reflClass = new ReflectionClass($employee);
        $propertyAccessor = PropertyAccess::createPropertyAccessor();
        foreach ($reflClass->getProperties() as $property) {
            $name = $property->getName();
            // Skip the ID property and collections
            if ($name !== 'id' && !$property->getValue($existingEmployee) instanceof Collection) {
                $value = $propertyAccessor->getValue($employee, $name);
                $propertyAccessor->setValue($existingEmployee, $name, $value);
            }
        }

        $existingEmployee->setRestaurant($restaurant);
        $existingEmployee->setModifiedAt(new DateTimeImmutable('now'));

        $this->em->flush();

        return $existingEmployee;
    }


//    public function delete(int $id)
//    {
//        $employee = $this->getEmployeeById($id);
//        if (!$employee) {
//            throw new \LogicException('Employee not provided for deletion.');
//        }
//
//        try {
//            $this->em->remove($employee);
//            $this->em->flush();
//        } catch (\Exception $e) {
//            throw $e;
//        }
//    }
}
