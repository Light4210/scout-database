<?php

namespace App\Controller\struct;

use App\Entity\Struct;
use App\Entity\User;
use App\Form\StructCreateType;
use App\Form\StructEditType;
use App\Form\UserCreateType;
use App\Service\EditableService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;
use function Symfony\Component\String\s;

class StructController extends AbstractController
{
    public function show(EntityManagerInterface $entityManager, Request $request): Response
    {
        $id = $request->attributes->get('id');
        $struct = $entityManager->find(Struct::class, $id);
        if (!$struct) {
            return $this->render('admin/single/404.html.twig');
        }
        return $this->render('admin/struct/struct.html.twig', ['struct' => $struct]);
    }

    public function edit(EditableService $editableService, EntityManagerInterface $entityManager, Security $security, Request $request): Response
    {
        $currentUser = $security->getUser();

        $id = $request->attributes->get('id');
        $struct = $entityManager->find(Struct::class, $id);
        if (!$struct) {
            return $this->render('admin/single/404.html.twig');
        }

        $editable = $editableService->checkStruct($struct, $currentUser);
        if (!$editable) {
            $this->addFlash('fail', "You cant edit this struct");
            $url = $this->generateUrl('struct', ['id' => $id]);
            return $this->redirect($url);
        }

        $form = $this->createForm(StructEditType::class, $struct)->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
        }

        return $this->renderForm('admin/struct/edit-struct.html.twig', [
            'form' => $form,
            'struct' => $struct,
        ]);
    }

    public function create(Security $security, Request $request, EntityManagerInterface $entityManager): Response
    {
        $currentUser = $security->getUser();
        if($currentUser->get)
        $form = $this->createForm(StructCreateType::class)->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $struct = $form->getData();
            $entityManager->persist($struct);
            $entityManager->flush();
        }

        return $this->render('admin/user/create-user.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    public function list(): Response
    {
        return $this->render('admin/struct/struct-list.html.twig');
    }
}
