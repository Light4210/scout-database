<?php

namespace App\Controller\User;

use App\Entity\User;
use App\Service\RedirectService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ListUserController extends AbstractController
{
    public function __invoke(Request $request, EntityManagerInterface $entityManager, Security $security, RedirectService $redirectService)
    {
        /** @var User $currentUser */
        $users = $entityManager->getRepository(User::class)->findBy(['role' => User::ROLE_TRAVELLER]);

        return $this->render('admin/user/user-list.html.twig', ['users' => $users]);
    }
}