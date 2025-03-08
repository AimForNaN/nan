<?php

namespace NaN\Database\Query\Statements;

use NaN\Database\Query\Statements\{
	Clauses\DeleteClause,
	Clauses\FromClause,
	Clauses\LimitClause,
	Clauses\OrderByClause,
	Clauses\WhereClause,
	Clauses\Traits\FromClauseTrait,
	Clauses\Traits\LimitClauseTrait,
	Clauses\Traits\OrderByTrait,
	Clauses\Traits\WhereClauseTrait,
	Interfaces\PurgeInterface,
	Traits\StatementTrait,
};

class Purge implements PurgeInterface {
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
}
