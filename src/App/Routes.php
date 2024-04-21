<?php

namespace NaN\App;

class Routes extends \NaN\Collections\TypedCollection {
	public function __construct(
		protected array $data = [],
	) {
		parent::__construct($data, Route::class);
	}

	/**
	 * Get routes by method.
	 *
	 * @param string $offset HTTP method.
	 *
	 * @return \Iterator
	 */
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
