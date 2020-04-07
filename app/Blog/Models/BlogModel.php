<?php

namespace App\Blog\Models;

use ActiveRecord;
use App\Blog\Models\Posts;
use Pagerfanta\Pagerfanta;
use Framework\Database\Query;
use Framework\Database\ActiveRecord\PaginatedActiveRecord;

class BlogModel extends ActiveRecord\Model
{
 
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
        $paginator = new PaginatedActiveRecord(static::class);
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
        return static::class;
    }

    /**
     * Init options conditions for all Post
     *
     * @return Query
     */
    public static function findAll(): Query
    {
        return static::makeQuery();
    }

    /**
     *
     *
     * @return array
     */
    public static function findList(string $field): array
    {
        $list = [];
        $results = static::find('all', ['select' => $field]);
        foreach ($results as $result) {
            $list[$result->id] = $result->name;
        }
        return $list;
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
