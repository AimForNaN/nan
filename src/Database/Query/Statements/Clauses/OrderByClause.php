<?php

namespace NaN\Database\Query\Statements\Clauses;

class OrderByClause implements ClauseInterface {
	private array $ascending = [];
	private array $descending = [];

	public function asc(string ...$columns): ClauseInterface {
		$this->ascending = $columns;
		return $this;
	}

	public function desc(string ...$columns): ClauseInterface {
		$this->descending = $columns;
		return $this;
	}

	public function getBindings(): array {
		return [];
	}

	public function render(bool $prepared = false): string {
		$columns = [];

		if (!empty($this->ascending)) {
			$columns[] = \implode(', ', $this->ascending) . ' ASC';
		}

		if (!empty($this->descending)) {
			$columns[] = \implode(', ', $this->descending) . ' DESC';
		}

		return 'ORDER BY ' . \implode(', ', $columns);
	}
}
