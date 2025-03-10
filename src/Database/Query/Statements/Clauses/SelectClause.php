<?php

namespace NaN\Database\Query\Statements\Clauses;

use NaN\Database\Query\Statements\Clauses\Interfaces\ClauseInterface;

class SelectClause extends \NaN\Collections\Collection implements ClauseInterface {
	public function __construct(
		array $columns = ['*'],
		private bool $distinct = false,
	) {
		parent::__construct();
		$this->setColumns($columns);
	}

	public function __invoke(array $columns) {
		$this->setColumns($columns);
	}

	protected function addColumn(string $column, string $alias = ''): static {
		$this->data[] = [
			'expr' => 'column',
			'alias' => $alias,
			'column' => $column,
		];

		return $this;
	}

	public function setColumns(array $columns): static {
		$this->data = [];

		foreach ($columns as $alias => $column) {
			if (!\is_numeric($alias)) {
				$this->addColumn($column, $alias);
			} else {
				$this->addColumn($column);
			}
		}

		return $this;
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
					if (!empty($alias)) {
						return "$column AS $alias";
					}
			}

			return $column;
		}));
	}
}
