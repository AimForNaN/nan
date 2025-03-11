<?php

namespace NaN\Database\Query\Statements\Interfaces;

use NaN\Database\Query\Statements\Clauses\OrderByClause;

interface OrderByInterface {
	public function orderBy(array $order): static;

	public function setOrderBy(OrderByClause $order_by_clause): static;
}
