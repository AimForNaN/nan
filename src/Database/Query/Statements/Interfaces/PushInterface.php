<?php

namespace NaN\Database\Query\Statements\Interfaces;

use NaN\Database\Query\Statements\Clauses\Interfaces\WhereClauseInterface;

interface PushInterface extends StatementInterface, WhereClauseInterface {
	public function into(string $table, string $database = ''): static;

	public function push(array $columns): static;
}
