<?php

namespace NaN\Database\Query\Statements\Clauses;

class SelectClause extends \NaN\Collections\Collection implements ClauseInterface {
	private bool $distinct = false;

	public function __invoke(array $columns) {
		$this->addColumns($columns);
	}

	public function addColumn(string $column, ?string $alias = null): static {
		$this->data[] = [
			'expr' => 'column',
			'alias' => $alias,
			'column' => $column,
		];
		return $this;
	}

	public function addColumns(array $columns): static {
		foreach ($columns as $alias => $column) {
			if (!\is_numeric($alias)) {
				$this->addColumn($column, $alias);
			} else {
				$this->addColumn($column);
			}
		}
		return $this;
	}

	public function addAllColumns(): static {
		return $this->addColumn('*');
	}

	public function distinct(): static {
		$this->distinct = true;
		return $this;
	}

	public function getBindings(): array {
		return [];
	}

	public function offsetGet(mixed $offset): mixed {
		throw new \BadMethodCallException('Getting value through array accessor is not supported!');
	}

	public function offsetSet(mixed $offset, mixed $value): void {
		throw new \BadMethodCallException('Setting value through array accessor is not supported!');
	}

	public function render(bool $prepared = false): string {
		return 'SELECT ' . ($this->distinct ? 'DISTINCT ' : '') . \implode(', ', $this->map(function ($item) {
			\extract($item);

			switch ($expr) {
				case 'column':
					if ($alias) {
						return "$column AS $alias";
					}
			}

			return $column;
		}));
	}
}
