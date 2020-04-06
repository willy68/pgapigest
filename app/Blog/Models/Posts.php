<?php

namespace App\Blog\Models;

use ActiveRecord;

class Posts extends ActiveRecord\Model
{
    public static $connection = 'blog';
    public static $table_name = 'posts';
}
