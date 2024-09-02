<?php

namespace NaN\Database\Query\Statements\Clauses;

class LimitClause implements ClauseInterface {
	public function __construct(
		public readonly int $limit,
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
