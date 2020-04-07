<?php

namespace App\Blog\Models;

use ActiveRecord;
use Pagerfanta\Pagerfanta;
use Framework\Database\Query;
use Framework\Database\ActiveRecord\PaginatedActiveRecord;

class Categories extends ActiveRecord\Model
{
    public static $connection = 'blog';

    public static $table_name = 'categories';

    public static $has_many = [['posts', 'class_name' => 'Posts']];
 
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
        $paginator = new PaginatedActiveRecord(Categories::class);
        return (new Pagerfanta($paginator))
            ->setMaxPerPage($perPage)
            ->setCurrentPage($currentPage);
    }

    /**
     * Undocumented function
     *
     * @return int
     */
    public static function getNbResults(): int
    {
        $options = null;
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
        return __CLASS__;
    }

    /**
     * Init options conditions for all Post
     *
     * @return Query
     */
    public static function findAll(): Query
    {
        return static::makeQuery()->order('id DESC');
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
