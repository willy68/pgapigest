<?php

namespace App\Blog\Models;

use Framework\Database\Query;

class Categories extends BlogModel
{
    public static $connection = 'blog';

    public static $table_name = 'categories';

    public static $has_many = [['posts', 'class_name' => 'Posts']];

    /**
     * Init options conditions for all Post
     *
     * @return Query
     */
    public static function findAll(): Query
    {
        return static::makeQuery()->order('id DESC');
    }
}
