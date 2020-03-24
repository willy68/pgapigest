<?php

namespace App\Models;

use ActiveRecord;
  
class User extends ActiveRecord\Model
{
    static $table_name = 'user';
    static $has_many = array(
    array('administrateurs', 'class_name' => 'Administrateur')
    );
}
