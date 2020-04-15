<?php

namespace Framework\Validator\Validation;

use Framework\Validator\ValidationInterface;

class RequiredValidation implements ValidationInterface
{
    protected $error;

    public function __construct($error =  'Le champ %s est obligatoire')
    {
        $this->error = $error;
    }

    public function isValid($var)
    {
        return $this->is_set($var);
    }

	public function parseParams($param) {
		return $this;
	}

	public function getParams()
	{
		return [];
	}

	/**
	 * 
	 *
	 * @return string
	 */
	public function getError(): string
	{
		return $this->error;
	}

    /**
     *
     * @check if POST variable is set
     *
     * @access protected
     *
     * @param string $var The POST variable to check
     * @return bool
     */
    protected function is_set($var)
    {
        $check = true;
        if (!isset($var))
            $check = false;
        else if (is_array($var))
            $check = !empty($var);
        else if (is_string($var)) // une chaine constituée que d'espaces est considérée comme vide!
            $check = (bool) strlen(trim($var));
        else
            $check = !empty($var); // autre type de variable

        return $check;
    }
}
