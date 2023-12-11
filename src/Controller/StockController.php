<?php

namespace App\Controller;

use App\Entity\Stock;
use App\Filter\StockFilter;
use App\Service\StockService;
use Exception;
use JMS\Serializer\DeserializationContext;
use JMS\Serializer\SerializationContext;
use JMS\Serializer\SerializerBuilder;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api')]
class StockController extends AbstractController
{

    private $serializer;
    private $stockService;

    public function __construct(StockService $stockService)
    {
        $this->stockService = $stockService;
        $this->serializer = SerializerBuilder::create()->build();
    }

    #[Route('/stocks', methods: ['GET'])]
    public function getStocks(Request $request): Response
    {
        try {
            $jsonQuery = json_encode($request->query->all());
            $filters = $this->serializer->deserialize($jsonQuery, StockFilter::class, 'json', DeserializationContext::create()->setGroups(['default']));

            $alert = $request->query->get('alert') === 'true';
            $filters->setAlert($alert);

            $stocks = $this->stockService->findByFilter($filters);

            $context = SerializationContext::create()->setGroups(['stock', 'default']);
            $stocksJson = $this->serializer->serialize($stocks, 'json', $context);
        }catch (Exception $e){
            return new Response('Invalid input: ' . $e->getMessage(), Response::HTTP_BAD_REQUEST);
        }
        return new Response($stocksJson, Response::HTTP_OK, ['Content-Type' => 'application/json']);
    }

    #[Route('/stock', methods: ['POST'])]
    public function addStock(Request $request): Response
    {
        try {
            $content = json_decode($request->getContent(), true);
            $stockData = $content['body'];
            $stockJson = json_encode($stockData);

            $stock = $this->serializer->deserialize($stockJson, Stock::class, 'json');

            $result = $this->stockService->save($stock);

        } catch (Exception $e) {
            return new Response('Error processing request: ' . $e->getMessage(), Response::HTTP_BAD_REQUEST);
        }

        $jsonResponse = $this->serializer->serialize($result, 'json', SerializationContext::create()->setGroups(['stock', 'default']));
        return new Response($jsonResponse, Response::HTTP_CREATED, ['Content-Type' => 'application/json']);
    }

    #[Route('/stock', methods:  ['PUT'])]
    public function updateStock(Request $request): Response
    {
        try {
            $content = json_decode($request->getContent(), true);
            $stockData = $content['body'];
            $stockJson = json_encode($stockData);

            $stock = $this->serializer->deserialize($stockJson, Stock::class, 'json');

            $result = $this->stockService->update($stock);
        }catch (Exception $e){
            return new Response('Error processing request ' . $e->getMessage(), Response::HTTP_BAD_REQUEST);
        }

        $jsonResponse = $this->serializer->serialize($result, 'json', SerializationContext::create()->setGroups(['stock', 'default']));
        return new Response($jsonResponse, Response::HTTP_CREATED, ['Content-Type' => 'application/json']);
    }

    #[Route('/stock/{id}', methods: ['GET'])]
    public function getStock(int $id): Response
    {
        try {
            // gerer les droits
            // ...

            $stock = $this->stockService->getStockById($id);

            if (!$stock) {
                return $this->json(['message' => 'Stock not found'], Response::HTTP_NOT_FOUND);
            }

            $stockJson = $this->serializer->serialize($stock, 'json', SerializationContext::create()->setGroups(['stock', 'default']));
        } catch (Exception $e) {
            return new Response('Error processing request: ' . $e->getMessage(), Response::HTTP_BAD_REQUEST);
        }

        return new Response($stockJson, Response::HTTP_OK, ['Content-Type' => 'application/json']);
    }

    #[Route('/stock/{id}', methods: ['DELETE'])]
    public function deleteStock(int $id): Response
    {
        // GESTION DES ROLES

        try {
            $this->stockService->delete($id);

            return new Response(null, Response::HTTP_NO_CONTENT);
        } catch (\Exception $e) {

            return $this->json(['message' => 'Error deleting stock: ' . $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    #[Route('/stocks-small', methods: ['GET'])]
    public function getStocksSmall(Request $request): Response
    {
        try {
            $stocks = $this->stockService->getStocks();
            $stocksJson = $this->serializer->serialize($stocks, 'json', SerializationContext::create()->setGroups(['default']));
        }catch (Exception $e) {
            return new Response('Invalid input: ' . $e->getMessage(), Response::HTTP_BAD_REQUEST);
        }

        return new Response($stocksJson, Response::HTTP_OK, ['Content-Type' => 'application/json']);
    }
}
