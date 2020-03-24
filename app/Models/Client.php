<?php

namespace App\Models;

use ActiveRecord;
  
class Client extends ActiveRecord\Model
{
    static $table_name = 'client';
    static $has_many = [['adresses', 'class_name' => 'Adresse']];
}
