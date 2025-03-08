<?php

namespace NaN\Database\Query\Statements;

use NaN\Database\Query\Statements\Clauses\{
	InsertClause,
	InsertValuesClause,
	WhereClause,
};
use NaN\Database\Query\Statements\Clauses\Traits\WhereClauseTrait;
use NaN\Database\Query\Statements\Traits\StatementTrait;

class Push implements Interfaces\PushInterface {
	use StatementTrait;
	use WhereClauseTrait;

	public function __invoke(...$args): static {
		return $this->push(...$args);
	}

	public function into(string $table, string $database = ''): static {
		$this->query[0] = new InsertClause($table, $database);
		return $this;
	}

	public function push(iterable $columns): static {
		$this->query[1] = new InsertValuesClause($columns);
		return $this;
	}

	public function setWhere(WhereClause $where_clause): static {
		$this->query[2] = $where_clause;
		return $this;
	}

	public function validate(): bool {
		if (\count($this->query) === 0) {
			return false;
		}

		if (!\is_a($this->query[0], InsertClause::class)) {
			return false;
		}

		if (!\is_a($this->query[1], InsertValuesClause::class)) {
			return false;
		}

		return true;
	}
}
