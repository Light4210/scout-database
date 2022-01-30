<?php

namespace App\Controller\User;

use App\Entity\User;
use App\Service\RedirectService;
use App\Service\EditableService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ShowUserController extends AbstractController
{

    public function __invoke(EditableService $editableService, Request $request, EntityManagerInterface $entityManager, Security $security, RedirectService $redirectService)
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
}