<?php

namespace app\src\exception;

class NotFoundException extends \Exception
{
	protected $code = 404;
	protected $message = 'Not Found';
}