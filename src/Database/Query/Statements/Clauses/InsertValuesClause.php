<?php

namespace NaN\Database\Query\Statements\Clauses;

use NaN\Database\Query\Statements\Interfaces\StatementInterface;
use NaN\Database\Query\Statements\Traits\StatementTrait;

class InsertValuesClause implements StatementInterface, \Countable {
	use StatementTrait;

	public function __construct(
		array $columns,
	) {
		$this->data = $columns;
	}

	public function count(): int {
		return \count($this->data);
	}

	public function getBindings(): array {
		return \array_values($this->data);
	}

	static public function generatePlaceHolders(int $count): string {
		return \implode(', ', \array_fill(0, $count, '?'));
	}

	public function render(bool $prepared = false): string {
		$columns = [];
		$values = [];

		foreach ($this->data as $column => $value) {
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

	static public function renderValues(array $values, bool $prepared = false): string {
		$args = [];

		foreach ($values as $value) {
			$args[] = $prepared ? '?' : static::renderValue($value);
		}

		return \implode(', ', $args);
	}

	public function toUpdateValues(): UpdateValuesClause {
		return new UpdateValuesClause($this->data);
	}
}
