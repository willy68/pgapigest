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

    public static $query;

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
        $options = [];
        if (!empty(static::$paginatedQuery['conditions'])) {
            $options['conditions'] = static::$paginatedQuery['conditions'];
        }
        return static::count($options);
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

    /**
     * set paginated options conditions
     *
     * @param \Framework\Database\Query $query
     * @return string Class name
     */
    public static function setPaginatedQuery(Query $query): string
    {
        static::$paginatedQuery = [];
        if (!empty($where = $query->getWhere())) {
            static::$paginatedQuery['conditions'] = [$where];
        }
        if (!empty($order = $query->getOrder())) {
            static::$paginatedQuery['order'] = $order;
        }
        static::$paginatedQuery['include'] = ['category'];
        return __CLASS__;
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

    /**
     * Init query
     *
     * @return \Framework\Database\Query
     */
    public static function makeQuery(): Query
    {
        if (!static::$query) {
            return static::$query = new Query();
        }
        return static::$query;
    }
}
