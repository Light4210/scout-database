<?php


namespace App\Service;

use App\Entity\Game;
use App\Entity\Struct;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class EditableService extends AbstractController
{


    /**
     * @param User $targetUser
     * @param User $currentUser
     * @return bool
     */
    public function checkUser(User $targetUser, UserInterface $currentUser): bool
    {
        $editable = false;
        $targetUserStruct = $targetUser->getStruct();
        if ($currentUser->getId() == $targetUser->getId()) {
            $editable = true;
            return $editable;
        } elseif (!$targetUserStruct) {
            return $editable;
        }

        if ($targetUserStruct->getSheaf() === null) {
            return false;
        }

        $targetUserSheafId = $targetUserStruct->getSheaf()->getId();
        if ($currentUser->getId() === $targetUserSheafId && ($targetUser->getRole() == User::ROLE_SCOUT || $targetUser->getRole() == User::ROLE_WOLVES)) {
            $editable = true;
        }
        return $editable;
    }

    public function checkStruct(Struct $struct, UserInterface $currentUser): bool
    {
        $editable = false;

        if ($struct->getSheaf() === null) {
            return $editable;
        }

        $structSheafId = $struct->getSheaf()->getId();

        if ($structSheafId == $currentUser->getId()) {
            $editable = true;
            return $editable;
        }

        return $editable;
    }

    public function checkGame(Game $game): bool
    {
        $user = $this->getUser();
        if ($user->getUserIdentifier() == $game->getAuthor()->getUserIdentifier() || $user->getUserPermision() == User::PRIORITY_NATIONAL_COUNCIL) {
            return true;
        }
        return false;
    }
}


