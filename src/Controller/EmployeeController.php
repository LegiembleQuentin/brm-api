<?php

namespace App\Controller;

use App\Entity\Employee;
use App\Filter\EmployeeFilter;
use App\Service\EmployeeService;
use JMS\Serializer\SerializationContext;
use JMS\Serializer\SerializerBuilder;
use JMS\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api')]
class EmployeeController extends AbstractController
{
    private $serializer;
    private $employeeService;

    public function __construct(SerializerInterface $serializer, EmployeeService $employeeService)
    {
        $this->serializer = SerializerBuilder::create()->build();;
        $this->employeeService = $employeeService;
    }

//    #[Route('/employees', methods: ['GET'])]
//    public function getEmployees(): Response
//    {
//        //gerer les droits
//        $employees = $this->employeeService->getEmployees();
//        $employeesJson = $this->serializer->serialize($employees, 'json', SerializationContext::create()->setGroups(['employee', 'default']));
//
//        return new Response($employeesJson, Response::HTTP_OK, ['Content-Type' => 'application/json']);
//    }

    #[Route('/employees', methods: ['GET'])]
    public function getEmployees(Request $request): Response
    {
        //gerer les droits/roles?

        $filters = new EmployeeFilter();
        $search = $request->query->get('search');
        if(isset($search) && $search != 'undefined' && $search != null && $search != 'null') {
            $filters->setSearch($search);
        }
        $contractType = $request->query->get(('contractType'));
        if(isset($contractType) && $contractType != 'undefined' && $contractType != null && $contractType != 'null'){
            $filters->setContractType($contractType);
        }
        $restaurant = $request->query->get('restaurant');
        if(isset($restaurant) && $restaurant != 'undefined' && $restaurant != null && $restaurant != 'null'){
            $filters->setRestaurant($restaurant);
        }
        $role = $request->query->get('role');
        if(isset($role) && $role != 'undefined' && $role != null && $role != 'null'){
            $filters->setRole($role);
        }

        $employees = $this->employeeService->findByFilter($filters);
        $employeesJson = $this->serializer->serialize($employees, 'json', SerializationContext::create()->setGroups(['employee', 'default']));

        return new Response($employeesJson, Response::HTTP_OK, ['Content-Type' => 'application/json']);
    }

    #[Route('/employee', methods: ['POST'])]
    public function addEmployee(Request $request, SerializerInterface $serializer): Response
    {
        $content = $request->getContent();

        print_r($content);

        $employee = $serializer->deserialize($content, Employee::class, 'json');

        print_r($employee);

        if ($employee->getId() !== null) {
            //retourner vers l'update

        }

        $result = $this->employeeService->save($employee);

        $jsonResponse = $this->serializer->serialize($result, 'json');
        return new Response($jsonResponse, Response::HTTP_CREATED, ['Content-Type' => 'application/json']);
    }

    #[Route('/employee/{id}', methods: ['GET'])]
    public function getEmployee(int $id): Response
    {
        //gerer les droits
        $employee = $this->employeeService->getEmployeeById($id);

        if (!$employee) {
            return $this->json(['message' => 'Employee not found'], Response::HTTP_NOT_FOUND);
        }

        $employeeJson = $this->serializer->serialize($employee, 'json', SerializationContext::create()->setGroups(['employee', 'default']));

        return new Response($employeeJson, Response::HTTP_OK, ['Content-Type' => 'application/json']);
    }
}
