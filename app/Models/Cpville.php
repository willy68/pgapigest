<?php

namespace App\Models;

use ActiveRecord;

class Cpville extends ActiveRecord\Model 
{
	static $connection = 'ajax';
	static $table_name = 'cp_autocomplete';
	static $primary_key = 'CP';
}
