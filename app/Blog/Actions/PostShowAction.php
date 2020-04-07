<?php

namespace App\Blog\Actions;

use App\Blog\Models\Posts;
use Framework\Actions\RouterAwareAction;
use Framework\Renderer\RendererInterface;
use Framework\Router;
use Psr\Http\Message\ServerRequestInterface as Request;

/**
 * Undocumented class
 */
class PostShowAction
{
    /**
     *
     */
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
     * @var \Framework\Router
     */
    private $router;

    /**
     * Undocumented function
     *
     * @param RendererInterface $renderer
     */
    public function __construct(
        RendererInterface $renderer,
        Router $router
    ) {
        $this->renderer = $renderer;
        $this->router = $router;
    }

    /**
     * Undocumented function
     *
     * @param Request $request
     * @return void
     */
    public function __invoke(Request $request)
    {
        $slug = $request->getAttribute('slug');
        $post = Posts::find($request->getAttribute('id'), ['include' => ['category']]);

        if ($post->slug !== $slug) {
            return $this->redirect('blog.show', [
                'slug' => $post->slug,
                'id' => $post->id
            ]);
        }

        return $this->renderer->render('@blog/show', [
            'post' => $post
        ]);
    }
}
