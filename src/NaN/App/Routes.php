<?php

namespace NaN\App;

class Routes extends \NaN\TypedCollection {
	public function __construct() {
		parent::__construct(Route::class);
	}

	public function offsetSet(mixed $offset, mixed $value): void {
		$this->assertType($value);
		$method = \strtoupper($value->method);
		$this->data[$method][] = $value;
	}
}
