<?php

namespace NaN\Database\Query\Statements\Clauses;

class UpdateValuesClause implements ClauseInterface {
	public function __construct(private array $columns) {
	}

	public function getBindings(): array {
		return \array_values($this->columns);
	}

	public function render(bool $prepared = false): string {
		return 'SET (' . $this->renderValues($prepared) . ')';
	}

	public function renderValue(mixed $value): string {
		switch (gettype($value)) {
			case 'string':
				return '"' . $value . '"';
		}

		return (string)$value;
	}

	public function renderValues(bool $prepared = false): string {
		$args = [];

		foreach ($this->columns as $column => $value) {
			$args[] = "$column = " . ($prepared ? '?' : $this->renderValue($value));
		}

		return \implode(', ', $args);
	}
}
