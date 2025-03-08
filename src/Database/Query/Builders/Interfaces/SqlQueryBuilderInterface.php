<?php

namespace NaN\Database\Query\Builders\Interfaces;

interface SqlQueryBuilderInterface extends QueryBuilderInterface {
	public function getLastInsertId(): string | false;

	public function raw(string $query, array $bindings = []): mixed;

	public function transact(callable $fn): bool;
}