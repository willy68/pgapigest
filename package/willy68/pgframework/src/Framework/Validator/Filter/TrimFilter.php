<?php

namespace Framework\Validator\Filter;

use Framework\Validator\FilterInterface;

class TrimFilter implements FilterInterface
{
    /**
     * return $var after filter if is set or just $var without filter
     *
     * @param mixed $var
     * @return void
     */
    public function filter($var)
    {
    	if(!empty($var)) {
    		return trim($var);
    	}
    	else return $var;
    }
}
