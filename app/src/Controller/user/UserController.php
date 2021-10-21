<?php

namespace App\Controller\user;

use App\Entity\Struct;
use App\Entity\User;
use App\Form\UserCreateType;
use App\Form\UserEditType;
use App\Service\EditableService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Stopwatch\Section;
use Symfony\form\FormBuilderInterface;

class UserController extends AbstractController
{
    /**
     * @param EntityManagerInterface $entityManager
     * @param Request $request
     * @return Response
     */
    public function show(EditableService $editableService, Security $security, EntityManagerInterface $entityManager, Request $request): Response
    {
        $id = $request->attributes->get('id');
        $currentUser = $security->getUser();
        $user = $entityManager->find(User::class, $id);
        $editable = false;
        if (!$user) {
            return $this->render('admin/single/404.html.twig');
        }

        $editable = $editableService->checkUser($user, $currentUser);
        return $this->render('admin/user/user.html.twig', ['user' => $user, 'editable' => $editable]);
    }

    /**
     * @param EditableService $editableService
     * @param EntityManagerInterface $entityManager
     * @param Request $request
     * @return Response
     */
    public function edit(Security $security, EditableService $editableService, EntityManagerInterface $entityManager, Request $request): Response
    {
        $currentUser = $security->getUser();

        $id = $request->attributes->get('id');
        $user = $entityManager->find(User::class, $id);
        if (!$user) {
            return $this->render('admin/single/404.html.twig');
        }

        $editable = $editableService->checkUser($user, $currentUser);
        if (!$editable) {
            $this->addFlash('fail', "You cant edit this user");
            $url = $this->generateUrl('user', ['id' => $id]);
            return $this->redirect($url);
        }

        $form = $this->createForm(UserEditType::class, $user)->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
        }
        return $this->renderForm('admin/user/edit-user.html.twig', [
            'form' => $form,
            'user' => $user,
        ]);
    }

    /**
     * @param Security $security
     * @param EntityManagerInterface $entityManager
     * @param Request $request
     * @return Response
     */
    public function create(Security $security, EntityManagerInterface $entityManager, Request $request): Response
    {
        $currentUser = $security->getUser();
        $id = $currentUser->getId();
        $sheafStruct = $entityManager->getRepository(Struct::class)->findOneBy(['sheaf' => $id]);
        if (!$sheafStruct) {
            $this->addFlash('fail', "You cant create new users without your own structure");
            $url = $this->generateUrl('user', ['id' => $id]);
            return $this->redirect($url);
        }

        $form = $this->createForm(UserCreateType::class)->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();
            $entityManager->persist($user);
            $user->setCreatedAt(new \DateTimeImmutable());
            $entityManager->flush();
        }

        return $this->render('admin/user/create-user.html.twig', [
            'form' => $form->createView(),
            'struct' => $sheafStruct
        ]);
    }

    public function list(Security $security, EntityManagerInterface $entityManager, Request $request): Response
    {
        $currentUser = $security->getUser();

        $role = $request->attributes->get('role');

        if(User::MINISTRY_PRIORITY[$currentUser->getMinistry()] < User::PRIORITY['nationalCouncil']){
            $this->addFlash('fail', "Access denied");
            $url = $this->generateUrl('index');
            return $this->redirect($url);
        }

        $users = $entityManager->getRepository(User::class)->findBy(['role'=>User::ROLES[$role]]);
        return $this->render('admin/user/user-list.html.twig', ['users' => $users]);
    }
}