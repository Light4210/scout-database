<?php

namespace App\Controller\Struct;

use App\Entity\User;
use App\Entity\Struct;
use App\Entity\Notification;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\NotificationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ListStructController extends AbstractController
{
    public function __invoke(NotificationRepository $notificationRepository, EntityManagerInterface $entityManager)
    {
        $structs = $entityManager->getRepository(Struct::class)->findAll();
        /** @var User $user */
        $user = $this->getUser();
        if ($user->getRole() == User::ROLE_TRAVELLER && $user->getStruct() === null) {
            foreach ($structs as $struct) {
                $isDuplicateExist = $notificationRepository->findOneBy(['status' => Notification::STATUS_PENDING, 'type' => Notification::TYPE_REQUEST, 'targetUser' => $user, 'fromUser' => $user, 'toUser' => $struct->getSheaf()]);
                if ($isDuplicateExist) {
                    $struct->setRequestStatus(Struct::REQUEST_STATUS_PENDING);
                }
            }
        }
        return $this->render('admin/struct/struct-list.html.twig', ['structs' => $structs]);
    }
}
