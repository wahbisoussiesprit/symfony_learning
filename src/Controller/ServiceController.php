<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ServiceController extends AbstractController
{
    #[Route('/service/{name}', name: 'app_service')]
    public function index($name): Response
    {
        return $this->render('service/index.html.twig', [
            'name' => $name,
        ]);
    }

    #[Route('/goIndex', name: 'app_goindex')]
    public function goToIndex():Response
    {return $this->redirectToRoute('app_home');}
}
