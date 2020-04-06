<?php
namespace App\Blog\Actions;

use App\Blog\Table\PostTable;
use Framework\Actions\RouterAwareAction;
use Framework\Renderer\RendererInterface;
use Framework\Router;
use Psr\Http\Message\ServerRequestInterface as Request;

/**
 * Undocumented class
 */
class BlogAction
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
   * Undocumented function
   *
   * @param RendererInterface $renderer
   */
  public function __construct(RendererInterface $renderer, PostTable $postTable, Router $router)
  {
    $this->renderer = $renderer;
    $this->postTable = $postTable;
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
    if ($request->getAttribute('id')) {
      return $this->show($request);
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
    $posts = $this->postTable->findPaginated(12, $params['p'] ?? 1);

    // for PHPRenderer
    // $this->renderer->addGlobal('router', $this->router);

    return $this->renderer->render('@blog/index', compact('posts'));
  }

  /**
   * Undocumented function
   *
   * @param ServerRequestInterface $request
   * @return ResponseInterface|string
   */
  public function show(Request $request)
  {
    $slug = $request->getAttribute('slug');
    $post = $this->postTable->find($request->getAttribute('id'));

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
