<?php

namespace NaN\Database\Query\Statements;

use NaN\Database\Query\Statements\Clauses\{
	InsertClause,
	InsertIntoClause,
	InsertValuesClause,
	WhereClause,
};
use NaN\Database\Query\Statements\Clauses\Traits\WhereClauseTrait;
use NaN\Database\Query\Statements\Traits\StatementTrait;

class Push implements Interfaces\PushInterface {
	use StatementTrait;
	use WhereClauseTrait;

	public function __construct(array $columns = []) {
		$this->query[0] = new InsertClause();
		$this->push($columns);
	}

	public function __invoke(...$args): static {
		return $this->push(...$args);
	}

	public function into(string $table, string $database = ''): static {
		return $this->setInsertInto(new InsertIntoClause($table, $database));
	}

	public function push(array $columns): static {
		return $this->setInsertValues(new InsertValuesClause($columns));
	}

	public function setInsertInto(InsertIntoClause $into): static {
		$this->query[1] = $into;
		return $this;
	}

	public function setInsertValues(InsertValuesClause $values): static {
		$this->query[2] = $values;
		return $this;
	}

	public function setWhere(WhereClause $where_clause): static {
		$this->query[3] = $where_clause;
		return $this;
	}

	public function validate(): bool {
		if (\count($this->query) === 0) {
			return false;
		}

		if (!\is_a($this->query[0] ?? null, InsertClause::class)) {
			return false;
		}

		if (!\is_a($this->query[1] ?? null, InsertIntoClause::class)) {
			return false;
		}

		if (!\is_a($this->query[2] ?? null, InsertValuesClause::class)) {
			return \count($this->query[2]);
		}

		return true;
	}
}
