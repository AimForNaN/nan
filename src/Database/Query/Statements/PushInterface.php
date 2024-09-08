<?php

namespace NaN\Database\Query\Statements;

interface PushInterface extends StatementInterface, WhereClauseInterface {
	public function into(string $table, string $database = ''): static;

	public function push(iterable $columns): static;
}
