<?php

namespace NaN\App\Router;

class Routes extends \NaN\Collections\TypedCollection {
	public function __construct(
		protected array $data = [],
	) {
		parent::__construct($data, Route::class);
	}

	/**
	 * Get routes by method.
	 *
	 * @param string $method HTTP method.
	 *
	 * @return \Traversable
	 */
	public function getByMethod(string $method): \Traversable {
		return $this->filter(function (Route $route) use ($method) {
			return $route->method === $method;
		});
	}
}
