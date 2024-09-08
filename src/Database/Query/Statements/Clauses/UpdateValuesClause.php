<?php

namespace NaN\Database\Query\Statements\Clauses;

class UpdateValuesClause extends InsertValuesClause {
	public function render(bool $prepared = false): string {
		return 'SET ' . static::renderValues($this->columns, $prepared);
	}

	static public function renderValues(iterable $values, bool $prepared = false): string {
		$args = [];

		foreach ($values as $column => $value) {
			$args[] = "$column = " . ($prepared ? '?' : static::renderValue($value));
		}

		return \implode(', ', $args);
	}
}
