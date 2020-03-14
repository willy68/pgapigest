<?php

namespace App\Models;

use ActiveRecord;
  
class Adresse extends ActiveRecord\Model {
  static $table_name = 'adresse';
  static $belongs_to = [['client', 'class_name' => 'Client']];
}

