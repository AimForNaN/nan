<?php

namespace NaN\Database\Interfaces;

use NaN\Database\Query\Builders\Interfaces\QueryBuilderInterface;

interface EntityInterface {
	/**
	 * @return DatabaseInterface Database connection interface.
	 */
	static public function database(): QueryBuilderInterface;

	public function fill(iterable $data): void;

	/**
	 * Aliases for database field names.
	 *
	 * @return array Database column mappings.
	 */
	static public function mappings(): array;

	static public function patch(callable $fn): mixed;

	static public function pull(callable $fn): mixed;

	static public function purge(callable $fn): mixed;

	static public function push(callable $fn): mixed;
}