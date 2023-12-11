<?php

namespace App\Controller;

use App\Entity\Restaurant;
use App\Service\RestaurantService;
use Exception;
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

    #[Route('/restaurants-small', methods: ['GET'])]
    public function getRestaurantsSmall(Request $request): Response
    {
        try {
            $restaurants = $this->restaurantService->getRestaurant();
            $restaurantsJson = $this->serializer->serialize($restaurants, 'json', SerializationContext::create()->setGroups(['default']));
        } catch (Exception $e) {
            return new Response('Invalid input: ' . $e->getMessage(), Response::HTTP_BAD_REQUEST);
        }

        return new Response($restaurantsJson, Response::HTTP_OK, ['Content-Type' => 'application/json']);
    }

    #[Route('/restaurant', methods: ['POST'])]
    public function addRestaurant(Request $request, SerializerInterface $serializer): Response
    {
        try {
            $content = json_decode($request->getContent(), true);
            $restaurantData = $content['body'];
            $restaurantJson = json_encode($restaurantData);

            $restaurant = $this->serializer->deserialize($restaurantJson, Restaurant::class, 'json');

            $result = $this->restaurantService->save($restaurant);
        } catch (Exception $e) {
            return new Response('Error processing request: ' . $e->getMessage(), Response::HTTP_BAD_REQUEST);
        }

        $jsonResponse = $this->serializer->serialize($result, 'json', SerializationContext::create()->setGroups(['restaurant', 'default']));
        return new Response($jsonResponse, Response::HTTP_CREATED, ['Content-Type' => 'application/json']);
    }

    #[Route('/restaurant', methods: ['PUT'])]
    public function updateRestaurant(Request $request): Response
    {
        try {
            $content = json_decode($request->getContent(), true);
            $restaurantData = $content['body'];
            $restaurantJson = json_encode($restaurantData);

            $restaurant = $this->serializer->deserialize($restaurantJson, Restaurant::class, 'json');

            $result = $this->restaurantService->update($restaurant);
        } catch (Exception $e) {
            return new Response('Error processing request: ' . $e->getMessage(), Response::HTTP_BAD_REQUEST);
        }

        $jsonResponse = $this->serializer->serialize($result, 'json', SerializationContext::create()->setGroups(['restaurant', 'default']));
        return new Response($jsonResponse, Response::HTTP_CREATED, ['Content-Type' => 'application/json']);
    }
}
