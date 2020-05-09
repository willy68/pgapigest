<?php

namespace App\Demo\Controller;

use App\Auth\Models\User;
use Framework\Database\ActiveRecord\ActiveRecordQuery;
use Framework\Renderer\RendererInterface;
use Psr\Http\Message\ServerRequestInterface;
use Framework\Validator\Validation\ValidationRules;

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
        $validation = new ValidationRules('auteur', 'required|min:3|max:10|filter:trim');
        $validation->isValid('Willy ');
        $query = new ActiveRecordQuery();
        $query
            ->where('id = ?', 'user_id = ?')
            ->orWhere('created_at = now()')
            ->setWhereValue([2, 5, new \DateTime()]);
        /** @var \App\Auth\Models\User $user */
        $user = User::find_by_username(['username' => 'admin']);
        $user_array = $user->to_array();
        $mysql_ver = $pdo->getAttribute(\PDO::ATTR_SERVER_VERSION);
        $params = array_merge($request->getServerParams(), $user_array, [$mysql_ver]);
        return $renderer->render('@demo/index', compact('params'));
    }
}
