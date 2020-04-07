<?php

namespace App\Admin;

use Framework\Renderer\RendererInterface;

class DashboardAction
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
     * @var AdminWidgetInterface[]
     */
    private $widgets;

    public function __construct(RendererInterface $renderer, array $widgets)
    {
        
        $this->renderer = $renderer;
        $this->widgets = $widgets;
    }
/*
    public function __invoke()
    {
        $widgets = array_reduce($this->widgets, function ($html, AdminWidgetInterface $widget) {
            return $html . $widget->render();
        }, '');
        return $this->renderer->render('@admin/dashboard', compact('widgets'));
    }
*/
    public function index()
    {
        $widgets = array_reduce($this->widgets, function ($html, AdminWidgetInterface $widget) {
            return $html . $widget->render();
        }, '');
        return $this->renderer->render('@admin/dashboard', compact('widgets'));
    }
}
