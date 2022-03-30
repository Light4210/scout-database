<?php

namespace App\Controller\Invitations;

use App\Entity\User;
use App\Entity\Invite;
use App\Form\InviteUserType;
use App\Form\StructCreateType;
use App\Service\RedirectService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class InviteController extends AbstractController
{
    public function __invoke(RedirectService $redirectService, Request $request)
    {
        $currentUser = $this->getUser();    
        if (!in_array(User::ROLE_ADMIN, $currentUser->getRoles())) {
            return $redirectService->redirectWithPopup(
                RedirectService::MESSAGE_TYPE['fail'],
                RedirectService::MESSAGE_TEXT['INVITE_DENIED'],
                'user',
                [
                    'id' => $currentUser->getId()
                ]
            );
        }
        $form = $this->createForm(InviteUserType::class)->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            dd($data);
//            $invite = new Invite($data);
        }
            //TODO add read by xml file
        return $this->render('admin/invitation/invite.html.twig', [
            'form' => $form->createView()
        ]);
    }
}