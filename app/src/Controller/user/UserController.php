<?php

namespace App\Controller\user;

use App\Entity\User;
use App\Service\EditableService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class UserController extends AbstractController
{
    /**
     * @param EntityManagerInterface $entityManager
     * @param Request $request
     * @return Response
     */
    public function show(EntityManagerInterface $entityManager, Request $request): Response
    {
        $id = $request->attributes->get('id');
        $user = $entityManager->find(User::class, $id);
        if (!$user) {
            return $this->render('admin/single/404.html.twig');
        }
        return $this->render('admin/user/user.html.twig', ['user' => $user]);
    }

    /**
     * @param EditableService $editableService
     * @param EntityManagerInterface $entityManager
     * @param Request $request
     * @return Response
     */
    public function edit(EditableService $editableService, EntityManagerInterface $entityManager, Request $request): Response
    {
        $id = $request->attributes->get('id');
        $user = $entityManager->find(User::class, $id);
        //TODO add logic to editable controller, which post you can edit, $editable(true or false)
        $editable = $editableService->check($user::class);
        if (!$user) {
            return $this->render('admin/single/404.html.twig');
        } elseif (!$editable) {
            $this->addFlash('fail', "You cant edit this user");
            $url = $this->generateUrl('user', ['id' => $id]);
            return $this->redirect($url);
        }

        $form = $this->createForm($user::class)->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
        }
        return $this->render('admin/user/edit-user.html.twig', ['user' => $user, 'editable' => $editable]);
    }

    public function add(): Response
    {
        return $this->render('admin/user/add-user.html.twig');
    }

    public function list(): Response
    {
        return $this->render('admin/user/user-list.html.twig');
    }
}

