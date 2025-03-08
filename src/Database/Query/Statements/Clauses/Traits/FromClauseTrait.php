<?php

namespace NaN\Database\Query\Statements\Clauses\Traits;

use NaN\Database\Query\Statements\Clauses\FromClause;

trait FromClauseTrait {
	public function from(\Closure|string $table, string $database = ''): static {
		if (empty($table)) {
			\trigger_error('From clause: Table name cannot be empty!', E_USER_ERROR);
		}

		$from_clause = new FromClause();

		if ($table instanceof \Closure) {
			$table($from_clause);
		} else {
			$from_clause->addTable($table, $database);
		}

		return $this->setFrom($from_clause);
	}
}
