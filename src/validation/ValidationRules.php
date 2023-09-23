<?php

namespace app\src\validation;

enum ValidationRules: string
{
	case REQUIRED = 'required';
	case EMAIL = 'email';
	case MIN = 'min';
	case MAX = 'max';
	case MATCH = 'match';
	case UNIQUE = 'unique';
}
