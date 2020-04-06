<?php

namespace Framework\Database\ActiveRecord;

use Pagerfanta\Adapter\AdapterInterface;

class PaginatedActiveRecord implements AdapterInterface
{
    protected $model;

    public function __construct(string $model)
    {
        $this->model = $model;
    }

    /**
     * Undocumented function
     *
     * @return int
     */
    public function getNbResults(): int
    {
        return $this->model::count([
            'conditions' => ['published = 1', 'created_at < NOW()'],
            'order' => 'created_at DESC'
            ]
        );
    }

    /**
     * Undocumented function
     *
     * @param mixed $offset
     * @param mixed $length
     * @return \ActiveRecord\Model[]
     */
    public function getSlice($offset, $length)
    {
        $posts = $this->model::find('all', [
                'conditions' => ['published = ? AND created_at < ?', 1, 'NOW()'],
                'order' => 'created_at DESC',
                'limit' => $length,
                'offset' => $offset,
                'include' => ['categories']
            ]
        );
        // var_dump($posts); die();
        /*if (empty($posts)) {
            return [];
        }*/
        return $posts;
    }
}
