<?php

namespace Framework\Validator\Validation;

use Framework\Validator\ValidationInterface;
  
class DateFormatValidation implements ValidationInterface
{

	protected $format;

	protected $error = 'Date invalide, format valide: %s';

    public function __construct($format = 'd/m/Y', string $error = null)
    {
    	$this->setFormat($format);
		if (!is_null($error)) {
			$this->error = $error;
		}
    }
    
    public function isValid($var)
    {
    	return $this->checkDate($var);
    }

	public function parseParams($param) {
		if (is_string($param)) {
			list($format, $message) = array_pad(explode(',', $param), 2, '');
			if (!empty($message)) {
				$this->error = $message;
			}
			if (!empty($format)) {
				$this->setFormat($format);
			}
		}
		return $this;
	}

	public function getParams()
	{
		return [$this->format];
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
    
	public function setFormat($format)
	{
		if (is_string($format))
			$this->format = $format;
	}

    protected function checkDate($var)
    {
    	$date = \DateTime::createFromFormat($this->format, $var);
	    if(!$date)
		    return false;

	    return true;
    }

}
