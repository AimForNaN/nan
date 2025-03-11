<?php

namespace NaN\Database\Query\Statements\Traits;

use NaN\Database\Query\Statements\Clauses\WhereClause;

trait WhereClauseTrait {
	public function where(\Closure $fn): static {
		$where_clause = new WhereClause();
		$fn($where_clause);
		return $this->setWhere($where_clause);
	}
}
