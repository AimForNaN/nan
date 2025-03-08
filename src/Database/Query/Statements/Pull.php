<?php

namespace NaN\Database\Query\Statements;

use NaN\Database\Query\Statements\{
	Clauses\FromClause,
	Clauses\GroupByClause,
	Clauses\LimitClause,
	Clauses\OrderByClause,
	Clauses\SelectClause,
	Clauses\WhereClause,
	Clauses\Traits\FromClauseTrait,
	Clauses\Traits\GroupByTrait,
	Clauses\Traits\LimitClauseTrait,
	Clauses\Traits\OrderByTrait,
	Clauses\Traits\WhereClauseTrait,
	Interfaces\PullInterface,
	Traits\StatementTrait,
};

class Pull implements PullInterface {
	use FromClauseTrait;
	use GroupByTrait;
	use LimitClauseTrait;
	use OrderByTrait;
	use StatementTrait;
	use WhereClauseTrait;

	public function __construct() {
	}

	public function __invoke(...$args): static {
		return $this->pull(...$args);
	}

	public function first(): static {
		$this->limit(1);
		return $this;
	}

	public function last(string $column): static {
		$this->orderBy([$column => 'desc']);
		$this->limit(1);
		return $this;
	}

	public function pull(array $columns = ['*'], bool $distinct = false): static {
		$select_clause = new SelectClause();
		$select_clause->addColumns($columns);

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
}
