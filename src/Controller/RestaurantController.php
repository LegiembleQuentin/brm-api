<?php

namespace App\Controller;

use App\Entity\Restaurant;
use App\Service\RestaurantService;
use JMS\Serializer\SerializationContext;
use JMS\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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

    //
    //    #[Route('/employees', name: 'app_employees', methods: ['GET'])]
    //    public function index(): Response
    //    {
    //        $employees = $this->employeeService->getEmployees();
    //        $jsonContent = $this->serializer->serialize($employees, 'json');
    //
    //        return new Response($jsonContent, Response::HTTP_OK, ['Content-Type' => 'application/json']);
    //    }

    #[Route('/restaurant/{id}', methods: ['GET'])]
    public function getRestaurant(int $id): Response
    {
        //gerer les droits
        $restaurant = $this->restaurantService->getRestaurantById($id);

        if (!$restaurant) {
            return $this->json(['message' => 'Restaurant not found'], Response::HTTP_NOT_FOUND);
        }

        $restaurantJson = $this->serializer->serialize($restaurant, 'json', SerializationContext::create()->setGroups(['restaurant', 'default']));

        return new Response($restaurantJson, Response::HTTP_OK, ['Content-Type' => 'application/json']);
    }

    // #[Route('/restaurant', methods: ['POST'])]
    // public function addRestaurant(Request $request, SerializerInterface $serializer): Response
    // {
    //     $content = $request->getContent();

    //     $restaurant = $serializer->deserialize($content, Restaurant::class, 'json');

    //     if ($restaurant->getId() !== null) {
    //         //retourner vers l'update

    //     }

    //     // $result = $this->restaurantService->save($restaurant);

    //     $jsonResponse = $this->serializer->serialize($result, 'json');
    //     return new Response($jsonResponse, Response::HTTP_CREATED, ['Content-Type' => 'application/json']);
    // }
}
