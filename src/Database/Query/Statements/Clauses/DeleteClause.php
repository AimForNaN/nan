<?php

namespace NaN\Database\Query\Statements\Clauses;

class DeleteClause implements ClauseInterface {
	public function getBindings(): array {
		return [];
	}

	public function render(bool $prepared = false): string {
		return 'DELETE';
	}
}
