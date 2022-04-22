<?php

namespace App\Controller\User;

use App\Entity\Struct;
use App\Form\UserCreateType;
use App\Service\RedirectService;
use App\Service\AttachmentService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CreateUserController extends AbstractController
{
    public function __invoke(AttachmentService $attachmentService, Request $request, EntityManagerInterface $entityManager, Security $security, RedirectService $redirectService, UserCreateType $userCreateType)
    {
        $currentUser = $security->getUser();
        $id = $currentUser->getId();
        $sheafStruct = $entityManager->getRepository(Struct::class)->findOneBy(['sheaf' => $id]);
        if (!$sheafStruct) {
            return $redirectService->redirectWithPopup(
                RedirectService::MESSAGE_TYPE['fail'],
                RedirectService::MESSAGE_TEXT['CREATE_USER_WITH_NO_STRUCT'],
                'user',
                [
                    'id' => $id
                ]
            );
        } elseif ($sheafStruct->getType() == Struct::CIRCLE_SLUG || $sheafStruct->getType() == 'none'){
            return $redirectService->redirectWithPopup(
                RedirectService::MESSAGE_TYPE['fail'],
                RedirectService::MESSAGE_TEXT['CANT_CREATE_USER'],
                'struct',
                [
                    'id' => $sheafStruct->getId()
                ]
            );
        }

        $form = $this->createForm(UserCreateType::class)->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
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

            return $this->redirectToRoute('struct', [
                'id' => $sheafStruct->getId()
            ]);
        }

        return $this->render('admin/user/create-user.html.twig', [
            'form' => $form->createView(),
            'struct' => $sheafStruct
        ]);
    }
}