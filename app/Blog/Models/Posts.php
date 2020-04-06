<?php

namespace App\Blog\Models;

use ActiveRecord;
use Framework\Database\ActiveRecord\PaginatedActiveRecord;
use Pagerfanta\Pagerfanta;

class Posts extends ActiveRecord\Model
{
    public static $connection = 'blog';

    public static $table_name = 'posts';

    public static $belongs_to = [
        [
            'categories',
            'class_name' => 'Categories',
            'foreign_key' => 'category_id'
        ]
    ];
 
    /**
     * Undocumented function
     *
     * @param int $perPage
     * @param int $currentPage
     * @return Pagerfanta
     */
    public static function paginate(int $perPage, int $currentPage = 1): Pagerfanta
    {
        $paginator = new PaginatedActiveRecord(Posts::class);
        return (new Pagerfanta($paginator))
            ->setMaxPerPage($perPage)
            ->setCurrentPage($currentPage);
    }
}
