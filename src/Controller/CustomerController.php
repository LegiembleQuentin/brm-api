<?php

namespace App\Controller;

use App\Filter\CustomerFilter;
use App\Service\CustomerService;
use Exception;
use JMS\Serializer\SerializationContext;
use JMS\Serializer\SerializerBuilder;
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
        $this->serializer = SerializerBuilder::create()->build();;
        $this->customerService = $customerService;
    }

    #[Route('/customer', methods: ['GET'])]
    public function getCustomers(Request $request, SerializerInterface $serializer): Response
    {
        //gerer les droits/roles?

        try {
            $jsonQuery = json_encode($request->query->all());

            $filters = $serializer->deserialize($jsonQuery, CustomerFilter::class, 'json');

            $enabled = $request->query->get('enabled') === 'true';
            $filters->setEnabled($enabled);
        } catch (Exception $e) {
            return new Response('Invalid input: ' . $e->getMessage(), Response::HTTP_BAD_REQUEST);
        }

        $customer = $this->customerService->findByFilter($filters);
        $customerJson = $this->serializer->serialize($customer, 'json', SerializationContext::create()->setGroups(['customer', 'default']));

        return new Response($customerJson, Response::HTTP_OK, ['Content-Type' => 'application/json']);
    }
}
