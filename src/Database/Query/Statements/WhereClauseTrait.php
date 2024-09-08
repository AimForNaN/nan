<?php

namespace NaN\Database\Query\Statements;

use NaN\Database\Query\Statements\Clauses\WhereClause;

trait WhereClauseTrait {
	public function where(callable $fn): static {
		$where_clause = new WhereClause();
		$fn($where_clause);
		return $this->setWhere($where_clause);
	}

	public function whereEquals(string $column, mixed $value): static {
		$where_clause = new WhereClause();
		$where_clause->addColumn(null, $column, '=', $value);
		return $this->setWhere($where_clause);
	}

	public function whereGreaterThan(string $column, mixed $value): static {
		$where_clause = new WhereClause();
		$where_clause->addColumn(null, $column, '>', $value);
		return $this->setWhere($where_clause);
	}

	public function whereGreaterThanEquals(string $column, mixed $value): static {
		$where_clause = new WhereClause();
		$where_clause->addColumn(null, $column, '>=', $value);
		return $this->setWhere($where_clause);
	}

	public function whereIn(string $column, array $value): static {
		$where_clause = new WhereClause();
		$where_clause->addColumn(null, $column, 'IN', $value);
		return $this->setWhere($where_clause);
	}

	public function whereLessThan(string $column, mixed $value): static {
		$where_clause = new WhereClause();
		$where_clause->addColumn(null, $column, '<', $value);
		return $this->setWhere($where_clause);
	}

	public function whereLessThanEquals(string $column, mixed $value): static {
		$where_clause = new WhereClause();
		$where_clause->addColumn(null, $column, '<=', $value);
		return $this->setWhere($where_clause);
	}
}
