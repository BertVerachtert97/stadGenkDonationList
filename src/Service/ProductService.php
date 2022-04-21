<?php

namespace App\Service;

use App\Entity\Environment;
use App\Entity\Product;
use App\Repository\ProductRepository;

class ProductService
{
    private ProductRepository $productRepository;
    private EnvironmentService $environmentService;

    public function __construct(ProductRepository $productRepository, EnvironmentService $environmentService)
    {
        $this->productRepository = $productRepository;
        $this->environmentService = $environmentService;
    }

    public function getProductListByEnviromentId(int $environmentId): array
    {
        $products = $this->productRepository->getProductListByEnvironmentId($environmentId);

        return [
            'environment' => $this->environmentService->getCurrentEnvironment($environmentId),
            'products' => $products,
        ];
    }

    public function getPublicProductListByEnviromentId(int $environmentId): array
    {
        $products = $this->productRepository->getPublicProductListByEnvironmentId($environmentId);

        return [
            'environment' => $this->environmentService->getCurrentEnvironment($environmentId),
            'products' => $products
        ];
    }

    public function getProduct(int $id): Product
    {
        return $this->productRepository->findOneBy(['id' => $id]);
    }
}