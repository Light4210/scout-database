<?php

namespace App\Controller\User\Promotions;

use App\Service\TransferService;
use App\Service\PromotionService;
use App\Repository\UserRepository;
use App\Repository\StructRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PromoteDeclineController extends AbstractController
{
    public function __invoke(TransferService $transferService, StructRepository $structRepository, PromotionService $promotionService, Request $request, UserRepository $userRepository)
    {
        $targetUser = $userRepository->find($request->attributes->get('user_id'));
        $targetStruct = $structRepository->find($request->attributes->get('struct_id'));
        $referer = $request->headers->get('referer');

        if (empty($targetUser) || empty($targetStruct)) {
            return $this->redirect($referer);
        }

        $promotionService->declineRequest($targetUser, $targetStruct);

        return $this->redirect($referer);
    }
}