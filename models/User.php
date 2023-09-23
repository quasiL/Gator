<?php

namespace app\models;

use app\src\Model;

class User extends Model
{
	const STATUS_INACTIVE = 0;
	const STATUS_ACTIVE = 1;
	const STATUS_DELETED = 2;

	public string $firstname = '';
	public string $lastname = '';
	public string $email = '';
	public int $status = self::STATUS_INACTIVE;
	public string $password = '';
	public string $confirmPassword = '';

	public function save(): void
	{
		$this->status = self::STATUS_ACTIVE;
		$this->password = password_hash($this->password, PASSWORD_DEFAULT);
		parent::save();
	}

	public function attributes(): array
	{
		return ['email', 'firstname', 'lastname', 'password', 'status'];
	}

	public static function primaryKey(): string
	{
		return 'id';
	}
}