<?php

namespace NaN\Database\Query\Statements;

use NaN\Database\Query\Statements\Clauses\{
	DeleteClause,
	FromClause,
	LimitClause,
	OrderByClause,
	WhereClause,
};
use NaN\Database\Query\Statements\Clauses\Traits\{
	FromClauseTrait,
	LimitClauseTrait,
	OrderByTrait,
	WhereClauseTrait,
};
use NaN\Database\Query\Statements\Traits\StatementTrait;

class Purge implements Interfaces\PurgeInterface {
	use FromClauseTrait;
	use LimitClauseTrait;
	use OrderByTrait;
	use StatementTrait;
	use WhereClauseTrait;

	public function __construct() {
		$this->query[0] = new DeleteClause();
	}

	public function setFrom(FromClause $from_clause): static {
		$this->query[1] = $from_clause;
		return $this;
	}

	public function setLimit(LimitClause $limit_clause): static {
		$this->query[4] = $limit_clause;
		return $this;
	}

	public function setOrderBy(OrderByClause $order_by_clause): static {
		$this->query[3] = $order_by_clause;
		return $this;
	}

	public function setWhere(WhereClause $where_clause): static {
		$this->query[2] = $where_clause;
		return $this;
	}

	public function validate(): bool {
		if (count($this->query) == 0) {
			return false;
		}

		if (!\is_a($this->query[0], DeleteClause::class)) {
			return false;
		}

		if (!\is_a($this->query[2], WhereClause::class)) {
			return false;
		}

		return true;
	}
}
