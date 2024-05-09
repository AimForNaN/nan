<?php

namespace NaN\Database\Query\Statements\Clauses;

class WhereClause extends \NaN\Collections\Collection implements ClauseInterface {
	/**
	 * @todo
	 */
	public function render(bool $prepared = false): string {
		return 'WHERE';
	}
}
