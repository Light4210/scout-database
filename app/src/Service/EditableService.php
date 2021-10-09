<?php


namespace App\Service;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class EditableService extends AbstractController
{
    public function check(string $entityClass):bool
    {
        return false;
    }
}


