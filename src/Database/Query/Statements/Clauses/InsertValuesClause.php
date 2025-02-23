<?php

namespace NaN\Database\Query\Statements\Clauses;

use NaN\Database\Query\Statements\Clauses\Interfaces\ClauseInterface;

class InsertValuesClause implements ClauseInterface {
	public function __construct(protected iterable $columns) {
	}

	public function getBindings(): array {
		return \iterator_to_array($this->columns, false);
	}

	static public function generatePlaceHolders(int $count): string {
		return \implode(', ', \array_fill(0, $count, '?'));
	}

	public function render(bool $prepared = false): string {
		$columns = [];
		$values = [];

		foreach ($this->columns as $column => $value) {
			$columns[] = $column;
			$values[] = $value;
		}

		return '(' . \implode(',', $columns) . ') VALUES (' . static::renderValues($values, $prepared) . ')';
	}

	static public function renderValue(mixed $value): string {
		switch (gettype($value)) {
			case 'string':
				return '"' . $value . '"';
		}

		return (string)$value;
	}

	static public function renderValues(iterable $values, bool $prepared = false): string {
		$args = [];

		foreach ($values as $value) {
			$args[] = $prepared ? '?' : static::renderValue($value);
		}

		return \implode(', ', $args);
	}

	public function toUpdateValues(): UpdateValuesClause {
		return new UpdateValuesClause($this->columns);
	}
}
