<?php

namespace App\Controller\struct;

use App\Entity\Struct;
use App\Form\StructEditType;
use App\Service\EditableService;
use App\Service\RedirectService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ShowStructController extends AbstractController
{
    public function __invoke(EntityManagerInterface $entityManager, Request $request)
    {

        $id = $request->attributes->get('id');
        $struct = $entityManager->find(Struct::class, $id);
        if (!$struct) {
            return $this->render('admin/single/404.html.twig');
        }
        return $this->render('admin/struct/struct.html.twig', ['struct' => $struct]);
    }
}
