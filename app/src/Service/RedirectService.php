<?php

namespace App\Service;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\RouterInterface;

class RedirectService extends AbstractController
{
    const MESSAGE_TYPE = [
        'fail' => 'fail',
        'success' => 'success'
    ];

    const MESSAGE_TEXT = [
        'USER_EDIT_DENIED' => 'You cant edit this user',
        'CREATE_USER_WITH_NO_STRUCT' => 'You cant create new users without your own structure',
        'ACCESS_DENIED' => 'Access denied',
        'STRUCT_EDIT_DENIED' => 'You cant edit this struct',
        'NEW_STRUCT' => 'Congratulation now you have your own struct :)',
        'WRONG_ROLE_NAME' => 'Wrong role name',
    ];
    private $router;

    public function __construct(RouterInterface $router)
    {
        $this->router = $router;
    }

    public function redirectWithPopup(string $messageType, string $message, string $route, array $routeParams)
    {
        $this->addFlash($messageType, $message);
        $url = $this->router->generate($route, $routeParams);
        return new RedirectResponse($this->router->generate('index'));
    }


}