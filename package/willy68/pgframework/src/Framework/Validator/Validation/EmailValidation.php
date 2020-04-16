<?php

namespace Framework\Validator\Validation;

use Framework\Validator\ValidationInterface;

class EmailValidation implements ValidationInterface
{
	protected $error = 'Le champ %s doit être une adresse E-mail valide';

	public function __construct($error = null)
	{
        if (!is_null($error)) {
            $this->error = $error;
        }
	}

	public function isValid($var): bool
	{
		return $this->checkEmail($var);
	}

	/**
	 * 
	 *
	 * @param string $param
	 * @return self
	 */
	public function parseParams(string $param): self
	{
		return $this;
	}

	/**
	 * 
	 *
	 * @return array
	 */
	public function getParams(): array
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

	protected function checkEmail($var): bool
	{
		if (is_string($var)) {
			if (filter_var($var, FILTER_VALIDATE_EMAIL) !== false) {
				return true;
			}
		}
		return false;
	}
}
