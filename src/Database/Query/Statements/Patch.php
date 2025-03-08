<?php

namespace NaN\Database\Query\Statements;

use NaN\Database\Query\Statements\Clauses\{
	LimitClause,
	UpdateClause,
	UpdateValuesClause,
	WhereClause,
};
use NaN\Database\Query\Statements\Clauses\Traits\{
	LimitClauseTrait,
	WhereClauseTrait,
};
use NaN\Database\Query\Statements\Traits\StatementTrait;

class Patch implements Interfaces\PatchInterface {
	use LimitClauseTrait;
	use StatementTrait;
	use WhereClauseTrait;

	public function __invoke(...$args): static {
		return $this->patch(...$args);
	}

	public function patch(string $table, string $database = ''): static {
		$this->query[0] = new UpdateClause($table, $database);
		return $this;
	}

	public function setLimit(LimitClause $limit_clause): static {
		$this->query[3] = $limit_clause;
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

		if (!\is_a($this->query[0], UpdateClause::class)) {
			return false;
		}

		if (!\is_a($this->query[2], WhereClause::class)) {
			return false;
		}

		return true;
	}

	public function with(iterable $columns): static {
		$this->query[1] = new UpdateValuesClause($columns);
		return $this;
	}
}

