<?php

namespace App\Controller\User;

use App\Entity\User;
use App\Form\UserEditType;
use App\Service\RedirectService;
use App\Service\EditableService;
use App\Service\AttachmentService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use function Symfony\Component\String\u;

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

        $form = $this->createForm(UserEditType::class, $user);
        $photo = $user->getPhoto();
        $dealScan = $user->getDealScan();

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->has('photo')) {
                $file = $form->get('photo')->getData();
                if ($file) {
                    $photo = $attachmentService->createPhoto($file);
                }
                $user->setPhoto($photo);
            }
//            dd($form->get('photo')->getData(), $form->get('dealScan')->getData());
            if ($form->has('dealScan')) {
                $file = $form->get('dealScan')->getData();
                if ($file) {
                    $dealScan = $attachmentService->createDealScan($file);
                }
                $user->setDealScan($dealScan);
            }

            $entityManager->persist($user);
            $entityManager->flush();
        }

        if ($user->getPhoto() instanceof UploadedFile || $user->getPhoto() === null) {
            $user->setPhoto($photo);
        }
        if ($user->getDealScan() instanceof UploadedFile|| $user->getPhoto() === null) {
            $user->setDealScan($dealScan);
        }


        return $this->renderForm('admin/user/edit-user.html.twig', [
            'form' => $form,
            'editable' => $editable,
            'user' => $user,
            'currentUser' => $currentUser,
        ]);
    }
}