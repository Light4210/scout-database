<?php

namespace App\Controller\user;

use App\Entity\User;
use App\Entity\Struct;
use App\Form\UserEditType;
use App\Form\UserCreateType;
use App\Service\EditableService;
use App\Service\RedirectService;
use App\Service\AttachmentService;
use Symfony\form\FormBuilderInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use function Symfony\Component\String\u;


class UserController extends AbstractController
{
    /**
     * @param EntityManagerInterface $entityManager
     * @param Request $request
     * @return Response
     */
    public function show(EditableService $editableService, Security $security, EntityManagerInterface $entityManager, Request $request): Response
    {
        $id = $request->attributes->get('id');
        $currentUser = $security->getUser();
        $user = $entityManager->find(User::class, $id);
        if (!$user) {
            return $this->render('admin/single/404.html.twig');
        }

        $editable = $editableService->checkUser($user, $currentUser);
        return $this->render('admin/user/user.html.twig', ['user' => $user, 'editable' => $editable]);
    }

    /**
     * @param EditableService $editableService
     * @param EntityManagerInterface $entityManager
     * @param AttachmentService $attachmentService ,
     * @param Request $request
     * @return Response
     */
    public function edit(Security $security, EditableService $editableService, EntityManagerInterface $entityManager, AttachmentService $attachmentService, Request $request, RedirectService $redirectService
    ): Response
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
        ]);
    }

    /**
     * @param AttachmentService $attachmentService ,
     * @param Security $security
     * @param EntityManagerInterface $entityManager
     * @param Request $request
     * @return Response
     */
    public function create(AttachmentService $attachmentService, Security $security, EntityManagerInterface $entityManager, Request $request, RedirectService $redirectService): Response
    {
        $currentUser = $security->getUser();
        $id = $currentUser->getId();
        $sheafStruct = $entityManager->getRepository(Struct::class)->findOneBy(['sheaf' => $id]);
        if (!$sheafStruct) {
            return $redirectService->redirectWithPopup(
                RedirectService::MESSAGE_TYPE['fail'],
                RedirectService::MESSAGE_TEXT['CREATE_USER_WITH_NO_STRUCT'],
                'user',
                [
                    'id' => $id
                ]
            );
        }

        $form = $this->createForm(UserCreateType::class)->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();
            if ($form->has('photo') && $form->get('photo')->getData() !== null) {
                $photo = $attachmentService->createAttachment($form->get('photo')->getData(), $form->get('photo')->getName());
                $user->setPhoto($photo);
            }
            if ($form->has('dealScan') && $form->get('dealScan')->getData() !== null) {
                $dealScan = $attachmentService->createAttachment($form->get('dealScan')->getData(), $form->get('dealScan')->getName());
                $user->setDealScan($dealScan);
            }
            $user->setCreatedAt(new \DateTimeImmutable());
            $entityManager->persist($user);
            $entityManager->flush();
        }

        return $this->render('admin/user/create-user.html.twig', [
            'form' => $form->createView(),
            'struct' => $sheafStruct
        ]);
    }

    public function list(Security $security, EntityManagerInterface $entityManager, Request $request, RedirectService $redirectService): Response
    {
        $currentUser = $security->getUser();

        $role = $request->attributes->get('role');

        if (User::MINISTRY[$currentUser->getMinistry()]['access'] < User::PRIORITY['nationalCouncil']) {
            return $redirectService->redirectWithPopup(
                RedirectService::MESSAGE_TYPE['fail'],
                RedirectService::MESSAGE_TEXT['ACCESS_DENIED'],
                'index',
                []
            );
        }
        if ($role == 'all') {
            $users = $entityManager->getRepository(User::class)->findAll();
        } else if (array_key_exists($role, User::ROLES)) {
            $users = $entityManager->getRepository(User::class)->findBy(['role' => User::ROLES[$role]]);
        } else {
            return $redirectService->redirectWithPopup(
                RedirectService::MESSAGE_TYPE['fail'],
                RedirectService::MESSAGE_TEXT['WRONG_ROLE_NAME'],
                'index',
                []
            );
        }

        return $this->render('admin/user/user-list.html.twig', ['users' => $users]);
    }

    public function promoteWolvies(Security $security, EntityManagerInterface $entityManager, Request $request, User $wolviews, EditableService $editableService, RedirectService $redirectService): Response
    {
        $currentUser = $security->getUser();

        if (!$editableService->checkWolviewsPromotion($wolviews, $currentUser)) {
            return $redirectService->redirectWithPopup(
                RedirectService::MESSAGE_TYPE['fail'],
                RedirectService::MESSAGE_TEXT['ACCESS_DENIED'],
                'index',
                []
            );
        }
        $wolviews->setRole(User::ROLES['scout']);
        $entityManager->persist($wolviews);
        $entityManager->flush();
        $url = $this->generateUrl('user', ['id' => $wolviews->getId()]);
        return $this->redirect($url);
    }
}