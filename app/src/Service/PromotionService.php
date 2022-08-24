<?php


namespace App\Service;

use App\Entity\StructAssistant;
use App\Entity\User;
use App\Entity\Struct;
use App\Entity\Notification;
use App\Repository\StructAssistantRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\NotificationRepository;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PromotionService extends AbstractController
{

    private EntityManagerInterface $entityManager;
    private NotificationRepository $notificationRepository;

    public function __construct(EntityManagerInterface $entityManager, NotificationRepository $notificationRepository)
    {
        $this->entityManager = $entityManager;
        $this->notificationRepository = $notificationRepository;
    }

    public function getFutureStructSlug($currentStructType): string
    {
        switch ($currentStructType) {
            case Struct::COMMUNITY_SLUG:
                return Struct::TROOP_SLUG;
            case Struct::TROOP_SLUG:
                return Struct::CIRCLE_SLUG;
        }
        return $currentStructType;
    }

    public function joinAssistant(UserInterface|User $user, Struct $struct)
    {
        $structAssistant = new StructAssistant($user, $struct);
        $this->entityManager->persist($structAssistant);
        $this->entityManager->flush();
    }

    public function promoteUserToStruct(UserInterface|User $user, Struct $struct)
    {
        $user->setRole(Struct::STRUCT[$struct->getType()]['membersRole']);
        $user->setStruct($struct);
        $this->entityManager->flush($user);
    }

    public function approveRequest(UserInterface|User $user, Struct $struct)
    {
        /** @var Notification $approvedNotification */
        $approvedNotification = $this->notificationRepository->findOneBy(['targetUser' => $user, 'toUser' => $struct->getSheaf(), 'type' => [Notification::TYPE_TRANSFER, Notification::TYPE_REQUEST], 'status' => Notification::STATUS_PENDING]);
        $approvedNotification->setStatus(Notification::STATUS_APPROVED);
        $this->entityManager->flush($approvedNotification);
    }

    public function declineRequest(UserInterface|User $user, Struct $struct)
    {
        /** @var Notification $approvedNotification */
        $approvedNotification = $this->notificationRepository->findOneBy(['targetUser' => $user, 'toUser' => $struct->getSheaf(), 'type' => [Notification::TYPE_TRANSFER, Notification::TYPE_REQUEST], 'status' => Notification::STATUS_PENDING]);
        $approvedNotification->setStatus(Notification::STATUS_DECLINED);
        $this->entityManager->flush($approvedNotification);
    }
}