<?php

namespace App\Controller;

use App\Entity\Employee;
use App\Filter\EmployeeFilter;
use App\Service\EmployeeService;
use App\Service\UserService;
use Exception;
use JMS\Serializer\SerializationContext;
use JMS\Serializer\SerializerBuilder;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api')]
class EmployeeController extends AbstractController
{
    private $serializer;
    private $employeeService;
    private $userService;

    public function __construct(EmployeeService $employeeService, UserService $userService)
    {
        $this->serializer = SerializerBuilder::create()->build();
        $this->employeeService = $employeeService;
        $this->userService = $userService;
    }

    #[Route('/employees', methods: ['GET'])]
    public function getEmployees(Request $request): Response
    {
        //gerer les droits/roles?

        try {
            $jsonQuery = json_encode($request->query->all());

            $filters = $this->serializer->deserialize($jsonQuery, EmployeeFilter::class, 'json');

            $enabled = $request->query->get('enabled') === 'true';
            $filters->setEnabled($enabled);

            $employees = $this->employeeService->findByFilter($filters);
            $employeesJson = $this->serializer->serialize($employees, 'json', SerializationContext::create()->setGroups(['employee', 'default']));
        }catch (Exception $e) {
            return new Response('Invalid input: ' . $e->getMessage(), Response::HTTP_BAD_REQUEST);
        }

        return new Response($employeesJson, Response::HTTP_OK, ['Content-Type' => 'application/json']);
    }

    #[Route('/employees-small', methods: ['GET'])]
    public function getEmployeesSmall(Request $request): Response
    {
        try {
            $employees = $this->employeeService->findAll();
            $employeesJson = $this->serializer->serialize($employees, 'json', SerializationContext::create()->setGroups(['default']));
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

            if ($employee->getRole() === 'DIRECTOR' || $employee->getRole() === 'MANAGER') {
                try {
                    $link = $this->addUser($employee);

                    $responseData = ['link' => $link];
                    $jsonResponse = json_encode($responseData);

                    return new Response($jsonResponse, Response::HTTP_CREATED, ['Content-Type' => 'application/json']);
                } catch (Exception $e) {
                    return new Response('Error processing request: ' . $e->getMessage(), Response::HTTP_BAD_REQUEST);
                }
            }

            $result = $this->employeeService->save($employee);

        } catch (Exception $e) {
            return new Response('Error processing request: ' . $e->getMessage(), Response::HTTP_BAD_REQUEST);
        }

        $jsonResponse = $this->serializer->serialize($result, 'json', SerializationContext::create()->setGroups(['employee', 'default']));
        return new Response($jsonResponse, Response::HTTP_CREATED, ['Content-Type' => 'application/json']);

    }

    private function addUser($employee)
    {
        try {
            $this->employeeService->save($employee);
        }catch (Exception $e) {
            throw new Exception('Error when creating employee' . $e->getMessage());
        }

        $link = $this->userService->addUser($employee);

        return $link;
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
