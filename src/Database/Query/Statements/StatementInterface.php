<?php

namespace NaN\Database\Query\Statements;

interface StatementInterface {
	public function getBindings(): array;

	public function render(bool $prepared = false): string;
}
