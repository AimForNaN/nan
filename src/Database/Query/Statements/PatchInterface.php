<?php

namespace NaN\Database\Query\Statements;

interface PatchInterface extends LimitClauseInterface, StatementInterface, WhereClauseInterface {
	public function patch(string $table, string $database = ''): static;

	public function with(iterable $columns): static;
}

