<?php

namespace App\Blog;

use Framework\Module;
use Framework\Router;
use App\Blog\Actions\PostShowAction;
use Framework\Renderer\TwigRenderer;
use App\Blog\Actions\PostIndexAction;
use Psr\Container\ContainerInterface;
use App\Blog\Actions\CategoryShowAction;
use Framework\Renderer\RendererInterface;

/**
 * Undocumented class
 */
class BlogModule extends Module
{

  /**
   *
   */
    public const DEFINITIONS = __DIR__ . '/config.php';

    public const MIGRATIONS = __DIR__ . '/db/migrations';

    public const SEEDS = __DIR__ . '/db/seeds';

  /**
   * Undocumented function
   *
   * @param string $prefix
   * @param Router $router
   * @param RendererInterface $renderer
   */
    public function __construct(ContainerInterface $c)
    {
        $renderer = $c->get(RendererInterface::class);
        $renderer->addPath('blog', __DIR__ . '/views');
        if ($renderer instanceof TwigRenderer) {
            $renderer->getTwig()->addExtension($c->get(BlogTwigExtension::class));
        }
        $prefix = $c->get('blog.prefix');
        /** @var \Framework\Router */
        $router = $c->get(Router::class);
        $router->get($prefix, PostIndexAction::class, 'blog.index');
        $router->get($prefix . '/{slug:[a-z\-0-9]+}-{id:[0-9]+}', PostShowAction::class, 'blog.show');
        $router->get($prefix . '/category/{slug:[a-z\-0-9]+}', CategoryShowAction::class, 'blog.category');
    }
}
