<?php

namespace app\models;

use app\src\Application;
use app\src\Model;

class LoginForm extends Model
{
	public string $email = '';
	public string $password = '';

}