<?php

namespace NaN\Database\Query\Statements\Clauses;

use NaN\Database\Query\Statements\Clauses\Interfaces\ClauseInterface;

class UpdateClause implements ClauseInterface {
	public function __construct(
		private string $table,
		private string $database = '',
	) {
	}

	public function getBindings(): array {
		return [];
	}

	public function render(bool $prepared = false): string {
		$table = $this->table;

		if (!empty($this->database)) {
			$table .= '.' . $this->database;
		}

		return 'UPDATE ' . $table;
	}
}
