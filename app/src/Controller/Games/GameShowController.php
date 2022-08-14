<?php

namespace App\Controller\Games;

use App\Entity\Game;
use App\Repository\GameRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class GameShowController extends AbstractController
{
    public function __invoke(Request $request, GameRepository $gameRepository)
    {
        $game = $gameRepository->find($request->attributes->get('id'));
        return $this->render('admin/game/game.html.twig', [
            'game' => $game
        ]);
    }
}