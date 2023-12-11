<?php

namespace App\Controller;

use App\Filter\OrderFilter;
use App\Filter\StockFilter;
use App\Service\OrderService;
use App\Service\StockService;
use DateTimeImmutable;
use Exception;
use JMS\Serializer\DeserializationContext;
use JMS\Serializer\SerializationContext;
use JMS\Serializer\SerializerBuilder;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api')]
class OrderController extends AbstractController
{
    private $serializer;
    private $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
        $this->serializer = SerializerBuilder::create()->build();
    }



    #[Route('/orders', methods: ['GET'])]
    public function getOrders(Request $request): Response
    {
        try {
            $jsonQuery = json_encode($request->query->all());
            $filters = $this->serializer->deserialize($jsonQuery, OrderFilter::class, 'json', DeserializationContext::create()->setGroups(['default']));

            if ($request->query->get('date') != 'null' && $request->query->get('date') != 'undefined') {
                $date = DateTimeImmutable::createFromFormat('D M d Y H:i:s e+', $request->query->get('date'));
                $filters->setDate($date);
            }

            $orders = $this->orderService->findByFilter($filters);

            $context = SerializationContext::create()->setGroups(['order', 'default']);
            $ordersJson = $this->serializer->serialize($orders, 'json', $context);
        }catch (Exception $e){
            return new Response('Invalid input: ' . $e->getMessage(), Response::HTTP_BAD_REQUEST);
        }
        return new Response($ordersJson, Response::HTTP_OK, ['Content-Type' => 'application/json']);
    }


}
