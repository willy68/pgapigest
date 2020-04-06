<?php

namespace App\Blog\Actions;

use Framework\Router;
use App\Blog\Table\CategoryTable;
use Framework\Actions\CrudAction;
use Framework\Session\FlashService;
use Framework\Renderer\RendererInterface;
use Psr\Http\Message\ServerRequestInterface as Request;

class CategoryCrudAction extends CrudAction
{

    protected $viewPath = '@blog/admin/categories';

    protected $routePrefix = 'blog.admin.category';

    public function __construct(
        RendererInterface $renderer,
        CategoryTable $table,
        Router $router,
        FlashService $flash
    ) {
        parent::__construct($renderer, $table, $router, $flash);
    }

    /**
     * Undocumented function
     *
     * @param Request $request
     * @return array
     */
    protected function getParams(Request $request, $item): array
    {
        return array_filter($request->getParsedBody(), function ($key) {
            return in_array($key, ['name', 'slug']);
        }, ARRAY_FILTER_USE_KEY);
    }

    /**
     * Undocumented function
     *
     * @param Request $request
     * @return Validator
     */
    protected function getValidator(Request $request)
    {
        return parent::getValidator($request)
            ->required('name', 'slug')
            ->length('name', 2, 250)
            ->length('slug', 2, 250)
            ->unique('slug', $this->table->getTable(), $this->table->getPdo(), $request->getAttribute('id'))
            ->slug('slug');
    }
}
