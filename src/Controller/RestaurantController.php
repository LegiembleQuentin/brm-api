<?php

namespace App\Controller;

use App\Service\RestaurantService;
use JMS\Serializer\SerializationContext;
use JMS\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api')]
class RestaurantController extends AbstractController
{
    private $serializer;
    private $restaurantService;

    public function __construct(SerializerInterface $serializer, RestaurantService $restaurantService)
    {
        $this->serializer = $serializer;
        $this->restaurantService = $restaurantService;
    }

    #[Route('/restaurants', name: 'app_restaurants', methods: ['GET'])]
    public function index(): Response
    {
        $restaurants = $this->restaurantService->getRestaurant();
        $restaurantsJson = $this->serializer->serialize($restaurants, 'json', SerializationContext::create()->setGroups(['restaurant', 'default']));

        return new Response($restaurantsJson, Response::HTTP_OK, ['Content-Type' => 'application/json']);
    }
}
