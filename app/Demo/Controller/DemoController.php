<?php

namespace App\Demo\Controller;

use App\Models\User;
use Framework\Renderer\RendererInterface;
use Psr\Http\Message\ServerRequestInterface;

class DemoController
{

    /**
     * Montre l'index de l'application
     * $renderer est injecté automatiquement, comme toutes les classes
     * renseignées dans config/config.php
     * Il est possible d'injecter la ServerRequestInterface
     * mais doit s'appeler Obligatoirement $request.
     * Ce type d'injection est possible avec \DI\Container de PHP-DI
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \Framework\Renderer\RendererInterface $renderer
     * @param \PDO $pdo
     * @return string
     */
    public function index(
        ServerRequestInterface $request,
        RendererInterface $renderer,
        \PDO $pdo
    ): string {
        /** @var \User $user */
        $user = User::find_by_email(['email' => 'william.lety@gmail.com']);
        $user_array = $user->to_array();
        $mysql_ver = $pdo->getAttribute(\PDO::ATTR_SERVER_VERSION);
        $params = array_merge($request->getServerParams(), $user_array, [$mysql_ver]);
        return $renderer->render('@demo/index', compact('params'));
    }
}
