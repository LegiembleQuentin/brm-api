<?php

namespace App\Controller;

use App\Entity\Employee;
use App\Service\EmployeeService;
use JMS\Serializer\SerializerInterface;
use Psr\Log\LoggerInterface;
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
        $this->serializer = $serializer;
        $this->employeeService = $employeeService;
    }

    #[Route('/employees', methods: ['GET'])]
    public function getEmployees(): Response
    {
        //gerer les droits
        $employees = $this->employeeService->getEmployees();
        $employee = [
            'bonjour'
        ];
        $employeesJson = $this->serializer->serialize($employee, 'json');

        return new Response($employeesJson, Response::HTTP_OK, ['Content-Type' => 'application/json']);
    }

    #[Route('/employee', methods: ['POST'])]
    public function addEmployee(Request $request): Response
    {
        $content = $request->getContent();
        $employee = $this->serializer->deserialize($content, Employee::class, 'json');

        dump($employee);

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

        $employeeJson = $this->serializer->serialize($employee, 'json');

        return new Response($employeeJson, Response::HTTP_OK, ['Content-Type' => 'application/json']);
    }
}
