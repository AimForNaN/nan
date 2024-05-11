<?php

namespace NaN\Database\Query\Statements\Clauses;

interface ClauseInterface {
	public function getBindings(): array;

	public function render(bool $prepared = false): string;
}
