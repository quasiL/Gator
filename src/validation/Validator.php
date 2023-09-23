<?php

namespace app\src\validation;

use app\src\Application;
use app\src\DB;

class Validator
{
	public array $errors = [];

	public const RULE_REQUIRED = 'required';
	public const RULE_EMAIL = 'email';
	public const RULE_MIN = 'min';
	public const RULE_MAX = 'max';
	public const RULE_MATCH = 'match';
	public const RULE_UNIQUE = 'unique';
	public function validate($rulesArray, $model)
	{
		foreach ($rulesArray as $attribute => $rules) {
			$value = $model->{$attribute};
			foreach ($rules as $rule) {
				$ruleName = $rule;
				if (!is_string($ruleName)) {
					$ruleName = $rule[0];
				}
				if ($ruleName === self::RULE_REQUIRED && !$value) {
					$this->addErrorForRule($attribute, self::RULE_REQUIRED);
				}
				if ($ruleName === self::RULE_EMAIL && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
					$this->addErrorForRule($attribute, self::RULE_EMAIL);
				}
				if ($ruleName === self::RULE_MIN && strlen($value) < $rule['min']) {
					$this->addErrorForRule($attribute, self::RULE_MIN, $rule);
				}
				if ($ruleName === self::RULE_MAX && strlen($value) > $rule['max']) {
					$this->addErrorForRule($attribute, self::RULE_MAX, $rule);
				}
				if ($ruleName === self::RULE_MATCH && $value !== $model->{$rule['match']}) {
					$this->addErrorForRule($attribute, self::RULE_MATCH, $rule);
				}
				if ($ruleName === self::RULE_UNIQUE) {
					$uniqueAttr = $rule['attribute'] ?? $attribute;
					$tableName = $model->tableName();

					$record = DB::table($tableName)
						->select()
						->where($uniqueAttr, '=', $value)
						->getFirst();
					if ($record) {
						$this->addErrorForRule($attribute, self::RULE_UNIQUE);
					}
				}
			}
		}
		return empty($this->errors);
	}

	public function addErrorForRule(string $attribute, string $rule, array $params = []): void
	{
		$message = $this->errorMessages()[$rule] ?? '';
		foreach ($params as $key => $value) {
			$message = str_replace("{{$key}}", $value, $message);
		}
		$this->errors[$attribute][] = $message;
		var_dump($this->errors);
	}

	private function errorMessages(): array
	{
		return [
			self::RULE_REQUIRED => 'This field is required',
			self::RULE_EMAIL => 'This field must be valid email address',
			self::RULE_MIN => 'Min length of this field must be {min}',
			self::RULE_MAX => 'Max length of this field must be {max}',
			self::RULE_MATCH => 'This field must be the same as {match}',
			self::RULE_UNIQUE => 'Record with this already exists',
		];
	}

	public function hasError(string $attribute)
	{
		return $this->errors[$attribute] ?? false;
	}
}