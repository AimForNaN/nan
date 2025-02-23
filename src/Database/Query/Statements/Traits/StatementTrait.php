<?php

namespace NaN\Database\Query\Statements\Traits;

use NaN\Database\Query\Statements\Clauses\Interfaces\ClauseInterface;

trait StatementTrait {
	private array $query = [];

	public function getBindings(): array {
		return \array_reduce($this->query, function (array $ret, ClauseInterface $stmt): array {
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
