<?php

namespace App\Controller\user;

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
        $currentUser = $security->getUser();

        $role = $request->attributes->get('role');

        if (User::ACTIVE_MINISTRY[$currentUser->getMinistry()]['access'] < User::PRIORITY_NATIONAL_COUNCIL) {
            return $redirectService->redirectWithPopup(
                RedirectService::MESSAGE_TYPE['fail'],
                RedirectService::MESSAGE_TEXT['ACCESS_DENIED'],
                'index',
                []
            );
        }

        if ($role == 'all') {
            $users = $entityManager->getRepository(User::class)->findAll();
        } else if (array_key_exists($role, [User::ROLE_SCOUT, User::ROLE_TRAVELLER, User::ROLE_WOLVES])) {
            $users = $entityManager->getRepository(User::class)->findBy(['role' => $role]);
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
}