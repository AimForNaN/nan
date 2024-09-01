<?php

namespace NaN\Database\Query\Statements;

use NaN\Database\Query\Statements\Clauses\{
	ClauseInterface,
};

trait StatementTrait {
	private array $query = [];

	public function getBindings(): array {
		return \array_reduce($this->query, function (Array $ret, ClauseInterface $stmt): Array {
			return \array_merge($ret, $stmt->getBindings());
		}, []);
	}

	public function render(bool $prepared = false): string {
		ksort($this->query);
		return \implode(' ', \array_filter(
			\array_map(fn(ClauseInterface $stmt) => $stmt->render($prepared), $this->query)
		));
	}
}
