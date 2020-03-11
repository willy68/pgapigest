<?php

namespace App\Admin\Controller;

use Framework\Renderer\RendererInterface;
use Psr\Http\Message\ServerRequestInterface;

class AdminController
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
     * @return string
     */
    public function index(ServerRequestInterface $request, RendererInterface $renderer): string
    {
        $params = $request->getServerParams();
        return $renderer->render('@admin/index', compact('params'));
    }
}
