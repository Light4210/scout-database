<?php

namespace App\Controller\user;

use App\Entity\User;
use App\Form\UserEditType;
use App\Service\RedirectService;
use App\Service\EditableService;
use App\Service\AttachmentService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class EditUserController extends AbstractController
{

    public function __invoke(AttachmentService $attachmentService, EditableService $editableService, Request $request, EntityManagerInterface $entityManager, UserEditType $userEditType, Security $security, RedirectService $redirectService)
    {
        $currentUser = $security->getUser();

        $id = $request->attributes->get('id');
        $user = $entityManager->find(User::class, $id);
        if (!$user) {
            return $this->render('admin/single/404.html.twig');
        }

        $editable = $editableService->checkUser($user, $currentUser);
        if (!$editable) {
            return $redirectService->redirectWithPopup(
                RedirectService::MESSAGE_TYPE['fail'],
                RedirectService::MESSAGE_TEXT['USER_EDIT_DENIED'],
                'user',
                [
                    'id' => $id
                ]
            );
        }

        $form = $this->createForm(UserEditType::class, $user)->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->has('photo') && $form->get('photo')->getData() !== null) {
                $photo = $attachmentService->createAttachment($form->get('photo')->getData(), $form->get('photo')->getName());
                $user->setPhoto($photo);
            }
            if ($form->has('dealScan') && $form->get('dealScan')->getData() !== null) {
                $dealScan = $attachmentService->createAttachment($form->get('dealScan')->getData(), $form->get('dealScan')->getName());
                $user->setDealScan($dealScan);
            }
            $entityManager->persist($user);
            $entityManager->flush();
        }

        return $this->renderForm('admin/user/edit-user.html.twig', [
            'form' => $form,
            'user' => $user,
            'currentUser' => $currentUser,
        ]);
    }
}