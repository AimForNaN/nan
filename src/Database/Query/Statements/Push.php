<?php

namespace NaN\Database\Query\Statements;

use NaN\Database\Query\Statements\Clauses\{
	InsertClause,
	InsertValuesClause,
    WhereClause,
};

class Push implements PushInterface {
	use StatementTrait;
	use WhereClauseTrait;

	public function into(string $table, string $database = ''): static {
		$this->query[0] = new InsertClause($table, $database);
		return $this;
	}

	public function push(iterable $columns): static {
		$this->query[1] = new InsertValuesClause($columns);
		return $this;
	}

	public function setWhere(WhereClause $where): static {
		$this->query[2] = $where;
		return $this;
	}
}
