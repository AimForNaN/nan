<?php

namespace NaN\Database\Query\Statements;

use NaN\Database\Query\Statements\{
	Clauses\FromClause,
	Clauses\GroupByClause,
	Clauses\LimitClause,
	Clauses\OrderByClause,
	Clauses\SelectClause,
	Clauses\WhereClause,
};

class Pull implements PullInterface {
	private bool $distinct_modifier = false;
	private FromClause $from_clause;
	private GroupByClause $group_by_clause;
	private LimitClause $limit_clause;
	private OrderByClause $order_by_clause;
	private SelectClause $select_clause;
	private WhereClause $where_clause;

	public function distinct(): PullInterface {
		$this->distinct_modifier = true;
		return $this;
	}

	public function from(callable $fn): PullInterface {
		$fn($this->from_clause = new FromClause());
		return $this;
	}

	/**
	 * @todo
	 */
	public function getBindings(): array {
		return [
			...$this->from_clause->getBindings(),
			...($this->where_clause?->getBindings() ?? []),
		];
	}

	public function groupBy(string ...$columns): PullInterface {
		$this->group_by_clause = new GroupByClause(...$columns);
		return $this;
	}

	public function limit(int $limit, int $offset = 0): PullInterface {
		$this->limit_clause = new LimitClause($limit, $offset);
		return $this;
	}

	public function orderBy(callable $fn): PullInterface {
		$fn($this->order_by_clause = new OrderByClause());
		return $this;
	}

	public function render(bool $prepared = false): string {
		// Force select and from to be defined!
		return \implode(' ', \array_filter([
			$this->select_clause->render($prepared, $this->distinct_modifier),
			$this->from_clause->render($prepared),
			$this->where_clause?->render($prepared),
			$this->group_by_clause?->render($prepared),
			$this->order_by_clause?->render($prepared),
			$this->limit_clause?->render($prepared),
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
