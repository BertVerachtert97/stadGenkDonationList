<?php

namespace App\Controller;

use App\Entity\Environment;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractController
{
    #[Route('/donatielijst/admin/dashboard', name: 'app_dashboard')]
    public function index(): Response
    {
        $environment = new Environment();

        return $this->render('dashboard/index.html.twig', [
            'environments' => $environment->getEnvironments(),
        ]);
    }
}
