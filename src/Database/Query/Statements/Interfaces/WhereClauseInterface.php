<?php

namespace NaN\Database\Query\Statements\Interfaces;

use NaN\Database\Query\Statements\Clauses\WhereClause;

interface WhereClauseInterface {
	public function setWhere(WhereClause $where_clause): static;

	public function where(\Closure $fn): static;
}
