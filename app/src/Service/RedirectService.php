<?php

namespace App\Service;

use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class RedirectService extends AbstractController
{
    const MESSAGE_TYPE = [
        'fail' => 'fail',
        'success' => 'success'
    ];

    const MESSAGE_TEXT = [
        'GAME_EDITED' => 'Гра успішно відредагована',
        'APPROVE_GAME' => 'Гра схвалена',
        'CREATE_GAME_SUCCESS' => 'Дякуємо за додану гру, наразі гра проходить модерацію',
        'USER_DELETE_DENIED' => 'You can`t delete this user',
        'USER_REGISTERED' => 'User by this link already registered',
        'USER_SELF_REMOVE' => 'You can`t remove yourself from struct',
        'INVITE_DENIED' => 'You can`t invite users',
        'USER_EDIT_DENIED' => 'You can`t edit this user',
        'CREATE_USER_WITH_NO_STRUCT' => 'You can`t create new users without your own structure',
        'ACCESS_DENIED' => 'Access denied',
        'STRUCT_EDIT_DENIED' => 'You can`t edit this struct',
        'NEW_STRUCT' => 'Congratulation now you have your own struct :)',
        'WRONG_ROLE_NAME' => 'Wrong role name',
        'SENDED_INVITE' => 'Invite was successfuly sended',
        'INVITE_ALREADY_SENDED' => 'Invite to user already sended, check the mailbox',
        'USER_EXIST' => 'User with this email already exist',
        'SOMETHING_WENT_WRONG' => 'Ops, something went wrong',
        'CANT_CREATE_USER' => 'You cant create new user for this struct',
        'LOGOUT_TO_CONTINUE' => 'If you want to register new email, firstly yo need to logout',
        'JOIN_REQUEST_SUCCESS' => 'Join request was successfully sended',
        'ALREADY_SENDED_REQUEST' => 'you already send request to this structure'
    ];
    private $router;

    public function __construct(RouterInterface $router)
    {
        $this->router = $router;
    }

    public function redirectWithPopup(string $messageType, string $message, string $route, array $routeParams = [])
    {
        $this->addFlash($messageType, $message);
        $url = $this->router->generate($route, $routeParams);
        return new RedirectResponse($url);
    }


}