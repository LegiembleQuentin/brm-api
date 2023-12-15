<?php

namespace App\Controller;

use App\Entity\Customer;
use App\Filter\CustomerFilter;
use App\Service\CustomerService;
use Exception;
use JMS\Serializer\SerializationContext;
use JMS\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api')]

class CustomerController extends AbstractController
{

    private $serializer;
    private $customerService;

    public function __construct(SerializerInterface $serializer, CustomerService $customerService)
    {
        $this->serializer = $serializer;
        $this->customerService = $customerService;
    }

    // #[Route('/customers', name: 'app_customers', methods: ['GET'])]
    // public function index(): Response
    // {
    //     $customer = $this->customerService->getCustomer();
    //     $customerJson = $this->serializer->serialize($customer, 'json', SerializationContext::create()->setGroups(['customer', 'default']));

    //     return new Response($customerJson, Response::HTTP_OK, ['Content-Type' => 'application/json']);
    // }


    #[Route('/customer/{id}', methods: ['GET'])]
    public function getCustomer(int $id): Response
    {
        //gerer les droits
        $customer = $this->customerService->getCustomerById($id);

        if (!$customer) {
            return $this->json(['message' => 'customer not found'], Response::HTTP_NOT_FOUND);
        }

        $customerJson = $this->serializer->serialize($customer, 'json', SerializationContext::create()->setGroups(['customer', 'default']));

        return new Response($customerJson, Response::HTTP_OK, ['Content-Type' => 'application/json']);
    }

    #[Route('/customer', methods: ['POST'])]
    public function updateCustomer(Request $request): Response
    {

        try {
            $content = json_decode($request->getContent(), true);
            $customerData = $content['body'];
            $customerJson = json_encode($customerData);

            $customer = $this->serializer->deserialize($customerJson, Customer::class, 'json');
            $result = $this->customerService->update($customer);
        } catch (Exception $e) {
            return new Response('Error processing request: ' . $e->getMessage(), Response::HTTP_BAD_REQUEST);
        }

        $jsonResponse = $this->serializer->serialize($result, 'json', SerializationContext::create()->setGroups(['customer', 'default']));
        return new Response($jsonResponse, Response::HTTP_CREATED, ['Content-Type' => 'application/json']);
    }


    #[Route('/customers', methods: ['GET'])]
    public function getCustomers(Request $request): Response
    {
        //gerer les droits/roles?

        try {
            $jsonQuery = json_encode($request->query->all());

            $filters = $this->serializer->deserialize($jsonQuery, CustomerFilter::class, 'json');
            $customers = $this->customerService->findByFilter($filters);
            $customersJson = $this->serializer->serialize($customers, 'json', SerializationContext::create()->setGroups(['customer', 'default']));
        } catch (Exception $e) {
            return new Response('Invalid input: ' . $e->getMessage(), Response::HTTP_BAD_REQUEST);
        }

        return new Response($customersJson, Response::HTTP_OK, ['Content-Type' => 'application/json']);
    }
}
