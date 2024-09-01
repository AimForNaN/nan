<?php

namespace NaN\Database\Query\Statements\Clauses;

class InsertValuesClause implements ClauseInterface {
	private array $columns = [];
	private array $values = [];

	public function __construct(array $columns) {
		foreach ($columns as $key => $val) {
			$this->columns[] = $key;
			$this->values[] = $val;
		}
	}

	public function getBindings(): array {
		return $this->values;
	}

	public function render(bool $prepared = false): string {
		return '(' . \implode(',', $this->columns) . ') VALUES (' . $this->renderValues($prepared) . ')';
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

		foreach ($this->values as $value) {
			$args[] = $prepared ? '?' : $this->renderValue($value);
		}

		return \implode(', ', $args);
	}

	public function toUpdateValues(): UpdateValuesClause {
		return new UpdateValuesClause(\array_combine($this->columns, $this->values));
	}
}
