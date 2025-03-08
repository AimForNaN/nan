<?php

namespace NaN\Database\Query\Builders;

use NaN\Database\Query\Builders\Interfaces\BuilderInterface;

class Builder implements BuilderInterface {
	public function patch(callable $fn): mixed {
	}

	public function pull(callable $dfn): mixed {
	}

	public function purge(callable $dfn): mixed {
	}

	public function push(callable $fn): mixed {
	}
}
