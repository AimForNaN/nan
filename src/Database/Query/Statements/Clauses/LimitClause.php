<?php

namespace NaN\Database\Query\Statements\Clauses;

use NaN\Database\Query\Statements\Clauses\Interfaces\ClauseInterface;

class LimitClause implements ClauseInterface {
	public function __construct(
		public readonly int $limit = 1,
		public readonly int $offset = 0,
	) {
	}

	public function getBindings(): array {
		return [];
	}

	public function render(bool $prepared = false): string {
		$ret = 'LIMIT ' . $this->limit;

		if ($this->offset) {
			$ret .= ', ' . $this->offset;
		}

		return $ret;
	}
}
