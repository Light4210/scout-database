<?php

namespace App\Controller\Invitations;

use App\Entity\User;
use App\Entity\Invite;
use App\Form\InviteUserType;
use App\Service\RedirectService;
use Symfony\Component\Mime\Email;
use App\Repository\UserRepository;
use App\Repository\InviteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use function Symfony\Component\String\u;

class InviteController extends AbstractController
{
    /**
     * @param MailerInterface $mailer
     * @param RedirectService $redirectService
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function __invoke(InviteRepository $inviteRepository, UserRepository $userRepository, EntityManagerInterface $entityManager, MailerInterface $mailer, RedirectService $redirectService, Request $request)
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
            /** @var Invite $data */
            $data = $form->getData();
            $registerLink = $request->getScheme() . '://' . $request->getHttpHost() . '/register?token=' . $data->getToken();

            /** @var User $user */
            $user = $userRepository->findOneBy(['email' => $data->getEmail()]);
            /** @var Invite $invite */
            $invite = $inviteRepository->findOneBy(['email' => $data->getEmail()]);

            if ($user !== null) {
                return $redirectService->redirectWithPopup(
                    RedirectService::MESSAGE_TYPE['fail'],
                    RedirectService::MESSAGE_TEXT['USER_EXIST'],
                    'invite',
                    []
                );
            }

            //TODO add exception
            try {
                $email = (new Email())
                    ->from('from@example.com')
                    ->to($data->getEmail())
                    ->subject('Welcome to UIGSE database!')
                    ->text("this registration link is valid 10 days: $registerLink");
                $mailer->send($email);
            } catch (\Exception $exception){
                $this->addFlash(
                    RedirectService::MESSAGE_TYPE['fail'],
                    RedirectService::MESSAGE_TEXT['SOMETHING_WENT_WRONG']
                );
            }
            $entityManager->persist($data);
            $entityManager->flush($data);

            $this->addFlash(
                RedirectService::MESSAGE_TYPE['success'],
                RedirectService::MESSAGE_TEXT['SENDED_INVITE']
            );
        }

        //TODO add read by xml file
        return $this->render('admin/invitation/invite.html.twig', [
            'form' => $form->createView()
        ]);
    }
}