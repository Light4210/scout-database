<?php

namespace App\Controller\user;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Security;

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
        $post = $entityManager->find(User::class, $id);
        return $this->render('admin/user/user.html.twig', ['post' => $post]);
    }

    public function edit(Security $security, EntityManagerInterface $entityManager, Request $request): Response
    {
        $id = $request->attributes->get('id');
        $targetUser = $entityManager->find(User::class, $id);
        if (!$targetUser) {
            throw $this->createNotFoundException("Not found user with id: $id");
        }
        $currentUser = $security->getUser();
        $self = $currentUser->getId() == $targetUser->getId();
        return $this->render('admin/user/edit-user.html.twig', ['user' => $targetUser, 'self' => $self]);
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

