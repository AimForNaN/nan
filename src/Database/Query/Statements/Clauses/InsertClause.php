<?php

namespace NaN\Database\Query\Statements\Clauses;

use NaN\Database\Query\Statements\Clauses\ClauseInterface;

class InsertClause implements ClauseInterface {
	public function __construct(private string $table, private ?string $database = null) {
	}

	public function getBindings(): array {
		return [];
	}

	public function render(bool $prepared = false): string {
		$table = $this->table;

		if ($this->database) {
			$table .= '.' . $this->database;
		}

		return 'INSERT INTO ' . $table;
	}

	public function toUpdate(): UpdateClause {
		return new UpdateClause($this->table, $this->database);
	}
}
