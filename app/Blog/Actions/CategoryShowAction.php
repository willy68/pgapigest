<?php

namespace App\Blog\Actions;

use App\Blog\Models\Categories;
use App\Blog\Table\CategoryTable;
use App\Blog\Table\PostTable;
use Framework\Actions\RouterAwareAction;
use Framework\Renderer\RendererInterface;
use Framework\Router;
use Psr\Http\Message\ServerRequestInterface as Request;

/**
 * Undocumented class
 */
class CategoryShowAction
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
        // $category = $this->categoryTable->findBy('slug', $request->getAttribute('slug'));
        $category = Categories::find_by_slug($request->getAttribute('slug'));
        $params = $request->getQueryParams();
        $posts = $this->postTable->findPublicForCategory($category->id)->paginate(12, $params['p'] ?? 1);
        // $categories = $this->categoryTable->findAll();
        $categories = Categories::find('all');
        $page = $params['p'] ?? 1;

        // for PHPRenderer
        // $this->renderer->addGlobal('router', $this->router);

        return $this->renderer->render('@blog/index', compact('posts', 'categories', 'category', 'page'));
    }
}
