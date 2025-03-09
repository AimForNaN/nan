<?php

namespace NaN\Database\Query\Statements;

use NaN\Database\Query\Statements\Clauses\{FromClause,
	GroupByClause,
	LimitClause,
	OrderByClause,
	SelectClause,
	WhereClause};
use NaN\Database\Query\Statements\Clauses\Traits\{
	FromClauseTrait,
	GroupByTrait,
	LimitClauseTrait,
	OrderByTrait,
	WhereClauseTrait,
};
use NaN\Database\Query\Statements\Traits\StatementTrait;

class Pull implements Interfaces\PullInterface {
	use FromClauseTrait;
	use GroupByTrait;
	use LimitClauseTrait;
	use OrderByTrait;
	use StatementTrait;
	use WhereClauseTrait;

	public function __construct() {
		$this->setSelect(new SelectClause());
	}

	public function __invoke(...$args): static {
		return $this->pull(...$args);
	}

	public function first(): static {
		$this->limit();
		return $this;
	}

	public function last(string $column): static {
		$this->orderBy([$column => 'desc']);
		$this->limit();
		return $this;
	}

	public function pull(array $columns, bool $distinct = false): static {
		$select_clause = new SelectClause();
		$select_clause->setColumns($columns);

		if ($distinct) {
			$select_clause->distinct();
		}

		return $this->setSelect($select_clause);
	}

	public function setFrom(FromClause $from_clause): static {
		$this->query[1] = $from_clause;
		return $this;
	}

	public function setGroupBy(GroupByClause $group_by_clause): static {
		$this->query[3] = $group_by_clause;
		return $this;
	}

	public function setLimit(LimitClause $limit_clause): static {
		$this->query[5] = $limit_clause;
		return $this;
	}

	public function setOrderBy(OrderByClause $order_by_clause): static {
		$this->query[4] = $order_by_clause;
		return $this;
	}

	public function setSelect(SelectClause $select_clause): static {
		$this->query[0] = $select_clause;
		return $this;
	}

	public function setWhere(WhereClause $where_clause): static {
		$this->query[2] = $where_clause;
		return $this;
	}

	public function validate(): bool {
		if (\count($this->query) === 0) {
			return false;
		}

		if (!\is_a($this->query[0], SelectClause::class)) {
			return false;
		}

		if (!\is_a($this->query[1], FromClause::class)) {
			return false;
		}

		return true;
	}
}
