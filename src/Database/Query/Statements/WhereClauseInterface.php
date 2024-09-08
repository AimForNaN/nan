<?php

namespace NaN\Database\Query\Statements;

use NaN\Database\Query\Statements\Clauses\WhereClause;

interface WhereClauseInterface {
	public function setWhere(WhereClause $where_clause): static;

	public function where(callable $fn): static;

	public function whereEquals(string $column, mixed $value): static;

	public function whereGreaterThan(string $column, mixed $value): static;

	public function whereGreaterThanEquals(string $column, mixed $value): static;

	public function whereIn(string $column, array $value): static;

	public function whereLessThan(string $column, mixed $value): static;

	public function whereLessThanEquals(string $column, mixed $value): static;
}
