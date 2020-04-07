<?php

namespace App\Blog\Actions;

use Framework\Router;
use App\Blog\PostUpload;
use Framework\Validator;
use App\Blog\Entity\Post;
use App\Blog\Models\Posts;
use App\Blog\Table\PostTable;
use App\Blog\Models\Categories;
use App\Blog\Table\CategoryTable;
use Framework\Actions\CrudAction;
use Framework\Session\FlashService;
use Framework\Renderer\RendererInterface;
use Psr\Http\Message\ServerRequestInterface as Request;

class PostCrudAction extends CrudAction
{

    protected $viewPath = '@blog/admin/posts';

    protected $routePrefix = 'blog.admin';

    protected $model = Posts::class;

    protected $categoryTable;

    private $postUpload;

    public function __construct(
        RendererInterface $renderer,
        PostTable $table,
        Router $router,
        FlashService $flash,
        CategoryTable $categoryTable,
        PostUpload $postUpload
    ) {
        parent::__construct($renderer, $table, $router, $flash);
        $this->categoryTable = $categoryTable;
        $this->postUpload = $postUpload;
    }

    public function delete(Request $request)
    {
        $post = $this->table->find($request->getAttribute('id'));
        $this->postUpload->delete($post->image);
        
        return parent::delete($request);
    }

    /**
     * Undocumented function
     *
     * @param array $params
     * @return array
     */
    protected function formParams(array $params): array
    {
        // $params['categories'] = $this->categoryTable->findList();
        $params['categories'] = Categories::findList('id, name');
        return $params;
    }

    /**
     * Undocumented function
     *
     * @return Post
     */
    protected function getNewEntity()
    {
        $post = new Post();
        $post->created_at = new \DateTime();
        return $post;
    }

    /**
     * Undocumented function
     *
     * @param Request $request
     * @param mixed $item
     * @return array
     */
    protected function getParams(Request $request, $item): array
    {
        $params = array_merge($request->getParsedBody(), $request->getUploadedFiles());
        // Upload du fichier
        $image = $this->postUpload->upload($params['image'], $item->image);
        if ($image) {
            $params['image'] = $image;
        } else {
            unset($params['image']);
        }
         
        $params = array_filter($params, function ($key) {
            return in_array($key, ['name', 'slug', 'content', 'created_at', 'category_id', 'image', 'published']);
        }, ARRAY_FILTER_USE_KEY);
        return array_merge($params, [
            'updated_at' => date('Y-m-d H:i:s')
        ]);
    }

    /**
     * Undocumented function
     *
     * @param Request $request
     * @return Validator
     */
    protected function getValidator(Request $request): Validator
    {
        $validator = parent::getValidator($request)
            ->required('name', 'slug', 'content', 'created_at', 'category_id')
            ->length('content', 2)
            ->length('name', 2, 250)
            ->length('slug', 2, 100)
            ->exists(
                'category_id',
                $this->categoryTable->getTable(),
                $this->categoryTable->getPdo()
            )
            ->extension('image', ['jpg', 'png'])
            ->slug('slug')
            ->dateTime('created_at');
        if (is_null($request->getAttribute('id'))) {
            $validator->uploaded('image');
        }
        return $validator;
    }
}
