<?php

namespace App\Api\Dernier_code;

use App\Models\Dernier_code;
use GuzzleHttp\Psr7\Response;
use App\Api\AbstractApiController;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class Dernier_codeController extends AbstractApiController
{
    /**
     * Model class
     *
     * @var string
     */
    protected $model = Dernier_code::class;

    public function forTable(ServerRequestInterface $request): ResponseInterface
    {
        $options = [];
        $attributes = $request->getAttributes();
        if (!isset($attributes['table_nom'])) {
            return new Response(400);
        }
        if (isset($attributes[$this->foreignKey])) {
          $options['conditions'] = [
            $this->foreignKey . ' = ? AND table_nom = ?',
            $attributes[$this->foreignKey], $attributes['table_nom']
          ];
        } else {
          $options['conditions'] = ['table_nom = ?', $request->getAttribute('table_nom')];
        }
        try {
          $dernier_code = $this->model::last($options);
        } catch (\ActiveRecord\ActiveRecordException $e) {
            return new Response(400);
        }
        if (empty($dernier_code)) {
            return new Response(404);
        }
        $json = $dernier_code->to_json();
        return new Response(200, [], $json);

    }
}
