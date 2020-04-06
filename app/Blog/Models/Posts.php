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
            'category',
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
     * Undocumented function
     *
     * @return int
     */
    public static function getNbResults(): int
    {
        return static::count([
            'conditions' => ['published = 1', 'created_at < NOW()'],
            'order' => 'created_at DESC'
            ]
        );
    }

    /**
     * Undocumented function
     *
     * @param int $offset
     * @param int $length
     * @return mixed
     */
    public static function paginatedQuery(int $offset, int $length)
    {
        return static::find('all', [
                'conditions' => ['published = ? AND created_at < NOW()', 1],
                'order' => 'created_at DESC',
                'limit' => $length,
                'offset' => $offset,
                'include' => ['category']
            ]
        );
    }
}
