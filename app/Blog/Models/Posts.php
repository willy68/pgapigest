<?php

namespace App\Blog\Models;

use ActiveRecord;
use Framework\Database\ActiveRecord\PaginatedActiveRecord;
use Framework\Database\Query;
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
 
    public static $paginatedQuery;

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
            'conditions' => static::$paginatedQuery['conditions'],
            'order' => static::$paginatedQuery['order']
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
        static::$paginatedQuery['limit'] = $length;
        static::$paginatedQuery['offset'] = $offset;
        return static::find('all', static::$paginatedQuery);
    }

    public static function setPaginatedQuery(Query $query)
    {
        static::$paginatedQuery = [
            'conditions' => [$query->getWhere()],
            'order' => $query->getOrder(),
            'limit' => 12,
            'offset' => 1,
            'include' => ['category']
        ];
    }

    public static function findPublicForCategory(int $category_id)
    {
        static::setPaginatedQuery((new Query())
            ->where('published = 1 AND created_at < NOW()')
            ->where("category_id = $category_id")
            ->order('created_at DESC')
        );
    }

    public static function findPublic()
    {
        static::setPaginatedQuery((new Query())
            ->where('published = 1 AND created_at < NOW()')
            ->order('created_at DESC')
        );
    }
}
