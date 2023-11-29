<?php

namespace App\Controller;

use App\Entity\Employee;
use App\Filter\EmployeeFilter;
use App\Service\EmployeeService;
use Exception;
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

    #[Route('/employees', methods: ['GET'])]
    public function getEmployees(Request $request, SerializerInterface $serializer): Response
    {
        //gerer les droits/roles?

        try {
            $jsonQuery = json_encode($request->query->all());

            $filters = $serializer->deserialize($jsonQuery, EmployeeFilter::class, 'json');

            $enabled = $request->query->get('enabled') === 'true';
            $filters->setEnabled($enabled);

            $employees = $this->employeeService->findByFilter($filters);
            $employeesJson = $this->serializer->serialize($employees, 'json', SerializationContext::create()->setGroups(['employee', 'default']));
        }catch (Exception $e) {
            return new Response('Invalid input: ' . $e->getMessage(), Response::HTTP_BAD_REQUEST);
        }

        return new Response($employeesJson, Response::HTTP_OK, ['Content-Type' => 'application/json']);
    }

    #[Route('/employee', methods: ['POST'])]
    public function addEmployee(Request $request): Response
    {
        try {
            $content = json_decode($request->getContent(), true);
            $employeeData = $content['body'];
            $employeeJson = json_encode($employeeData);

            $employee = $this->serializer->deserialize($employeeJson, Employee::class, 'json');

            $result = $this->employeeService->save($employee);

        } catch (Exception $e) {
            return new Response('Error processing request: ' . $e->getMessage(), Response::HTTP_BAD_REQUEST);
        }

        $jsonResponse = $this->serializer->serialize($result, 'json', SerializationContext::create()->setGroups(['employee', 'default']));
        return new Response($jsonResponse, Response::HTTP_CREATED, ['Content-Type' => 'application/json']);

    }

    #[Route('/employee', methods: ['PUT'])]
    public function updateEmployee(Request $request): Response
    {
        try {
            $content = json_decode($request->getContent(), true);
            $employeeData = $content['body'];
            $employeeJson = json_encode($employeeData);

            $employee = $this->serializer->deserialize($employeeJson, Employee::class, 'json');

            $result = $this->employeeService->update($employee);
        }catch (Exception $e) {
            return new Response('Error processing request: ' . $e->getMessage(), Response::HTTP_BAD_REQUEST);
        }

        $jsonResponse = $this->serializer->serialize($result, 'json', SerializationContext::create()->setGroups(['employee', 'default']));
        return new Response($jsonResponse, Response::HTTP_CREATED, ['Content-Type' => 'application/json']);
    }

    #[Route('/employee/{id}', methods: ['GET'])]
    public function getEmployee(int $id): Response
    {
        try {
            // gerer les droits
            // ...

            $employee = $this->employeeService->getEmployeeById($id);

            if (!$employee) {
                return $this->json(['message' => 'Employee not found'], Response::HTTP_NOT_FOUND);
            }

            $employeeJson = $this->serializer->serialize($employee, 'json', SerializationContext::create()->setGroups(['employee', 'default']));
        } catch (Exception $e) {
            return new Response('Error processing request: ' . $e->getMessage(), Response::HTTP_BAD_REQUEST);
        }

        return new Response($employeeJson, Response::HTTP_OK, ['Content-Type' => 'application/json']);
    }

//    #[Route('/employee/{id}', methods: ['DELETE'])]
//    public function deleteEmployee(int $id): Response
//    {
//        // GESTION DES ROLES
//        // if (!$this->isGranted('ROLE_ADMIN')) {
//        //     return $this->json(['message' => 'Access Denied'], Response::HTTP_FORBIDDEN);
//        // }
//
//        try {
//            $this->employeeService->delete($id);
//
//            return new Response(null, Response::HTTP_NO_CONTENT);
//        } catch (\Exception $e) {
//
//            return $this->json(['message' => 'Error deleting employee: ' . $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
//        }
//    }
}
