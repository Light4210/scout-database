<?php

namespace App\Controller\struct;

use http\Env\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManager;

class StructController extends AbstractController
{
    public function show(EntityManager $entityManager, Request $request): Response
    {
        return $this->render('admin/struct/struct.html.twig');
    }

    public function edit(): Response
    {
        return $this->render('admin/struct/edit-struct.html.twig');
    }

    public function add(): Response
    {
        return $this->render('admin/struct/add-struct.html.twig');
    }

    public function list(): Response
    {
        return $this->render('admin/struct/struct-list.html.twig');
    }
}
