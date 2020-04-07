<?php

namespace App\Blog\Models;

use Framework\Database\Query;

class Posts extends BlogModel
{
    public static $connection = 'blog';

    public static $table_name = 'posts';

    public static $belongs_to = [
        [
            'category',
            'class_name' => 'Categories',
            'foreign_key' => 'category_id'
        ]
    ];

    /**
     * Undocumented function
     *
     * @return string
     */
    public function getThumb(): string
    {
        ['filename' => $filename, 'extension' => $extension] = pathinfo($this->image);
        return '/uploads/posts/' . $filename . '_thumb.' . $extension;
    }

    /**
     * Undocumented function
     *
     * @return string
     */
    public function getImageUrl(): string
    {
        return '/uploads/posts/' . $this->image;
    }

    public function getCategory()
    {
        return $this->category ?: null;
    }

    /**
     * Init options conditions for all Posts by Categories
     *
     * @param int $category_id
     * @return Query
     */
    public static function findPublicForCategory(int $category_id): Query
    {
        return static::findPublic()->where("category_id = $category_id");
    }

    /**
     * Init options conditions for all published Posts
     *
     * @return Query
     */
    public static function findPublic(): Query
    {
        return static::findAll()->where('published = 1 AND created_at < NOW()');
    }

    /**
     * Init options conditions for all Post
     *
     * @return Query
     */
    public static function findAll(): Query
    {
        return static::makeQuery()->order('created_at DESC');
    }
}
