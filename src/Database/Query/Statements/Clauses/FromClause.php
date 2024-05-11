<?php

namespace NaN\Database\Query\Statements\Clauses;

use NaN\Database\Attrs\TableAttr;
use NaN\Database\Query\Statements\PullInterface;

class FromClause extends \NaN\Collections\Collection implements ClauseInterface {
	public function addRawQuery(string $query, array $bindings = []): static {
		$this->data[] = [
			'expr' => 'raw',
			'bindings' => $bindings,
			'query' =>  $query,
		];
		return $this;
	}

	public function addSubQuery(PullInterface $query): static {
		$this->data[] = [
			'expr' => 'query',
			'query' => $query,
		];
		return $this;
	}

	public function addTable(string $table, ?string $database = null, ?string $alias = null): static {
		$this->data[] = [
			'expr' => 'table',
			'alias' => $alias,
			'database' => $database,
			'table' => $table,
		];
		return $this;
	}

	public function addTableFromClass(string $class, string $alias = null): static {
		$table = $this->getTableFromClass($class);
		$this->addTable($table->name, $table->database, $alias);
		return $this;
	}

	public function getBindings(): array {
		return $this->reduce(function ($ret, $item) {
			\extract($item);

			switch ($expr) {
				case 'query':
					return \array_merge($ret, $query->getBindings());
					break;
				case 'raw':
					return \array_merge($ret, $bindings);
					break;
			}

			return $ret;
		}, []);
	}

	public function getTableFromClass(string $class): TableAttr {
		$ref = new \ReflectionClass($class);
		[$table] = $ref->getAttributes(TableAttr::class);

		if ($table instanceof \ReflectionAttribute) {
			$table = $table->newInstance();
		}

		return $table;
	}

	public function offsetGet(mixed $offset): mixed {
		throw new \BadMethodCallException('Getting value through array accessor is not supported!');
	}

	public function offsetSet(mixed $offset, mixed $value): void {
		throw new \BadMethodCallException('Setting value through array accessor is not supported!');
	}

	public function render(bool $prepared = false): string {
		return 'FROM ' . \implode(', ', \array_filter($this->map(function ($column) {
			\extract($column);

			switch ($expr) {
				case 'query':
					return $query->render($prepared);
				case 'table':
					$ret = '';

					if ($database) {
						$ret .= $database . '.';
					}

					$ret .= $table;

					if ($alias) {
						$ret .= 'AS ' . $alias;
					}

					return $ret;
			}

			// Raw expression!
			return $query ?? null;
		})));
	}
}
