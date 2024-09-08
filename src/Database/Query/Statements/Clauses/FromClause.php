<?php

namespace NaN\Database\Query\Statements\Clauses;

use NaN\Database\Query\Statements\StatementInterface;

class FromClause extends \NaN\Collections\Collection implements ClauseInterface {
	public function addSubQuery(StatementInterface $query): static {
		$this->data[] = [
			'expr' => 'query',
			'query' => $query,
		];
		return $this;
	}

	public function addTable(string $table, string $database = null, string $alias = null): static {
		$this->data[] = [
			'expr' => 'table',
			'alias' => $alias,
			'database' => $database,
			'table' => $table,
		];
		return $this;
	}

	public function addTableFromClass($class, string $alias = null): static {
		$this->addTable($class::table(), $class::database(), $alias);
		return $this;
	}

	public function getBindings(): array {
		return $this->reduce(function ($ret, $item) {
			/**
			 * @var Array $bindings
			 * @var \NaN\Database\Query\Statements\StatementInterface $query
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
		return 'FROM ' . \implode(', ', \array_filter($this->map(function ($column) {
			/**
			 * @var string $alias
			 * @var string $database
			 * @var \NaN\Database\Query\Statements\StatementInterface $query
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

					if ($alias) {
						$ret .= 'AS ' . $alias;
					}

					return $ret;
			}

			return '';
		})));
	}
}
