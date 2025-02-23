<?php

namespace NaN\Database\Query\Statements\Interfaces;

use NaN\Database\Query\Statements\Clauses\Interfaces\LimitClauseInterface;
use NaN\Database\Query\Statements\Clauses\Interfaces\WhereClauseInterface;

interface PatchInterface extends LimitClauseInterface, StatementInterface, WhereClauseInterface {
	public function patch(string $table, string $database = ''): static;

	public function with(iterable $columns): static;
}

