<?php

namespace Framework\Validator\Validation;

use Framework\Validator\ValidationInterface;

class DateFormatValidation implements ValidationInterface
{

	protected $format;

	protected $error = "Le champ %s doit être une date valide %s";

	public function __construct($format = 'Y-m-d H:i:s', string $error = null)
	{
		$this->setFormat($format);
		if (!is_null($error)) {
			$this->error = $error;
		}
	}

	public function isValid($var): bool
	{
		return $this->checkDate($var);
	}

	public function parseParams($param): self
	{
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

	public function getParams(): array
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

	public function setFormat($format): self
	{
		if (is_string($format))
			$this->format = $format;
		return $this;
	}

	protected function checkDate($var): bool
	{
		$datetime = \DateTime::createFromFormat($this->format, $var);
		$errors = \DateTime::getLastErrors();
		if (
			$errors['error_count'] > 0 ||
			$errors['warning_count'] > 0 ||
			$datetime === false
		) {
			return false;
		}
		return true;
	}
}
