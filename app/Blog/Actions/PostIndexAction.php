<?php

namespace App\Blog\Actions;

use App\Blog\Models\Categories;
use App\Blog\Models\Posts;
use App\Blog\Table\CategoryTable;
use App\Blog\Table\PostTable;
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
     * Undocumented function
     *
     * @param RendererInterface $renderer
     */
    public function __construct(
        RendererInterface $renderer,
        PostTable $postTable,
        CategoryTable $categoryTable
    ) {
        $this->renderer = $renderer;
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
        $params = $request->getQueryParams();
        $posts = Posts::paginate(12, $params['p'] ?? 1);
        $categories = Categories::find('all');

        return $this->renderer->render('@blog/index', compact('posts', 'categories'));
    }
}
