<?php

namespace App\Api\Client\Adresse_type;

use App\Models\Adresse_type;
use App\Api\AbstractApiController;

class Adresse_typeController extends AbstractApiController
{

    /**
     * Model class
     *
     * @var string
     */
    protected $model = Adresse_type::class;

    /**
     * Default to 'entreprise_id'
     * @var string
     */
    protected $foreignKey = '';
}
