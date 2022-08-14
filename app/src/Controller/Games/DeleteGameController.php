<?php

namespace App\Controller\Games;

use App\Entity\Game;
use App\Entity\User;
use App\Form\CreateGameType;
use App\Repository\GameRepository;
use App\Service\AttachmentService;
use App\Service\RedirectService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class DeleteGameController extends AbstractController
{
    public function __invoke(RedirectService $redirectService, AttachmentService $attachmentService, Request $request, GameRepository $gameRepository, EntityManagerInterface $entityManager)
    {
        /** @var User $user */
        $user = $this->getUser();
        if($user->getUserPermision() != User::PRIORITY_NATIONAL_COUNCIL){
            return $redirectService->redirectWithPopup(
                RedirectService::MESSAGE_TYPE['fail'],
                RedirectService::MESSAGE_TEXT['SOMETHING_WENT_WRONG'],
                'game.list'
            );
        }
        $game = $gameRepository->find($request->attributes->get('id'));
        $game->delete();
        $entityManager->persist($game);
        $entityManager->flush();
        return $this->redirectToRoute('game.list');
    }
}