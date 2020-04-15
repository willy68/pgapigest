<?php

namespace Framework\Validator\Validation;

use Framework\Validator\ValidationInterface;
  
class EmailConfirmValidation implements ValidationInterface
{
	protected $error;

	protected $fieldName;

	protected $form;

    public function __construct($fieldName = null, $errormsg = null)
    {
		if ($errormsg === null) {
			$this->error = 'Le champ %1$s doit être un E-mail identique avec le champ %2$s';
		}
    	$this->setFieldName($fieldName);
    }
	
    public function isValid($var)
    {
    	if ($this->checkField($var)) {
    		return parent::isValid($var);
    	}
    	else {
    		return false;
    	}
    }

	public function parseParams($param) {
		if (is_string($param)) {
			list($fieldName, $message) = array_pad(explode(',', $param), 2, '');
			if (!empty($message))
				$this->error = $message;
			if (!empty($fieldName))
				$this->setFieldName($fieldName);
		}
		return $this;
	}

	public function getParams()
	{
		return [$this->fieldName];
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

    protected function checkField($var)
    {
    	if (is_string($var))
    	{
			if (isset($_POST[$this->fieldName])) {
				return $_POST[$this->fieldName] === $var;
			}
        }
        return false;
    }

	public function setFieldName($fieldName)
	{
		if (is_string($fieldName))
			$this->fieldName = $fieldName;
	}
    
}
