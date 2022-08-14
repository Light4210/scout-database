<?php

namespace App\Controller\Games;

use App\Entity\Game;
use App\Form\CreateGameType;
use App\Form\EditBigGameType;
use App\Form\EditSimpleGameType;
use App\Repository\GameRepository;
use App\Service\AttachmentService;
use App\Service\EditableService;
use App\Service\RedirectService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class GameEditController extends AbstractController
{
    public function __invoke(RedirectService $redirectService, EditableService $editableService, AttachmentService $attachmentService, Request $request, GameRepository $gameRepository, EntityManagerInterface $entityManager)
    {
        $game = $gameRepository->find($request->attributes->get('id'));
        $editable = $editableService->checkGame($game);
        if($editable == false){
            return $redirectService->redirectWithPopup(
                RedirectService::MESSAGE_TYPE['fail'],
                RedirectService::MESSAGE_TEXT['SOMETHING_WENT_WRONG'],
                'game.list'
            );
        }
        $formType = '';
        $templateName = '';
        if ($game->getType() == Game::BIG_GAME) {
            $formType = EditBigGameType::class;
            $templateName = 'edit-big-game';
        } elseif ($game->getType() == Game::SIMPLE_GAME) {
            $formType = EditSimpleGameType::class;
            $templateName = 'edit-simple-game';
        }
        $form = $this->createForm($formType, $game)->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var Game $game */
            $game = $form->getData();
            if ($form->has('ilustration') && $form->get('ilustration')->getData() !== null) {
                $ilustration = $attachmentService->createIlustration($form->get('ilustration')->getData());
                $game->setIlustration($ilustration);
            }
            $game->setStatus(Game::STATUS_PENDING);
            $game->setAuthor($this->getUser());
            $entityManager->persist($game);
            $entityManager->flush();

            return $redirectService->redirectWithPopup(
                RedirectService::MESSAGE_TYPE['success'],
                RedirectService::MESSAGE_TEXT['GAME_EDITED'],
                'game.list'
            );
        }
        return $this->render("admin/game/$templateName.html.twig", [
            'form' => $form->createView(),
            'game' => $game
        ]);
    }
}