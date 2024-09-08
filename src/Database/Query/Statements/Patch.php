<?php

namespace NaN\Database\Query\Statements;

use NaN\Database\Query\Statements\Clauses\{
	LimitClause,
	UpdateClause,
	UpdateValuesClause,
    WhereClause,
};

class Patch implements PatchInterface {
	use LimitClauseTrait;
	use StatementTrait;
	use WhereClauseTrait;

	public function patch(string $table, string $database = ''): static {
		$this->query[0] = new UpdateClause($table, $database);
		return $this;
	}

	public function setLimit(LimitClause $limit): static {
		$this->query[3] = $limit;
		return $this;
	}

	public function setWhere(WhereClause $where_clause): static {
		$this->query[2] = $where_clause;
		return $this;
	}

	public function with(iterable $columns): static {
		$this->query[1] = new UpdateValuesClause($columns);
		return $this;
	}
}

