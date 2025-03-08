<?php

namespace NaN\Database\Query\Builders\Interfaces;

interface QueryBuilderInterface {
	public function patch(callable $fn): mixed;

	public function pull(callable $fn): mixed;

	public function purge(callable $fn): mixed;

	public function push(callable $fn): mixed;
}
