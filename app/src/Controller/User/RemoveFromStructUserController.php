<?php

namespace App\Controller\User;

use App\Entity\User;
use App\Entity\Struct;
use App\Service\RedirectService;
use App\Service\EditableService;
use Symfony\Bundle\MakerBundle\Str;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class RemoveFromStructUserController extends AbstractController
{
    public function __invoke(Request $request, Security $security, EditableService $editableService, EntityManagerInterface $entityManager, RedirectService $redirectService)
    {
        $currentUser = $security->getUser();

        $id = $request->attributes->get('id');
        $user = $entityManager->find(User::class, $id);

        if (!$user) {
            return $this->render('admin/single/404.html.twig');
        }

        if ($user->getId() == $currentUser->getId()) {
            return $redirectService->redirectWithPopup(
                RedirectService::MESSAGE_TYPE['fail'],
                RedirectService::MESSAGE_TEXT['USER_SELF_REMOVE'],
                'user',
                [
                    'id' => $id
                ]
            );        }

        $editable = $editableService->checkUser($user, $currentUser);
        if (!$editable) {
            return $redirectService->redirectWithPopup(
                RedirectService::MESSAGE_TYPE['fail'],
                RedirectService::MESSAGE_TEXT['USER_DELETE_DENIED'],
                'user',
                [
                    'id' => $id
                ]
            );
        }

        /** @var Struct $previousStruct */
        $previousStruct = $user->getStruct();

        $user->removeUserFromStruct();

        $entityManager->persist($user);
        $entityManager->flush();

        return $this->redirectToRoute('struct', ['id'=> $previousStruct->getId()]);
    }

}