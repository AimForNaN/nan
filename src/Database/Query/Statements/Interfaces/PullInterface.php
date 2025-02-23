<?php

namespace NaN\Database\Query\Statements\Interfaces;

use NaN\Database\Query\Statements\Clauses\Interfaces\FromClauseInterface;
use NaN\Database\Query\Statements\Clauses\Interfaces\GroupByInterface;
use NaN\Database\Query\Statements\Clauses\Interfaces\LimitClauseInterface;
use NaN\Database\Query\Statements\Clauses\Interfaces\OrderByInterface;
use NaN\Database\Query\Statements\Clauses\Interfaces\WhereClauseInterface;

interface PullInterface extends FromClauseInterface, GroupByInterface, LimitClauseInterface, OrderByInterface, StatementInterface, WhereClauseInterface {
	public function first(): static;

	public function last(string $column): static;

	public function pull(array $columns, bool $distinct = false): static;
}
