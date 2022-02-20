<?php

namespace App\Service;

use App\Entity\User;
use App\Entity\Notification;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\NotificationRepository;
use Symfony\Component\Security\Core\User\UserInterface;

class TransferService
{

    private NotificationRepository $notificationRepository;
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager, NotificationRepository $notificationRepository)
    {
        $this->notificationRepository = $notificationRepository;
        $this->entityManager = $entityManager;
    }

    public function closeAllUserTransfers(User|UserInterface $user)
    {
        $activeUserTransfers = $this->notificationRepository->getAllPromotionRequests($user);
        foreach ($activeUserTransfers as $transfer){
            $transfer->setStatus(Notification::STATUS_DECLINED);
            $this->entityManager->persist($transfer);
        }
        $this->entityManager->flush();
    }
}