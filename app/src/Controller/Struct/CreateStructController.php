<?php

namespace App\Controller\Struct;

use App\Form\StructCreateType;
use App\Service\RedirectService;
use App\Service\CreatableService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CreateStructController extends AbstractController
{
    public function __invoke(Security $security, Request $request, EntityManagerInterface $entityManager, CreatableService $creatableService, RedirectService $redirectService)
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
        }

        return $this->render('admin/struct/create-struct.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
