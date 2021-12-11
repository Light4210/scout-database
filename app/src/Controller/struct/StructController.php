<?php

namespace App\Controller\struct;

use App\Entity\Struct;
use App\Form\StructCreateType;
use App\Form\StructEditType;
use App\Service\CreatableService;
use App\Service\EditableService;
use App\Service\RedirectService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Security;

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

    public function edit(EditableService $editableService, EntityManagerInterface $entityManager, Security $security, Request $request, RedirectService $redirectService): Response
    {
        $currentUser = $security->getUser();

        $id = $request->attributes->get('id');
        $struct = $entityManager->find(Struct::class, $id);
        if (!$struct) {
            return $this->render('admin/single/404.html.twig');
        }

        $editable = $editableService->checkStruct($struct, $currentUser);
        if (!$editable) {
            return $redirectService->redirectWithPopup(
                RedirectService::MESSAGE_TYPE['fail'],
                RedirectService::MESSAGE_TEXT['STRUCT_EDIT_DENIED'],
                'struct',
                [
                    'id' => $id
                ]
            );
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

    public function create(Security $security, Request $request, EntityManagerInterface $entityManager, CreatableService $creatableService, RedirectService $redirectService): Response
    {

        $currentUser = $security->getUser();
        $creatable = $creatableService->checkUserStructCreateResponsibility($currentUser);

        if (!$creatable) {
            return $redirectService->redirectWithPopup(
                RedirectService::MESSAGE_TYPE['fail'],
                RedirectService::MESSAGE_TEXT['STRUCT_EDIT_DENIED'],
                'user',
                [
                    'id' => $currentUser->getId()
                ]
            );
        }

        $form = $this->createForm(StructCreateType::class)->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $struct = $form->getData();
            $entityManager->persist($struct);
            $entityManager->flush();
            return $redirectService->redirectWithPopup(
                RedirectService::MESSAGE_TYPE['success'],
                RedirectService::MESSAGE_TEXT['USER_EDIT_DENIED'],
                'user',
                [
                    'id' => $currentUser->getId()
                ]
            );
            $this->addFlash('success', "Congratulation now you have your own struct :)");
            $url = $this->generateUrl('struct', ['id' => $struct->getId()]);
            return $this->redirect($url);
        }
        return $this->render('admin/struct/create-struct.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    public function list(EntityManagerInterface $entityManager): Response
    {
        $structs = $entityManager->getRepository(Struct::class)->findAll();
        return $this->render('admin/struct/struct-list.html.twig', ['structs' => $structs]);
    }
}
