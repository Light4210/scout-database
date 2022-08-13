<?php

namespace App\Controller\Struct;

use App\Entity\Struct;
use App\Entity\User;
use App\Repository\StructRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class SetSheafController extends AbstractController
{
    public function __invoke(StructRepository $structRepository, Request $request, UserRepository $userRepository, EntityManagerInterface $entityManager)
    {
        /** @var User $currentUser */
        $currentUser = $this->getUser();
        $targetUser = $userRepository->find($request->query->get('user'));
        $targetStruct = $structRepository->find($request->attributes->get('struct_id'));
        if ($currentUser->getUserPermision() == User::PRIORITY_NATIONAL_COUNCIL) {
            if (!empty($targetStruct->getSheaf())) {
               $previoslySheaf = $targetStruct->getSheaf();
                $previoslySheaf->setSheafOf(null);
                $entityManager->persist($previoslySheaf);
            }
            $targetUser->setSheafOf($targetStruct);
            $targetStruct->setSheaf($targetUser);
            $entityManager->persist($targetUser);
            $entityManager->persist($targetStruct);
            $entityManager->flush();
        }
        return $this->redirectToRoute('struct', ['id' => $targetStruct->getId()]);
    }
}
