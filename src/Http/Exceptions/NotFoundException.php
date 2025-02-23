<?php

namespace NaN\Http\Exceptions;

class NotFoundException extends \Exception {
	public function __construct() {
		parent::__construct('Not Found', 404);
	}
}
