<?php

namespace App\Controller\user\promotions;

use App\Entity\User;
use App\Entity\Struct;
use App\Entity\Notification;
use App\Service\RedirectService;
use App\Service\EditableService;
use App\Repository\UserRepository;
use App\Repository\StructRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\NotificationRepository;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SendPromotionRequest extends AbstractController
{
    public function __invoke(EditableService $editableService, NotificationRepository $notificationRepository, EntityManagerInterface $entityManager, Request $request, Security $security, UserRepository $userRepository, StructRepository $structRepository): Response
    {

        $targetUserId = $request->attributes->get('target_user');
        /** @var User $targetUser */
        $targetUser = $userRepository->find($targetUserId);

        $targetStructId = $request->attributes->get('struct');
        /** @var Struct $targetStruct */
        $targetStruct = $structRepository->find($targetStructId);

        if (!$targetUser) {
            return new Response(
                "User with id: $targetUserId, not found",
                Response::HTTP_NOT_FOUND,
                ['content-type' => 'text/html']
            );
        } else if (!$targetStruct) {
            return new Response(
                "Struct with id: $targetStructId, not found",
                Response::HTTP_NOT_FOUND,
                ['content-type' => 'text/html']
            );
        }

        $toUser = $userRepository->find($targetStruct->getSheaf());
        if (!$toUser) {
            return new Response(
                "Sheaf for struct with id: $targetStructId, not found",
                Response::HTTP_NOT_FOUND,
                ['content-type' => 'text/html']
            );
        }

        $fromUser = $security->getUser();
        if (!$targetStruct) {
            return new Response(
                "You are logout",
                Response::HTTP_NOT_FOUND,
                ['content-type' => 'text/html']
            );
        }

        $editable = $editableService->checkUser($targetUser, $fromUser);
        if (!$editable) {
            return new Response(
                "You can`t edit user with id: $targetUserId",
                Response::HTTP_FORBIDDEN,
                ['content-type' => 'text/html']
            );
        }

        $structType = Struct::STRUCT[$targetStruct->getType()]['name'];
        $structName = $targetStruct->getName();
        $username = $targetUser->getName();
        $existingRequest = $notificationRepository->findBy(['targetUser'=>$targetUserId, 'fromUser'=>$fromUser, 'toUser'=>$toUser, 'type' => Notification::TYPE_TRANSFER]);
        if ($existingRequest) {
            return new Response(
                "You have already sent a request for $username(id: $targetUserId) to $structName(id: $targetStructId)",
                Response::HTTP_CONFLICT,
                ['content-type' => 'text/html']
            );
        }

        $requestNotification = new Notification(
            "You have new user suggestion to your $structType",
            'Transfer request',
            Notification::TYPE_TRANSFER,
            $targetUser,
            $fromUser,
            $toUser
        );

        $entityManager->persist($requestNotification);
        $entityManager->flush();

        $targetStructName = $targetStruct->getName();

        return new Response(
            "Request is successfully sended to $targetStructName",
            Response::HTTP_OK,
            ['content-type' => 'text/html']
        );
    }
}