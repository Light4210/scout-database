<?php

namespace App\Repository;

use App\Entity\User;
use App\Entity\Struct;
use App\Entity\Notification;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @method Notification|null find($id, $lockMode = null, $lockVersion = null)
 * @method Notification|null findOneBy(array $criteria, array $orderBy = null)
 * @method Notification[]    findAll()
 * @method Notification[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class NotificationRepository extends ServiceEntityRepository
{

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Notification::class);
    }

    public function isExistRequestByNewStruct(User|UserInterface $fromUser, User|UserInterface $targetUser, Struct $targetStruct): array
    {
        $toUser = $this->getEntityManager()->getRepository(User::class)->find($targetStruct->getSheaf());
        return $this->findBy(['targetUser' => $targetUser->getId(), 'fromUser' => $fromUser, 'toUser' => $toUser, 'type' => Notification::TYPE_TRANSFER]);
    }

    public function isExistRequestByAllStructs(User|UserInterface $fromUser, User|UserInterface $targetUser): array
    {
        return $this->findBy(['targetUser' => $targetUser->getId(), 'fromUser' => $fromUser, 'type' => Notification::TYPE_TRANSFER]);
    }

    public function getPromotionRequestsToUser(UserInterface|User $user): array
    {
        $userRequests = $this->findBy(['toUser' => $user, 'status' => Notification::STATUS_PENDING, 'type' => Notification::TYPE_REQUEST]);
        $promotionRequests =  $this->findBy(['toUser' => $user, 'status' => Notification::STATUS_PENDING, 'type' => Notification::TYPE_TRANSFER]);
        return array_merge_recursive($userRequests, $promotionRequests);
    }

    public function getAllPromotionRequests(UserInterface $user): array
    {
        return $this->findBy(['targetUser' => $user, 'status' => Notification::STATUS_PENDING, 'type' => Notification::TYPE_TRANSFER]);
    }
}