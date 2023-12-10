<?php
namespace App\Service;
use App\Entity\Product;
use App\Filter\ProductFilter;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ProductService
{
    private $em;
    private $validator;

    public function __construct(EntityManagerInterface $entityManager, ValidatorInterface $validator)
    {
        $this->em = $entityManager;
        $this->validator = $validator;
    }

    /**
     * @return Product[]
     */
    public function getProduct() : array
    {
        $productRepo = $this->em->getRepository(Product::class);
        return $productRepo->findAll();
    }

    /**
     * @return Product[]
     */
    public function findByFilter(ProductFilter $productFilter) : array
    {
        $productRepo = $this->em->getRepository(Product::class);
        return $productRepo->findProductsByFilter($productFilter);
    }

    public function getProductById(int $id): ?Product
    {
        $productRepo = $this->em->getRepository(Product::class);
        return $productRepo->find($id);
    }

//    public function save(Product $product): Product
//    {
//        if ($product->getStock()) {
//            foreach ($product->getStockItems() as $stockItem) {
//                // You might need to validate each stock item here
//                // $stockErrors = $this->validator->validate($stockItem);
//                // if (count($stockErrors) > 0) {
//                //     throw new Exception('Invalid stock item');
//                // }
//
//                // Persist each stock item
//                $this->em->persist($stockItem);
//            }
//        }
//
//
//        $errors = $this->validator->validate($product);
//        if (count($errors) > 0) {
//            throw new Exception('Invalid product');
//        }
//
//        $product->setCreatedAt(new DateTimeImmutable('now'));
//
//        $this->em->persist($product);
//        $this->em->flush();
//
//        return $product;
//    }
}