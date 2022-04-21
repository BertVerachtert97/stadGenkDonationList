<?php

namespace App\Controller;

use App\Entity\Environment;
use App\Entity\Product;
use App\FormType\ProductFormType;
use App\Service\DonatorService;
use App\Service\EnvironmentService;
use App\Service\ProductService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminProductController extends AbstractController
{
    private ProductService $productService;
    private DonatorService $donatorService;
    private EnvironmentService $environmentService;

    public function __construct(
        ProductService $productService,
        DonatorService $donatorService,
        EnvironmentService $environmentService
    ) {
        $this->productService = $productService;
        $this->donatorService = $donatorService;
        $this->environmentService = $environmentService;
    }

    #[Route('/donatielijst/admin/{environmentId}/producten', name: 'app_admin_product_list')]
    public function index(int $environmentId): Response
    {
        $data = $this->productService->getProductListByEnviromentId($environmentId);

        return $this->render('admin_product/index.html.twig', [
            'data' => $data
        ]);
    }

    #[Route('/donatielijst/admin/{environmentId}/donaties', name: 'app_admin_donations')]
    public function donations(int $environmentId): Response
    {
        $data = $this->donatorService->getDonatorListByEnvironmentId($environmentId);

        return $this->render('admin_product/donationList.html.twig', [
            'data' => $data
        ]);
    }

    #[Route('/donatielijst/admin/{environmentId}/addProduct', name: 'app_admin_product_create')]
    public function createProduct(int $environmentId, EntityManagerInterface $entityManager, Request $request): Response
    {
        $product = new Product();
        $product->setEnvironmentId($environmentId);
        $product->setDonatedAmount(0);
        $form = $this->createForm(ProductFormType::class, $product);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $product = $form->getData();
            $entityManager->persist($product);
            $entityManager->flush();

            return $this->redirectToRoute('app_admin_product_list', [
                'environmentId' => $environmentId
            ]);
        }

        return $this->render('admin_product/addProduct.html.twig', [
            'productForm' => $form->createView(),
            'environment' => $this->environmentService->getCurrentEnvironment($environmentId)
        ]);
    }

    #[Route('/donatielijst/admin/{environmentId}/editProduct/{id}', name: 'app_admin_product_edit')]
    public function editProduct(
        int $environmentId,
        int $id,
        EntityManagerInterface $entityManager,
        Request $request
    ): Response {
        $product = $this->productService->getProduct($id);
        $form = $this->createForm(ProductFormType::class, $product);
        $warning = '';

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var Product $product */
            $product = $form->getData();
            if ($product->getAmount() > $product->getDonatedAmount()) {
                $entityManager->persist($product);
                $entityManager->flush();

                return $this->redirectToRoute('app_admin_product_list', [
                    'environmentId' => $environmentId
                ]);
            }
            $warning = 'Gelieve aantal gelijk of hoger dan het aantal gedoneerde items te zetten.';
        }

        return $this->render('admin_product/editProduct.html.twig', [
            'productForm' => $form->createView(),
            'environment' => $this->environmentService->getCurrentEnvironment($environmentId),
            'product' => $product,
            'warning' => $warning,
        ]);
    }
}
