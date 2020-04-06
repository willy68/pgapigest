<?php

namespace App\Blog\Actions;

use App\Blog\Table\CategoryTable;
use App\Blog\Table\PostTable;
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
     * @var PostTable
     */
    private $postTable;

    /**
     * Undocumented variable
     *
     * @var CategoryTable
     */
    private $categoryTable;

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
        Router $router,
        PostTable $postTable,
        CategoryTable $categoryTable
    ) {
        $this->renderer = $renderer;
        $this->router = $router;
        $this->postTable = $postTable;
        $this->categoryTable = $categoryTable;
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
        $post = $this->postTable->findWithCategory($request->getAttribute('id'));

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
