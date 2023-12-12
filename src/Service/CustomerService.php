<?php

namespace App\Service;

use App\Entity\Customer;
use App\Entity\Restaurant;
use App\Filter\CustomerFilter;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use ReflectionClass;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class CustomerService
{
    private $entityManager;
    private $validator;

    public function __construct(EntityManagerInterface $entityManager, ValidatorInterface $validator)
    {
        $this->entityManager = $entityManager;
        $this->validator = $validator;
    }

    /**
     * @return Customer[]
     */
    public function getCustomer(): array
    {
        $customerRepo = $this->entityManager->getRepository(Customer::class);
        return $customerRepo->findAll();
    }

    public function getCustomerById(int $id): ?customer
    {
        $customerRepo = $this->entityManager->getRepository(Customer::class);
        return $customerRepo->find($id);
    }


    /**
     * @return Customer[]
     */
    public function findByFilter(CustomerFilter $filters): array
    {
        return $this->entityManager->getRepository(Customer::class)->findByFilter($filters);
    }

    public function save(Customer $customer): Customer
    {

        $errors = $this->validator->validate($customer);
        if (count($errors) > 0) {
            throw new Exception('Invalid employee');
        }

        $customer->setCreatedAt(new DateTimeImmutable('now'));

        $this->entityManager->persist($customer);
        $this->entityManager->flush();

        return $customer;
    }

    public function update(Customer $customer): Customer
    {
        $existingCustomer = $this->entityManager->getRepository(Customer::class)->find($customer->getId());
        if (!$existingCustomer) {
            throw new Exception('Customer not found.');
        }
        $errors = $this->validator->validate($customer);
        if (count($errors) > 0) {
            throw new Exception('Invalid customer');
        }
        $reflClass = new ReflectionClass($customer);
        $propertyAccessor = PropertyAccess::createPropertyAccessor();
        foreach ($reflClass->getProperties() as $property) {
            $name = $property->getName();
            // Skip the ID property and collections
            if ($name !== 'id' && !$property->getValue($existingCustomer) instanceof Collection) {
                $value = $propertyAccessor->getValue($customer, $name);
                $propertyAccessor->setValue($existingCustomer, $name, $value);
            }
        }

        $this->entityManager->flush();
        return $existingCustomer;
    }

    // public function getEmployeeById(int $id): ?Employee
    // {
    //     $employeeRepo = $this->em->getRepository(Employee::class);
    //     return $employeeRepo->find($id);
    // }

    // public function save(Employee $employee): Employee
    // {
    //     $restaurant = $this->restaurantService->getRestaurantById($employee->getRestaurant()->getId());

    //     if (!$restaurant) {
    //         throw new Exception("Restaurant not found.");
    //     }

    //     $errors = $this->validator->validate($employee);
    //     if (count($errors) > 0) {
    //         throw new Exception("Invalid employee");
    //     }

    //     $employee->setRestaurant($restaurant);
    //     $employee->setCreatedAt(new DateTimeImmutable('now'));

    //     $this->em->persist($employee);
    //     $this->em->flush();

    //     return $employee;
    // }

    // public function update(Employee $employee): Employee
    // {
    //     $existingEmployee = $this->em->getRepository(Employee::class)->find($employee->getId());
    //     if (!$existingEmployee) {
    //         throw new Exception("Employee not found.");
    //     }

    //     $errors = $this->validator->validate($employee);
    //     if (count($errors) > 0) {
    //         throw new Exception("Invalid employee" . $errors);
    //     }

    //     $restaurant = $this->restaurantService->getRestaurantById($employee->getRestaurant()->getId());
    //     if (!$restaurant) {
    //         throw new Exception("Restaurant not found.");
    //     }

    //     $reflClass = new ReflectionClass($employee);
    //     $propertyAccessor = PropertyAccess::createPropertyAccessor();
    //     foreach ($reflClass->getProperties() as $property) {
    //         $name = $property->getName();
    //         // Skip the ID property and collections
    //         if ($name !== 'id' && !$property->getValue($existingEmployee) instanceof Collection) {
    //             $value = $propertyAccessor->getValue($employee, $name);
    //             $propertyAccessor->setValue($existingEmployee, $name, $value);
    //         }
    //     }

    //     $existingEmployee->setRestaurant($restaurant);
    //     $existingEmployee->setModifiedAt(new DateTimeImmutable('now'));

    //     $this->em->flush();

    //     return $existingEmployee;
    // }


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
