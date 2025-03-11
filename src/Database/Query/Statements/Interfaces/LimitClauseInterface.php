<?php

namespace NaN\Database\Query\Statements\Interfaces;

use NaN\Database\Query\Statements\Clauses\LimitClause;

interface LimitClauseInterface {
	public function limit(int $limit = 1, int $offset = 0): static;

	public function setLimit(LimitClause $limit_clause): static;
}
