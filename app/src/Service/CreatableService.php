<?php

namespace App\Service;

use App\Entity\User;
use Symfony\Component\Security\Core\User\UserInterface;

class CreatableService
{
    public function checkUserStructCreateResponsibility(User|UserInterface $user): bool
    {
        $creatable = false;

        if (empty($user->getSheafOf()) && ($user->getMinistry() == User::ACTIVE_MINISTRY['troopLeader']['slug'] || $user->getMinistry() == User::ACTIVE_MINISTRY['sheaf']['slug'] || $user->getMinistry() == User::ACTIVE_MINISTRY['akela']['slug'])) {
            $creatable = true;
            return $creatable;
        }

        return $creatable;
    }
}