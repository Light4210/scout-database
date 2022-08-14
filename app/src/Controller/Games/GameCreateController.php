<?php

namespace App\Controller\Games;

use App\Entity\Game;
use App\Form\CreateGameType;
use App\Repository\GameRepository;
use App\Service\AttachmentService;
use App\Service\RedirectService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class GameCreateController extends AbstractController
{
    public function __invoke(RedirectService $redirectService, AttachmentService $attachmentService, Request $request, GameRepository $gameRepository, EntityManagerInterface $entityManager)
    {
        $form = $this->createForm(CreateGameType::class)->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var Game $game */
            $game = $form->getData();
            if ($form->has('ilustration') && $form->get('ilustration')->getData() !== null) {
                $ilustration = $attachmentService->createIlustration($form->get('ilustration')->getData());
                $game->setIlustration($ilustration);
            }
            $game->setAuthor($this->getUser());
            $game->setStatus(Game::STATUS_PENDING);
            $entityManager->persist($game);
            $entityManager->flush();

            return $redirectService->redirectWithPopup(
                RedirectService::MESSAGE_TYPE['success'],
                RedirectService::MESSAGE_TEXT['CREATE_GAME_SUCCESS'],
                'game.list'
            );
        }
        return $this->render('admin/game/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}