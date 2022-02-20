<?php

namespace App\Controller\Struct;

use App\Entity\Struct;
use App\Form\StructEditType;
use App\Service\EditableService;
use App\Service\RedirectService;
use App\Repository\UserRepository;
use App\Repository\StructRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\NotificationRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ShowStructController extends AbstractController
{
    public function __invoke(UserRepository $userRepository, EditableService $editableService, NotificationRepository $notificationRepository, StructRepository $structRepository, Request $request)
    {
        $id = $request->attributes->get('id');
        $struct = $structRepository->find($id);
        if (!$struct) {
            return $this->render('admin/single/404.html.twig');
        }

        $promotionRequests = [];
        if(!empty($this->getUser())){
            $promotionRequests = $notificationRepository->getPromotionRequestsToUser($this->getUser());
        }
        $editable = $editableService->checkStruct($struct, $this->getUser());

        return $this->render('admin/struct/struct.html.twig', [
            'editable' => $editable,
            'struct' => $struct,
            'promotionRequests' => $promotionRequests
        ]);
    }
}
