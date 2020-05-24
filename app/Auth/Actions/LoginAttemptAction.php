<?php

namespace App\Auth\Actions;

use Framework\Actions\RouterAwareAction;
use Framework\Auth\RememberMe\AuthCookieSession;
use Framework\Auth\RememberMe\RememberMeInterface;
use Framework\Renderer\RendererInterface;
use Framework\Response\ResponseRedirect;
use Framework\Router;
use Framework\Session\FlashService;
use Framework\Session\SessionInterface;
use Psr\Http\Message\ServerRequestInterface;

class LoginAttemptAction
{
    use RouterAwareAction;

    /**
     * Undocumented variable
     *
     * @var RendererInterface
     */
    private $renderer;

    /**
     * Undocumented variable
     *
     * @var AuthCookieSession
     */
    private $auth;

    /**
     * Undocumented variable
     *
     * @var SessionInterface
     */
    private $session;

    /**
     * Undocumented variable
     *
     * @var Router
     */
    private $router;

    public function __construct(
        RendererInterface $renderer,
        AuthCookieSession $auth,
        SessionInterface $session,
        Router $router
    ) {
        $this->renderer = $renderer;
        $this->auth = $auth;
        $this->session = $session;
        $this->router = $router;
    }

    public function __invoke(ServerRequestInterface $request)
    {
        $params = $request->getParsedBody();
        $user = $this->auth->login($params['username'], $params['password']);
        if ($user) {
            $path = $this->session->get('auth.redirect')  ?: $this->router->generateUri('admin');
            $this->session->delete('auth.redirect');
            $response = new ResponseRedirect($path);
            if ($params['rememberMe']) {
                $response = $this->auth->onLogin($response, $user->getUsername(), $user->getPassword(), 'secret');
            }
            return $response;
        } else {
            (new FlashService($this->session))->error('Identifiant ou mot de passe incorrect');
            return $this->redirect('auth.login');
        }
    }
}
