<?php

namespace App\Blog\Models;

use ActiveRecord;

class Categories extends ActiveRecord\Model
{
    public static $connection = 'blog';

    public static $table_name = 'categories';

    public static $has_many = [['posts', 'class_name' => 'Posts']];
}
