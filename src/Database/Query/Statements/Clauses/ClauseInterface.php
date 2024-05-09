<?php

namespace NaN\Database\Query\Statements\Clauses;

interface ClauseInterface {
	public function render(bool $prepared = false): string;
}
