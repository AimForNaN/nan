<?php

namespace NaN\Database\Query\Statements\Clauses;

use NaN\Database\Query\Statements\Clauses\Interfaces\ClauseInterface;

class InsertClause implements ClauseInterface {
	public function getBindings(): array {
		return [];
	}

	public function render(bool $prepared = false): string {
		return 'INSERT';
	}
}
