<?php

namespace NaN\Database\Query\Statements\Clauses;

class SelectClause extends \NaN\Collections\Collection implements ClauseInterface {
	public function addColumn(string $column, string $alias = null) {
		$this->offsetSet(null, [
			'expr' => 'column',
			'alias' => $alias,
			'column' => $column,
		]);
		return $this;
	}

	/**
	 * @todo
	 */
	public function render(bool $prepared = false, bool $distinct = false): string {
		return 'SELECT';
	}
}
