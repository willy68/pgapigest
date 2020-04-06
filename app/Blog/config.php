<?php

use App\Blog\BlogWidget;
use App\Blog\BlogAdminWidget;

return [
    'blog.prefix' => '/blog',
    'admin.widgets' => \DI\add([
       \DI\get(BlogAdminWidget::class)
    ]),
    'blog.widgets' => \DI\add([
        \DI\get(BlogWidget::class)
     ])
];
