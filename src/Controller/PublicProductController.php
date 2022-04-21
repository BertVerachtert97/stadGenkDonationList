<?php

namespace App\Controller;

use App\Entity\Donator;
use App\Entity\Environment;
use App\Service\ProductService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PublicProductController extends AbstractController
{
    private ProductService $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    #[Route('/donatielijst', name: 'app_public_product')]
    public function index(): Response
    {
        $environment = new Environment();

        return $this->render('public_product/index.html.twig', [
            'environments' => $environment->getEnvironments(),
        ]);
    }

    #[Route('/donatielijst/{environmentId}/producten', name: 'app_public_product_list')]
    public function list(int $environmentId): Response
    {
        $data = $this->productService->getPublicProductListByEnviromentId($environmentId);

        return $this->render('public_product/productList.html.twig', [
            'data' => $data
        ]);
    }

    #[Route('/donatielijst/{environmentId}/donatie', name: 'app_public_product_donatie')]
    public function donatie(int $environmentId, Request $request, EntityManagerInterface $entityManager): Response
    {
        $int = 0;
        $donationText = '';

        foreach ($request->query->all() as $key => $item)
        {
            if ($key === 'name' || $key === 'surname') {
                continue;
            }

            if ($int > 0) {
                $donationText .= ', ';
                $int++;
            }

            $product = $this->productService->getProduct($key);
            $product->setDonatedAmount($product->getDonatedAmount() + $item);

            if ($product->getClusterAmount() !== null) {
                $donationText .= $item . ' x ' . '(' . $product->getClusterAmount() . ' x ' . $product->getName() . ')';
            } else {
                $donationText .= $item . ' x ' . '(' . $product->getName() . ')';
            }

            $entityManager->persist($product);
            $entityManager->flush();
        }

        $donator = new Donator();
        $donator->setEnvironmentId($environmentId);
        $donator->setName($request->query->get('name'));
        $donator->setSurname($request->query->get('surname'));
        $donator->setDonation($donationText);

        $entityManager->persist($donator);
        $entityManager->flush();

        return $this->redirectToRoute('app_public_product_confirmation');
    }

    #[Route('/donatielijst/confirmatie', name: 'app_public_product_confirmation')]
    public function confirmation(): Response
    {
        return $this->render('confirmation/donation.html.twig');
    }
}
