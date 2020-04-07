<?php

namespace App\Blog\Actions;

use App\Blog\Models\Categories;
use App\Blog\Models\Posts;
use Framework\Actions\RouterAwareAction;
use Framework\Renderer\RendererInterface;
use Psr\Http\Message\ServerRequestInterface as Request;

/**
 * Undocumented class
 */
class PostIndexAction
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
     * Undocumented function
     *
     * @param RendererInterface $renderer
     */
    public function __construct(
        RendererInterface $renderer
    ) {
        $this->renderer = $renderer;
    }

    /**
     * Undocumented function
     *
     * @param Request $request
     * @return void
     */
    public function __invoke(Request $request)
    {
        $params = $request->getQueryParams();
        // Init Query
        $posts = Posts::setPaginatedQuery(Posts::findPublic())
                ::paginate(12, $params['p'] ?? 1);
        $categories = Categories::find('all');

        return $this->renderer->render('@blog/index', compact('posts', 'categories'));
    }
}
