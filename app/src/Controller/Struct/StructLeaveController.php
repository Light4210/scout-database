<?php

namespace App\Controller\Struct;

use App\Entity\User;
use App\Entity\Struct;
use App\Entity\Notification;
use App\Service\EditableService;
use App\Repository\UserRepository;
use App\Repository\StructRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\NotificationRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use function Symfony\Component\String\u;

class StructLeaveController extends AbstractController
{
    public function __invoke(EntityManagerInterface $entityManager, UserRepository $userRepository, EditableService $editableService, NotificationRepository $notificationRepository, StructRepository $structRepository, Request $request)
    {
        $id = $request->attributes->get('id');
        $struct = $structRepository->find($id);
        /** @var User $user */
        $user = $this->getUser();
        if (!$struct || !$user || $struct->getSheaf()->getId() == $user->getId() || $user->getRole() !== User::ROLE_TRAVELLER || $user->getStruct() == null || $user->getStruct()->getType() !== Struct::CIRCLE_SLUG) {
            return $this->render('admin/single/404.html.twig');
        }

        $user->setStruct(null);
        $entityManager->flush($user);

        return $this->redirectToRoute('struct.list');
    }
}
