<?php

namespace App\Controller\Games;

use App\Entity\Game;
use App\Repository\GameRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class GameListController extends AbstractController
{
    public function __invoke(Request $request, GameRepository $gameRepository)
    {
        $games = $gameRepository->getGames($this->getUser());
        return $this->render('admin/game/game-list.html.twig', [
            'games' => $games
        ]);
    }
}