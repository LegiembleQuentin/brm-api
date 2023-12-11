<?php

namespace App\Controller;

use App\Entity\Product;
use App\Entity\ProductStock;
use App\Entity\Stock;
use App\Filter\ProductFilter;
use App\Service\ProductService;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use JMS\Serializer\DeserializationContext;
use JMS\Serializer\SerializationContext;
use JMS\Serializer\SerializerBuilder;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


#[Route('/api')]
class ProductController extends AbstractController
{
    private $serializer;
    private $productService;
    private $em;

    public function __construct(EntityManagerInterface $entityManager, ProductService $productService)
    {
        $this->em = $entityManager;
        $this->productService = $productService;
        $this->serializer = SerializerBuilder::create()->build();
    }

    #[Route('/products', methods: ['GET'])]
    public function getProducts(Request $request): Response
    {
        try {
            $jsonQuery = json_encode($request->query->all());
            $filters = $this->serializer->deserialize($jsonQuery, ProductFilter::class, 'json', DeserializationContext::create()->setGroups(['default']));

            $stocks = $this->productService->findByFilter($filters);

            $context = SerializationContext::create()->setGroups(['product', 'default']);
            $stocksJson = $this->serializer->serialize($stocks, 'json', $context);
        }catch (Exception $e){
            return new Response('Invalid input: ' . $e->getMessage(), Response::HTTP_BAD_REQUEST);
        }
        return new Response($stocksJson, Response::HTTP_OK, ['Content-Type' => 'application/json']);
    }

    #[Route('/products-small', methods: ['GET'])]
    public function getProductSmall(Request $request): Response
    {
        try {
            $products = $this->productService->getProduct();
            $productsJson = $this->serializer->serialize($products, 'json', SerializationContext::create()->setGroups(['default']));
        }catch (Exception $e) {
            return new Response('Invalid input: ' . $e->getMessage(), Response::HTTP_BAD_REQUEST);
        }

        return new Response($productsJson, Response::HTTP_OK, ['Content-Type' => 'application/json']);
    }

    #[Route('/product', methods: ['POST'])]
    public function addStock(Request $request): Response
    {
        try {
            $content = json_decode($request->getContent(), true);
            $productData = $content['body'];
            $productJson = json_encode($productData);


            $product = $this->serializer->deserialize($productJson, Product::class, 'json');

            foreach ($productData['productStocks'] as $productStockData) {
                $stock = $this->em->getRepository(Stock::class)->find($productStockData['stock']['id']);

                if (!$stock) {
                    throw new Exception("Stock with ID {$productStockData['stock']['id']} does not exist.");
                }

                $productStock = new ProductStock();
                $productStock->setProduct($product);
                $productStock->setStock($stock);
                $productStock->setStockQuantity($productStockData['stock_quantity']);

                $product->addProductStock($productStock);
            }

            $result = $this->productService->save($product);

        } catch (Exception $e) {
            return new Response('Error processing request: ' . $e->getMessage(), Response::HTTP_BAD_REQUEST);
        }

        $jsonResponse = $this->serializer->serialize($result, 'json', SerializationContext::create()->setGroups(['product', 'default']));
        return new Response($jsonResponse, Response::HTTP_CREATED, ['Content-Type' => 'application/json']);
    }

    #[Route('/product', methods:  ['PUT'])]
    public function updateProduct(Request $request): Response
    {
        try {
            $content = json_decode($request->getContent(), true);
            $productData = $content['body'];
            $productJson = json_encode($productData);

            $product = $this->serializer->deserialize($productJson, Product::class, 'json');

            $result = $this->productService->update($product);
        }catch (Exception $e){
            return new Response('Error processing request ' . $e->getMessage(), Response::HTTP_BAD_REQUEST);
        }

        $jsonResponse = $this->serializer->serialize($result, 'json', SerializationContext::create()->setGroups(['stock', 'default']));
        return new Response($jsonResponse, Response::HTTP_CREATED, ['Content-Type' => 'application/json']);
    }

    #[Route('/product/{id}', methods: ['GET'])]
    public function getProduct(int $id): Response
    {
        try {
            // gerer les droits
            // ...

            $product = $this->productService->getProductById($id);

            if (!$product) {
                return $this->json(['message' => 'product not found'], Response::HTTP_NOT_FOUND);
            }

            $stockJson = $this->serializer->serialize($product, 'json', SerializationContext::create()->setGroups(['product', 'default']));
        } catch (Exception $e) {
            return new Response('Error processing request: ' . $e->getMessage(), Response::HTTP_BAD_REQUEST);
        }

        return new Response($stockJson, Response::HTTP_OK, ['Content-Type' => 'application/json']);
    }
}
