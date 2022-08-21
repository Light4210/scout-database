<?php

namespace App\Controller\Struct;

use App\Entity\User;
use App\Entity\Struct;
use App\Entity\Notification;
use Doctrine\DBAL\Exception;
use App\Service\EditableService;
use App\Service\RedirectService;
use App\Repository\UserRepository;
use App\Repository\StructRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\NotificationRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class StructJoinRequestController extends AbstractController
{
    public function __invoke(RedirectService $redirectService, EntityManagerInterface $entityManager, UserRepository $userRepository, EditableService $editableService, NotificationRepository $notificationRepository, StructRepository $structRepository, Request $request)
    {
        $id = $request->attributes->get('id');
        try {
            $struct = $structRepository->find($id);
        } catch (Exception $exception) {
            return $this->render('admin/single/404.html.twig');
        }

        /** @var User $user */
        $user = $this->getUser();
        if (!$user || $user->getRole() !== User::ROLE_TRAVELLER || $user->getStruct() !== null) {
            return $this->render('admin/single/404.html.twig');
        }

        $notification = new Notification('New request to your struct', 'request', Notification::TYPE_REQUEST, $user, $user, $struct->getSheaf());

        $isDuplicateExist = $notificationRepository->findOneBy(['status' => Notification::STATUS_PENDING, 'type' => Notification::TYPE_REQUEST, 'targetUser' => $user, 'fromUser' => $user, 'toUser' => $struct->getSheaf()]);

        if ($isDuplicateExist) {
            return $redirectService->redirectWithPopup(
                RedirectService::MESSAGE_TYPE['fail'],
                RedirectService::MESSAGE_TEXT['ALREADY_SENDED_REQUEST'],
                'struct.list',
                []
            );
        }

        $entityManager->persist($notification);
        $entityManager->flush($notification);

        return $redirectService->redirectWithPopup(
            RedirectService::MESSAGE_TYPE['success'],
            RedirectService::MESSAGE_TEXT['JOIN_REQUEST_SUCCESS'],
            'struct.list',
            []
        );
    }
}
