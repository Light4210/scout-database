<?php

namespace App\Service;

use App\Entity\User;
use Symfony\Component\Security\Core\User\UserInterface;

class CreatableService
{
    public function checkUserStructCreateResponsibility(User|UserInterface $user): bool
    {
        $creatable = false;

        if (empty($user->getSheafOf()) && ($user->getMinistry() == User::MINISTRY['troopLeader']['name'] || $user->getMinistry() == User::MINISTRY['sheaf']['name'] || $user->getMinistry() == User::MINISTRY['akela']['name'])) {
            $creatable = true;
            return $creatable;
        }

        return $creatable;
    }
}