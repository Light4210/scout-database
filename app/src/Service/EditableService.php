<?php


namespace App\Service;

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

        $targetUserSheafId = $targetUserStruct->getSheaf()->getId();
        if ($currentUser->getId() === $targetUserSheafId && ($targetUser->getRole() == User::ROLES['scout'] || $targetUser->getRole() == User::ROLES['wolvies'])) {
            $editable = true;
        }
        return $editable;
    }

    /**
     * @param User $targetScout
     * @param User $currentUser
     * @return bool
     */
    public function checkPromotion(User $targetScout, UserInterface $currentUser): bool
    {
        $editable = true;
        return $editable;
    }

    public function checkStruct(Struct $struct, UserInterface $currentUser): bool
    {
        $editable = false;

        $structSheafId = $struct->getSheaf()->getId();

        if ($structSheafId == $currentUser->getId()) {
            $editable = true;
                return $editable;
        }

        return $editable;
    }
}


