<?php

namespace NaN\Database\Query\Statements\Clauses;

use NaN\Database\Query\Statements\Interfaces\StatementInterface;
use NaN\Database\Query\Statements\Traits\StatementTrait;

class FromClause implements StatementInterface {
	use StatementTrait;

	public function __construct(string $table = '', string $database = '', string $alias = '') {
		if (!empty($table)) {
			$this->addTable($table, $database, $alias);
		}
	}

	public function addSubQuery(StatementInterface $query): static {
		$this->data[] = [
			'expr' => 'query',
			'query' => $query,
		];
		return $this;
	}

	public function addTable(string $table, string $database = '', string $alias = ''): static {
		$this->data[] = [
			'expr' => 'table',
			'alias' => $alias,
			'database' => $database,
			'table' => $table,
		];
		return $this;
	}

	public function getBindings(): array {
		return \array_reduce($this->data, function ($ret, $item) {
			/**
			 * @var array $bindings
			 * @var StatementInterface $query
			 */
			\extract($item);

			switch ($expr) {
				case 'query':
					return \array_merge($ret, $query->getBindings());
			}

			return $ret;
		}, []);
	}

	public function offsetGet(mixed $offset): mixed {
		throw new \BadMethodCallException('Getting value through array accessor is not supported!');
	}

	public function offsetSet(mixed $offset, mixed $value): void {
		throw new \BadMethodCallException('Setting value through array accessor is not supported!');
	}

	public function render(bool $prepared = false): string {
		return 'FROM ' . \implode(', ', \array_filter(\array_map(function ($column) {
			/**
			 * @var string $alias
			 * @var string $database
			 * @var \NaN\Database\Query\Statements\Interfaces\StatementInterface $query
			 * @var string $table
			 */
			\extract($column);

			switch ($expr) {
				case 'query':
					return '(' . $query->render($prepared) . ')';
				case 'table':
					$ret = '';

					if (!empty($database)) {
						$ret .= $database . '.';
					}

					$ret .= $table;

					if (!empty($alias)) {
						$ret .= 'AS ' . $alias;
					}

					return $ret;
			}

			return '';
		}, $this->data)));
	}
}
