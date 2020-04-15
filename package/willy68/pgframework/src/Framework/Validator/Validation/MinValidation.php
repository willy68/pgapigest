<?php

namespace Framework\Validator\Validation;

use Framework\Validator\ValidationInterface;

class MinValidation implements ValidationInterface
{

	protected $min;

	protected string $error = 'Le champ %s doit avoir minimum %d caractères';

	public function __construct($min = 1, string $error = null)
	{
		$this->setMin($min);
		if (!is_null($error)) {
			$this->error = $error;
		}
	}

	/**
	 * 
	 *
	 * @param string $param
	 * @return self
	 */
	public function parseParams(string $param): self
	{
		if (is_string($param)) {
			list($min, $message) = array_pad(explode(',', $param), 2, '');
			if (!empty($message))
				$this->error = $message;
			if (!empty($min))
				$this->setMin($min);
		}
		return $this;
	}

	/**
	 * 
	 *
	 * @return array
	 */
	public function getParams(): array
	{
		return [$this->min];
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
	 *
	 * @param mixed $var
	 * @return bool
	 */
	public function isValid($var): bool
	{
		if (is_numeric($var))
			return $this->checkNumeric($var);
		else if (is_string($var))
			return $this->checkString($var);
		else if (is_int($var))
			return $this->checkInt($var);
		else if (is_float($var))
			return $this->checkFloat($var);
	}

	/**
	 * 
	 *
	 * @param int $min
	 * @return self
	 */
	public function setMin($min = 1): self
	{
		$this->min = $this->get_numeric($min);
		//lancer une exception si min === null;
		if ($this->min === null || $this->min < 0) {
			throw new \InvalidArgumentException(
				'Argument invalide, $min doit être de type numeric plus grand ou égal a 0 ex: 2 ou \'2\''
			);
		}
		return $this;
	}

	/**
	 * 
	 *
	 * @param string $var
	 * @return bool
	 */
	protected function checkString(string $var): bool
	{
		$check = true;
		if (strlen($var) < $this->min)
			$check = false;

		return $check;
	}

	/**
	 * 
	 *
	 * @param int $var
	 * @return bool
	 */
	protected function checkInt(int $var): bool
	{
		$check = true;
		if ($var < $this->min)
			$check = false;

		return $check;
	}

	/**
	 * 
	 *
	 * @param float $var
	 * @return bool
	 */
	protected function checkFloat(float $var): bool
	{
		$check = true;
		if ($var < $this->min)
			$check = false;

		return $check;
	}

	/**
	 * 
	 *
	 * @param mixed $var
	 * @return bool
	 */
	protected function checkNumeric($var): bool
	{
		$check = true;

		if ($val = $this->get_numeric($var) !== null) {
			if ($val < $this->min)
				$check = false;
		}
		return $check;
	}

	/**
	 * 
	 *
	 * @param mixed $val
	 * @return mixed
	 */
	protected function get_numeric($val)
	{
		if (is_numeric($val)) {
			return $val + 0;
		}
		return null;
	}
}
