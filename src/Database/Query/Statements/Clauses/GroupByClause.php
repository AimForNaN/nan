<?php

namespace NaN\Database\Query\Statements\Clauses;

class GroupByClause extends \NaN\Collections\Collection implements ClauseInterface {
	public function getBindings(): array {
		return [];
	}

	public function render(bool $prepared = false): string {
		return 'GROUP BY ' . $this->implode(', ');
	}
}
