<?php
namespace App\Service;
use App\Entity\Product;
use App\Filter\ProductFilter;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use ReflectionClass;
use Symfony\Component\PropertyAccess\PropertyAccess;
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

    public function save(Product $product): Product
    {
        $errors = $this->validator->validate($product);
        if (count($errors) > 0) {
            throw new Exception('Invalid product');
        }

        $product->setCreatedAt(new \DateTimeImmutable('now'));
        $this->em->persist($product);
        $this->em->flush();

        return $product;
    }



    public function update(Product $product): Product
    {
        $existingProduct = $this->em->getRepository(Product::class)->find($product->getId());

        if (!$existingProduct) {
            throw new Exception("Product not found");
        }

        $errors = $this->validator->validate($product);
        if (count($errors) > 0) {
            throw new Exception('Invalid product');
        }

        $reflClass = new ReflectionClass($product);
        $propertyAccessor = PropertyAccess::createPropertyAccessor();
        foreach ($reflClass->getProperties() as $property) {
            $name = $property->getName();
            if ($name !== 'id' && !$property->getValue($existingProduct) instanceof Collection) {
                $value = $propertyAccessor->getValue($product, $name);
                $propertyAccessor->setValue($existingProduct, $name, $value);
            }
        }

        foreach ($existingProduct->getProductStocks() as $existingProductStock) {
            $foundInNew = false;

            foreach ($product->getProductStocks() as $newProductStock) {
                if ($existingProductStock->getId() === $newProductStock->getId()) {
                    $existingProductStock->setStockQuantity($newProductStock->getStockQuantity());

                    $foundInNew = true;
                    break;
                }
            }

            if (!$foundInNew) {
                $existingProduct->getProductStocks()->removeElement($existingProductStock);
            }
        }
        foreach ($product->getProductStocks() as $newProductStock) {
            if (!$existingProduct->getProductStocks()->contains($newProductStock)) {
                $existingProduct->addProductStock($newProductStock);
            }
        }


        $this->em->flush();

        return $product;
    }
}