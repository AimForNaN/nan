<?php

namespace NaN\Database\Query\Statements\Clauses\Interfaces;

use NaN\Database\Query\Statements\Clauses\GroupByClause;

interface GroupByInterface {
	public function groupBy(array $columns): static;

	public function setGroupBy(GroupByClause $group_by_clause): static;
}
