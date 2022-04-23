<?php

namespace App\Controller\Invitations;

use App\Entity\User;
use App\Form\RegisterType;
use App\Service\RedirectService;
use App\Repository\UserRepository;
use App\Service\AttachmentService;
use App\Repository\InviteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\PasswordHasherFactoryInterface;

class RegisterController extends AbstractController
{

    public function __invoke(AttachmentService $attachmentService, UserRepository $userRepository, PasswordHasherFactoryInterface $PasswordHasherFactoryInterface, InviteRepository $inviteRepository, EntityManagerInterface $entityManager, MailerInterface $mailer, RedirectService $redirectService, Request $request)
    {
        $form = $this->createForm(RegisterType::class)->handleRequest($request);
        $invite = $inviteRepository->findOneBy(['token' => $request->query->get('token')]);
        $currentUser = $this->getUser();
        if($currentUser !== null){
            return $redirectService->redirectWithPopup(
                RedirectService::MESSAGE_TYPE['fail'],
                RedirectService::MESSAGE_TEXT['LOGOUT_TO_CONTINUE'],
                'user',
                ['id' => $currentUser->getId()]
            );
        }
        if ($invite === null) {
            return $redirectService->redirectWithPopup(
                RedirectService::MESSAGE_TYPE['fail'],
                RedirectService::MESSAGE_TEXT['SOMETHING_WENT_WRONG'],
                'index',
                []
            );
        } elseif ($userRepository->findOneBy(['email' => $invite->getEmail()])) {
            return $redirectService->redirectWithPopup(
                RedirectService::MESSAGE_TYPE['fail'],
                RedirectService::MESSAGE_TEXT['USER_REGISTERED'],
                'index',
                []
            );
        }

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var User $user */
            $user = $form->getData();
            $user->setEmail($invite->getEmail());
            $user->setStatus(User::STATUS_ACTIVE);
            $user->setRole(User::ROLE_TRAVELLER);
            $user->setMinistry($invite->getMinistry());
            $user->setPassword(($PasswordHasherFactoryInterface->getPasswordHasher($user)->hash(($user->getPassword()))));
            $user = $form->getData();
            if ($form->has('photo') && $form->get('photo')->getData() !== null) {
                $photo = $attachmentService->createPhoto($form->get('photo')->getData());
                $user->setPhoto($photo);
            }
            if ($form->has('dealScan') && $form->get('dealScan')->getData() !== null) {
                $dealScan = $attachmentService->createDealScan($form->get('dealScan')->getData());
                $user->setDealScan($dealScan);
            }
            $user->setCreatedAt(new \DateTimeImmutable());
            $entityManager->persist($user);
            $entityManager->flush();
            return $this->redirectToRoute('app_login', []);
        }

        return $this->render('admin/invitation/register.html.twig', [
            'form' => $form->createView()
        ]);
    }
}