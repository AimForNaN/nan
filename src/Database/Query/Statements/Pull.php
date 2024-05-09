<?php

namespace NaN\Database\Query\Statements;

use NaN\Database\Query\Statements\{
	Clauses\FromClause,
	Clauses\SelectClause,
};
use NaN\Database\Query\Statements\Clauses\WhereClause;

class Pull implements PullInterface {
	private bool $distinct = false;
	private FromClause $from_clause;
	private SelectClause $select_clause;
	private WhereClause $where_clause;

	public function distinct(): PullInterface {
		$this->distinct = true;
		return $this;
	}

	public function from(callable $fn): PullInterface {
		$fn($this->table_references = new FromClause());
		return $this;
	}

	/**
	 * @todo
	 */
	public function getBindings(): array {
		return [];
	}

	public function render(bool $prepared = false): string {
		// Force select and from to be defined!
		return \implode(' ', \array_filter([
			$this->select_clause->render($prepared, $this->distinct),
			$this->from_clause->render($prepared),
			$this->where_clause?->render($prepared),
		]));
	}

	public function select(callable $fn): PullInterface {
		$fn($this->select_clause = new SelectClause());
		return $this;
	}

	public function where(callable $fn): PullInterface {
		$fn($this->where_clause = new WhereClause());
		return $this;
	}
}
