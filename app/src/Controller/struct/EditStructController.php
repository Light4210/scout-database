<?php

namespace App\Controller\struct;

use App\Entity\Struct;
use App\Form\StructEditType;
use App\Service\EditableService;
use App\Service\RedirectService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use function Symfony\Component\String\s;

class EditStructController extends AbstractController
{
    public function __invoke(EditableService $editableService, EntityManagerInterface $entityManager, Security $security, Request $request, RedirectService $redirectService): Response
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
            $struct = $form->getData();
            $entityManager->persist($struct);
            $entityManager->flush();
        }

        return $this->renderForm('admin/struct/edit-struct.html.twig', [
            'form' => $form,
            'struct' => $struct,
        ]);
    }
}
