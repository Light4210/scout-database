<?php

namespace App\Controller\user\promotions;

use App\Entity\User;
use App\Entity\Struct;
use App\Service\EditableService;
use App\Repository\UserRepository;
use App\Repository\StructRepository;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class GetPromotionData extends AbstractController
{
    public function __invoke(EditableService $editableService, Security $security, Request $request, UserRepository $userRepository, StructRepository $structRepository): Response
    {
        /** @var User $targetUser */
        $targetUser = $userRepository->find($request->attributes->get('target_user'));
        if (!$targetUser) {
            return new Response(
                "User not found",
                Response::HTTP_NOT_FOUND,
                ['content-type' => 'text/html']
            );
        }

        /** @var User $currentUser */
        $currentUser = $security->getUser();
        $editable = $editableService->checkUser($targetUser, $currentUser);
        if (!$editable) {
            return new Response(
                "You can`t edit user with id: $targetUser->getId()",
                Response::HTTP_FORBIDDEN,
                ['content-type' => 'text/html']
            );
        }

        /** @var Struct $currentStruct */
        $currentStructType = $targetUser->getStruct()->getType();
        $structSlug = null;
        switch ($currentStructType) {
            case Struct::COMMUNITY_SLUG:
                $structSlug = Struct::TROOP_SLUG;
                break;
            case Struct::TROOP_SLUG:
                $structSlug = Struct::CIRCLE_SLUG;
                break;
        }

        if ($structSlug === null) {
            return new Response(
                'Something went wrong, maybe user struct has bad slug',
                Response::HTTP_NOT_FOUND,
                ['content-type' => 'text/html']
            );
        }

        $possibleStructs = $structRepository->getDataForPromotion($structSlug);
        if (empty($possibleStructs)) {
            return new Response(
                "Possible structs for user with slug: $structSlug, was not found",
                Response::HTTP_NOT_FOUND,
                ['content-type' => 'text/html']
            );
        }

        return new Response(json_encode(
            $possibleStructs
        ));
    }
}