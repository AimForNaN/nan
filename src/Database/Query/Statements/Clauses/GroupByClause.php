<?php

namespace NaN\Database\Query\Statements\Clauses;

use NaN\Database\Query\Statements\Interfaces\StatementInterface;
use NaN\Database\Query\Statements\Traits\StatementTrait;

class GroupByClause implements StatementInterface {
	use StatementTrait;

	public function __construct(array $columns = []) {
		$this->data = $columns;
	}

	public function getBindings(): array {
		return [];
	}

	public function render(bool $prepared = false): string {
		return 'GROUP BY ' . \implode(', ', $this->data);
	}
}
