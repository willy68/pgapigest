<?php

namespace App\Models;

use ActiveRecord;
  
class Entreprise extends ActiveRecord\Model
{
    static $table_name = 'entreprise';
    static $has_many = array(
    array('administrateurs', 'class_name' => 'Administrateur'),
    array('users', 'class_name' => 'User', 'through' => 'administrateurs')
    );
}
