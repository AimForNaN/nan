<?php

namespace NaN\Database\Query\Statements;

use NaN\Database\Query\Statements\Clauses\FromClause;

trait FromClauseTrait {
	public function from(\Closure|string $table, string $database = ''): static {
		$from_clause = new FromClause();

		if ($table instanceof \Closure) {
			$query = new static();
			$from_clause->addSubQuery($query);
			$table($query);
		} else {
			$from_clause->addTable($table, $database);
		}

		return $this->setFrom($from_clause);
	}
}
