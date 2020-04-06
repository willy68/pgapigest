<?php

namespace App\Blog;

use App\Admin\AdminWidgetInterface;
use App\Blog\Table\PostTable;
use Framework\Renderer\RendererInterface;

class BlogWidget implements AdminWidgetInterface
{

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

    public function __construct(RendererInterface $renderer, PostTable $postTable)
    {
        $this->renderer = $renderer;
        $this->postTable = $postTable;
    }

    public function render(): string
    {
        $count = $this->postTable->count();
        return $this->renderer->render('@blog/admin/widget', compact('count'));
    }

    public function renderMenu(): string
    {
        return $this->renderer->render('@blog/menu');
    }
}
