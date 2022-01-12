<?php

namespace App\Controller\struct;

use App\Entity\Struct;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ListStructController extends AbstractController
{
    public function __invoke(EntityManagerInterface $entityManager)
    {
        $structs = $entityManager->getRepository(Struct::class)->findAll();
        return $this->render('admin/struct/struct-list.html.twig', ['structs' => $structs]);
    }
}
