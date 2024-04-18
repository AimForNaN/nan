<?php

namespace NaN\App;

class Routes extends \NaN\TypedCollection {
	public function __construct() {
		parent::__construct(Route::class);
	}

	public function offsetGet(mixed $offset): mixed {
		return new \CallbackFilterIterator(new \ArrayIterator($this->data), function (Route $route) use ($offset) {
			return $route->method === $offset;
		});
	}

	public function offsetSet(mixed $offset, mixed $value): void {
		$this->assertType($value);
		$this->data[] = $value;
	}
}
