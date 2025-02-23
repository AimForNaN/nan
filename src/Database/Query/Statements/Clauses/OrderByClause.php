<?php

namespace NaN\Database\Query\Statements\Clauses;

use NaN\Database\Query\Statements\Clauses\Interfaces\ClauseInterface;

class OrderByClause implements ClauseInterface {
	public function __construct(private array $columns) {
	}

	public function getBindings(): array {
		return [];
	}

	public function render(bool $prepared = false): string {
		$columns = [];

		foreach ($this->columns as $column => $order) {
			$columns[] = "$column $order";
		}

		return 'ORDER BY ' . \implode(', ', $columns);
	}
}
