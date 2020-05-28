<?php

namespace App\Auth\Actions;

use Framework\Auth\AuthSession;
use Framework\Session\FlashService;
use Framework\Response\ResponseRedirect;
use Framework\Renderer\RendererInterface;
use Psr\Http\Message\ServerRequestInterface;
use Framework\Auth\Service\AuthSessionCookie;

class LogoutAction
{

    /**
     * Undocumented variable
     *
     * @var RendererInterface
     */
    private $renderer;

    /**
     * Undocumented variable
     *
     * @var AuthSession
     */
    private $auth;

    /**
     * Undocumented variable
     *
     * @var FlashService
     */
    private $flashService;

    public function __construct(
        RendererInterface $renderer,
        AuthSession $auth,
        FlashService $flashService
    )
    {
        $this->renderer = $renderer;
        $this->auth = $auth;
        $this->flashService = $flashService;
    }

    public function __invoke(ServerRequestInterface $request)
    {
        $this->auth->logout();
        $this->flashService->success('Vous êtes maintenant déconnecté');
        return new ResponseRedirect('/blog');;
    }
}
