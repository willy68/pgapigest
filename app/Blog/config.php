<?php

use App\Blog\BlogModule;
use App\Blog\BlogWidget;
use App\Blog\BlogAdminWidget;
use App\Blog\BlogTwigExtension;

return [
    'blog.prefix' => '/blog',
    'admin.widgets' => \DI\add([
       \DI\get(BlogAdminWidget::class)
    ]),
    'blog.widgets' => \DI\add([
        \DI\get(BlogWidget::class)
     ]),
     BlogModule::class => \DI\autowire()
       ->constructorParameter('prefix', \DI\get('blog.prefix')),
     BlogTwigExtension::class => \DI\create()->constructor(\DI\get('blog.widgets')),
];
