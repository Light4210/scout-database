<?php


namespace App\Service;

use App\Entity\Struct;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class PromotionService extends AbstractController
{

    public function getFutureStructSlug($currentStructType): string
    {
        switch ($currentStructType) {
            case Struct::COMMUNITY_SLUG:
                return Struct::TROOP_SLUG;
            case Struct::TROOP_SLUG:
                return Struct::CIRCLE_SLUG;
        }
        return $currentStructType;
    }
}