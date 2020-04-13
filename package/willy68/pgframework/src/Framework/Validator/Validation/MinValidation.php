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
	 * @param [type] $param
	 * @return self
	 */
	public function parseParam($param): self
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

	public function getParams(): array
	{
		return [$this->min];
	}

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

	public function getError(): string
	{
		return $this->error;
	}

	protected function checkString($var)
	{
		$check = true;
		if (strlen($var) < $this->min)
			$check = false;

		return $check;
	}

	protected function checkInt($var)
	{
		$check = true;
		if ($var < $this->min)
			$check = false;

		return $check;
	}

	protected function checkFloat($var)
	{
		$check = true;
		if ($var < $this->min)
			$check = false;

		return $check;
	}

	protected function checkNumeric($var)
	{
		$check = true;

		if ($val = $this->get_numeric($var) !== null) {
			if ($val < $this->min)
				$check = false;
		}
		return $check;
	}

	public function setMin($min = 1)
	{
		$this->min = $this->get_numeric($min);
		//lancer une exception si min === null;
		if ($this->min === null || $this->min < 0) {
			throw new \InvalidArgumentException(
				'Argument invalide, $min doit être de type numeric plus grand ou égal a 0 ex: 2 ou \'2\''
			);
		}
	}

	protected function get_numeric($val)
	{
		if (is_numeric($val)) {
			return $val + 0;
		}
		return null;
	}
}
