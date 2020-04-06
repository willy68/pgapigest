<?php

namespace App\Blog\Actions;

use App\Blog\Entity\Post;
use App\Blog\Table\PostTable;
use Framework\Actions\RouterAwareAction;
use Framework\Renderer\RendererInterface;
use Framework\Router;
use Framework\Session\FlashService;
use Framework\Session\SessionInterface;
use Framework\Validator;
use Psr\Http\Message\ServerRequestInterface as Request;

class AdminBlogAction
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
     * @var \Framework\Router
     */
    private $router;

    /**
     * Undocumented variable
     *
     * @var FlashService
     */
    private $flash;

    /**
     * Undocumented function
     *
     * @param RendererInterface $renderer
     */
    public function __construct(
        RendererInterface $renderer,
        PostTable $postTable,
        Router $router,
        FlashService $flash
    ) {
        $this->renderer = $renderer;
        $this->postTable = $postTable;
        $this->router = $router;
        $this->flash = $flash;
    }

    /**
     * Undocumented function
     *
     * @param Request $request
     * @return void
     */
    public function __invoke(Request $request)
    {
        if ($request->getMethod() === 'DELETE') {
            return $this->delete($request);
        }
        if (substr($request->getUri(), -3) === 'new') {
            return $this->create($request);
        }
        if ($request->getAttribute('id')) {
            return $this->edit($request);
        }
        return $this->index($request);
    }

    /**
     * Undocumented function
     *
     * @return string
     */
    public function index(Request $request): string
    {
        $params = $request->getQueryParams();
        $items = $this->postTable->findPaginated(12, $params['p'] ?? 1);

        // for PHPRenderer
        // $this->renderer->addGlobal('router', $this->router);

        return $this->renderer->render('@blog/admin/index', compact('items'));
    }

    /**
     * Edite un article
     *
     * @param ServerRequestInterface $request
     * @return ResponseInterface|string
     */
    public function edit(Request $request)
    {
        $item = $this->postTable->find($request->getAttribute('id'));

        if ($request->getMethod() === 'POST') {
            $params = $this->getParams($request);
            $validator = $this->getValidator($request);
            if ($validator->isValid()) {
                $this->postTable->update($item->id, $params);
                $this->flash->success('L\'article a bien été modifié');
                return $this->redirect('blog.admin.index');
            }
            $params['id'] = $item->id;
            $item = $params;
            $errors = $validator->getErrors();
        }
        return $this->renderer->render('@blog/admin/edit', compact('item', 'errors'));
    }

    /**
     * Crée un article
     *
     * @param Request $request
     * @return ResponseInterface|string
     */
    public function create(Request $request)
    {
        if ($request->getMethod() === 'POST') {
            $params = $this->getParams($request);
            $validator = $this->getValidator($request);
            if ($validator->isValid()) {
                $this->postTable->insert($params);
                $this->flash->success('L\'article a bien été créé');
                return $this->redirect('blog.admin.index');
            }
            $errors = $validator->getErrors();
        }

        $item = new Post();
        $item->created_at = new \DateTime();
        
        return $this->renderer->render('@blog/admin/create', compact('item', 'errors'));
    }

    /**
     * Undocumented function
     *
     * @param Request $request
     * @return ResponseInterface|string
     */
    public function delete(Request $request)
    {
        $this->postTable->delete($request->getAttribute('id'));
        return $this->redirect('blog.admin.index');
    }

    /**
     * Undocumented function
     *
     * @param Request $request
     * @return array
     */
    private function getParams(Request $request): array
    {
        $params = array_filter($request->getParsedBody(), function ($key) {
            return in_array($key, ['name', 'slug', 'content', 'created_at']);
        }, ARRAY_FILTER_USE_KEY);
        return array_merge($params, [
            'updated_at' => date('Y-m-d H:i:s')
        ]);
    }

    private function getValidator(Request $request)
    {
        return (new Validator($request->getParsedBody()))
            ->required('name', 'slug', 'content', 'created_at')
            ->length('content', 2)
            ->length('name', 2, 250)
            ->length('slug', 2, 250)
            ->slug('slug')
            ->dateTime('created_at');
    }
}
