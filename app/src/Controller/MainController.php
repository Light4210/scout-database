<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    #[Route('/default', name: 'default')]
    public function index(): Response
    {
        return $this->render('admin/single-pages/general-page.html.twig', [
            'controller_name' => 'MainController',
        ]);
    }
}
