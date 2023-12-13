<?php

namespace App\Controller;

use App\Service\DashboardService;
use JMS\Serializer\SerializerBuilder;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api')]
class DashboardController extends AbstractController
{
    private $serializer;
    private $dashboardService;

    public function __construct(DashboardService $dashboardService)
    {
        $this->dashboardService = $dashboardService;
        $this->serializer = SerializerBuilder::create()->build();
    }

    #[Route('/dashboard/product-sales', methods: ['GET'])]
    public function getProductSales(): Response
    {
        try {
            $productSales = $this->dashboardService->getProductSales();

            $productSalesJson = $this->serializer->serialize($productSales, 'json');
        }catch (\Exception $e){
            return new Response('Invalid input: ' . $e->getMessage(), Response::HTTP_BAD_REQUEST);
        }

        return new Response($productSalesJson, Response::HTTP_OK, ['Content-Type' => 'application/json']);
    }

    #[Route('/dashboard/sales', methods: ['GET'])]
    public function getSales(): Response
    {
        try {
            $sales = $this->dashboardService->getSales();

            $salesJson = $this->serializer->serialize($sales, 'json');
        }catch (\Exception $e){
            return new Response('Invalid input: ' . $e->getMessage(), Response::HTTP_BAD_REQUEST);
        }

        return new Response($salesJson, Response::HTTP_OK, ['Content-Type' => 'application/json']);
    }
}
