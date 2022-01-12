<?php

namespace App\Repository;

use App\Entity\User;
use App\Entity\Struct;
use App\Entity\Notification;
use App\Service\PromotionService;
use Doctrine\Persistence\ManagerRegistry;
use DoctrineExtensions\Query\Mysql\IfElse;
use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @method Struct|null find($id, $lockMode = null, $lockVersion = null)
 * @method Struct|null findOneBy(array $criteria, array $orderBy = null)
 * @method Struct[]    findAll()
 * @method Struct[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class StructRepository extends ServiceEntityRepository
{
    private PromotionService $promotionService;
    private NotificationRepository $notificationRepository;
    private UserRepository $userRepository;

    const SELECTED_STATUS_DISABLED = 'disabled';

    public function __construct(UserRepository $userRepository, ManagerRegistry $registry, NotificationRepository $notificationRepository, PromotionService $promotionService)
    {
        parent::__construct($registry, Struct::class);
        $this->promotionService = $promotionService;
        $this->notificationRepository = $notificationRepository;
        $this->userRepository = $userRepository;
    }

    public function getDataForPromotion(User|UserInterface $fromUser, User|UserInterface $targetUser)
    {
        $structQuery = $this->createQueryBuilder('s')
            ->select('s.id', 's.name', 's.city', 'IDENTITY(s.sheaf) as sheaf')
            ->where('s.type = :type')
            ->setParameter('type', $this->promotionService->getFutureStructSlug($targetUser->getStruct()->getType()));
        $userRequests = $this->notificationRepository->isExistRequestByAllStructs($fromUser, $targetUser);
        /** @var Struct[] $possibleStructs */
        $possibleStructs = $structQuery->getQuery()->getResult();


        foreach ($userRequests as $request) {
            foreach ($possibleStructs as $index => $struct) {
                $sheaf = $this->userRepository->find($struct['sheaf']);
                if ($request->getTargetUser()->getId() == $targetUser->getId() &&
                    $request->getFromUser()->getId() == $fromUser->getId() &&
                    $request->getType() == Notification::TYPE_TRANSFER &&
                    $request->getToUser()->getId() == $sheaf->getId()) {
                    $possibleStructs[$index]['selectedStatus'] = self::SELECTED_STATUS_DISABLED;
                }
            }
        }
        return $possibleStructs;
    }
}
