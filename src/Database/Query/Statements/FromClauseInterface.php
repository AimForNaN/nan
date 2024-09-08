<?php

namespace NaN\Database\Query\Statements;

use NaN\Database\Query\Statements\Clauses\FromClause;

interface FromClauseInterface {
	public function from(\Closure|string $table, string $database = ''): static;

	public function setFrom(FromClause $from_clause): static;
}
