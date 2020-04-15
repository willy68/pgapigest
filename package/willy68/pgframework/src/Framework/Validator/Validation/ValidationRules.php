<?php

namespace Framework\Validator\Validation;

use Framework\App;
use Framework\Validator\ValidationError;
use Framework\Validator\ValidationInterface;

/*									 
[
	'auteur' => 'required|max:50|min:3|filter:trim',
	'email' => 'required|email|filter:trim',
	'emailConfirm' => 'required|emailConfirm:email|filter:trim'
]
*/

class ValidationRules
{

	/**
	 * 
	 *
	 * @var ValidationError[]
	 */
	protected $errors = [];

	/**
	 * 
	 *
	 * @var ValidationInterface[]
	 */
	protected $validationRules = [];

	/**
	 * Filter rules
	 *
	 * @var array
	 */
	protected $filterRules = [];

	/**
	 * FieldName
	 *
	 * @var string
	 */
	protected $fieldName = '';

	public function __construct(string $fieldName, string $rules)
	{
		$this->setFieldName($fieldName);
		$this->setRules($rules);
	}

	/**
	 * Set fieldName
	 *
	 * @param string $fieldName
	 * @return self
	 */
	public function setFieldName(string $fieldName): self
	{
		if (is_string($fieldName) && !empty($fieldName)) {
			$this->fieldName = $fieldName;
		}
		return $this;
	}

	/**
	 * Parse string rules
	 *
	 * @param string $rules
	 * @return self
	 */
	public function setRules(string $rules): self
	{
		if (!is_string($rules) || empty($rules))
		{
			return $this;
		}

		$options = explode('|', $rules);
		
		foreach ($options as $option) {
			list($key, $value) = array_pad(explode(':', $option), 2, '');
            if (strtolower($key) === 'filter') {
				$this->filterRules[$value] = '';
			}
			else {
				$this->validationRules[$key] = $value;
			}
		}
		return $this;
	}

	/**
	 * Clean object
	 *
	 * @return self
	 */
	public function clean(): self
	{
		$this->fieldName = '';
		$this->validationRules = [];
		$this->filterRules = [];
		$this->errormsg = [];
		return $this;
	}

	/**
	 * 
	 *
	 * @param mixed $var
	 * @return bool
	 */
	public function isValid($var): bool
	{
		$valid = true;
		$formValidations = App::getApp()->getContainer()->get('form.validations');

		foreach ($this->filterRules as $key => $param) {
			$className = '\\Framework\\Validator\\Filter\\'.$key;
			$className = new $className();
			$var = $className->filter($var);
		}

		foreach ($this->validationRules as $key => $param)
		{
			if (array_key_exists($key, $formValidations)) {
				/** @var ValidationInterface $validation*/
				$validation = $formValidations[$key];
			}
			else {
				continue;
			}

			if (!empty($param))
			{
				$validation->parseParams((string) $param);
			}
			if (!$validation->isValid($var)) {
				$valid = false;
				$this->addError(
					$this->fieldName,
					strtolower($key),
					$validation->getParams(),
					$validation->getError()
				);
			}
		}	
		return $valid;
	}

    /**
     * Undocumented function
     *
     * @return ValidationError[]
     */
    public function getErrors(): array
    {
        return $this->errors;
	}
	
	public function getFieldName(): string
	{
		return $this->fieldName;
	}

    /**
     * Undocumented function
     *
     * @param string $key
     * @param string $rule
     * @param array $attributes
     * @return self
     */
    private function addError(string $key, string $rule, array $attributes = [], string $message = ''): self
    {
		$error = new ValidationError($key, $rule, $attributes);
		if (!empty($message)) {
			$error->addErrorMsg($rule, $message);
		}
		$this->errors[$key] = $error;
		return $this;
    }
}
