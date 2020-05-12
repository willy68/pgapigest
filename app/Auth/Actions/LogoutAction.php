<?php

namespace App\Auth\Actions;

use App\Auth\DatabaseAuth;
use Framework\Auth\Service\RememberMeAuthCookie;
use Framework\Session\FlashService;
use Framework\Response\ResponseRedirect;
use Framework\Renderer\RendererInterface;
use Psr\Http\Message\ServerRequestInterface;

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
     * @var DatabaseAuth
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
        DatabaseAuth $auth,
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
        $response = new ResponseRedirect('/blog');
        $cookies = $request->getCookieParams();
        if (!empty($cookies[RememberMeAuthCookie::COOKIE_NAME])) {
            $response = $this->auth->rememberMeLogout($response);
        }
        $this->flashService->success('Vous êtes maintenant déconnecté');
        return $response;
    }
}
