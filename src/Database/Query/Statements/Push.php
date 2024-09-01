<?php

namespace NaN\Database\Query\Statements;

use NaN\Database\Query\Statements\Clauses\{
	InsertClause,
	InsertValuesClause,
	WhereClause,
};

class Push implements PushInterface {
	use StatementTrait;

	public function into(string $table, string $database = null): PushInterface {
		$this->query[0] = new InsertClause($table, $database);
		return $this;
	}

	public function push(array $columns): PushInterface {
		$this->query[1] = new InsertValuesClause($columns);
		return $this;
	}

	public function setWhere(WhereClause $where): PushInterface {
		if ($this->query[0] instanceof InsertClause) {
			$this->query[0] = $this->query[0]->toUpdate();
		}

		if ($this->query[1] instanceof InsertValuesClause) {
			$this->query[1] = $this->query[1]->toUpdateValues();
		}

		$this->query[2] = $where;
		return $this;
	}

	public function where(callable $fn): PushInterface {
		$where_clause = new WhereClause();
		$fn($where_clause);
		return $this->setWhere($where_clause);
	}

	public function whereEquals(string $column, mixed $value): PushInterface {
		$where_clause = new WhereClause();
		$where_clause->addColumn(null, $column, '=', $value);
		return $this->setWhere($where_clause);
	}

	public function whereGreaterThan(string $column, mixed $value): PushInterface {
		$where_clause = new WhereClause();
		$where_clause->addColumn(null, $column, '>', $value);
		return $this->setWhere($where_clause);
	}

	public function whereGreaterThanEquals(string $column, mixed $value): PushInterface {
		$where_clause = new WhereClause();
		$where_clause->addColumn(null, $column, '>=', $value);
		return $this->setWhere($where_clause);
	}

	public function whereIn(string $column, array $value): PushInterface {
		$where_clause = new WhereClause();
		$where_clause->addColumn(null, $column, 'IN', $value);
		return $this->setWhere($where_clause);
	}

	public function whereLessThan(string $column, mixed $value): PushInterface {
		$where_clause = new WhereClause();
		$where_clause->addColumn(null, $column, '<', $value);
		return $this->setWhere($where_clause);
	}

	public function whereLessThanEquals(string $column, mixed $value): PushInterface {
		$where_clause = new WhereClause();
		$where_clause->addColumn(null, $column, '<=', $value);
		return $this->setWhere($where_clause);
	}
}
