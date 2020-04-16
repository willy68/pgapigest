<?php

namespace Framework\Validator\Validation;

use Framework\Validator\ValidationInterface;
use Psr\Http\Message\ServerRequestInterface;

class EmailConfirmValidation implements ValidationInterface
{
	protected $error = 'Le champ %s doit être un E-mail identique avec le champ %s';

	protected $fieldName;

	protected $params = [];

	public function __construct(
		ServerRequestInterface $request,
		string $fieldName = null,
		string $errormsg = null
	) {
		if ($errormsg !== null) {
			$this->error = $errormsg;
		}
		$this->params = $request->getParsedBody();
		$this->setFieldName($fieldName);
	}

	public function isValid($var): bool
	{
		if ($this->checkField($var)) {
			return parent::isValid($var);
		} else {
			return false;
		}
	}

	public function parseParams($param): self
	{
		if (is_string($param)) {
			list($fieldName, $message) = array_pad(explode(',', $param), 2, '');
			if (!empty($message))
				$this->error = $message;
			if (!empty($fieldName))
				$this->setFieldName($fieldName);
		}
		return $this;
	}

	public function getParams(): array
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

	protected function checkField(string $var): bool
	{
		if (is_string($var)) {
			if (isset($this->params[$this->fieldName])) {
				return $this->params[$this->fieldName] === $var;
			}
		}
		return false;
	}

	public function setFieldName(string $fieldName): self
	{
		if (is_string($fieldName)) {
			$this->fieldName = $fieldName;
		}
		return $this;
	}
}
