<?php

namespace NaN\Database\Query\Statements\Clauses;

class WhereClause extends \NaN\Collections\Collection implements ClauseInterface {
	/**
	 * Add where expression.
	 *
	 * @param ?string $delimiter AND, OR... Use null for first where expression.
	 * @param string $column
	 * @param string $operator =, >=, <=, IN...
	 * @param mixed $value
	 *
	 * @return static
	 */
	public function addColumn(?string $delimiter, string $column, string $operator, mixed $value): static {
		$this->data[] = [
			'expr' => 'condition',
			'delimiter' => $delimiter,
			'column' => $column,
			'operator' => $operator,
			'value' => $value,
		];
		return $this;
	}

	public function andColumn(string $column, string $operator, mixed $value): static {
		return $this->addColumn('AND', $column, $operator, $value);
	}

	public function orColumn(string $column, string $operator, mixed $value): static {
		return $this->addColumn('OR', $column, $operator, $value);
	}

	/**
	 * Add sub where clause.
	 *
	 * @param ?string $delimiter AND, OR...
	 * @param callable $fn
	 *
	 * @return static
	 */
	public function addGroup(?string $delimiter, callable $fn): static {
		$where_group = new static();
		$this->data[] = [
			'expr' => 'group',
			'delimiter' => $delimiter,
			'group' => $where_group,
		];

		$fn($where_group);

		return $this;
	}

	public function andGroup(callable $fn): static {
		return $this->addGroup('AND', $fn);
	}

	public function orGroup(callable $fn): static {
		return $this->addGroup('OR', $fn);
	}

	/**
	 * Add raw where expression.
	 *
	 * @param ?string $delimiter AND, OR... Use null for first where expression.
	 * @param string $condition Where expression.
	 * @param array [$bindings] Values if performing a prepared statement.
	 *
	 * @return static
	 */
	public function addRaw(?string $delimiter, string $condition, array $bindings = []): static {
		$this->data[] = [
			'expr' => 'raw',
			'delimiter' => $delimiter,
			'bindings' => $bindings,
			'condition' => $condition,
		];
		return $this;
	}

	public function andRaw(string $condition, array $bindings): static {
		return $this->addRaw('AND', $condition, $bindings);
	}

	public function orRaw(string $condition, array $bindings): static {
		return $this->addRaw('OR', $condition, $bindings);
	}

	static public function generatePlaceHolders(int $count): string {
		return \implode(', ', \array_fill(0, $count, '?'));
	}

	public function getBindings(): array {
		return $this->reduce(function ($ret, $item) {
			/**
			 * @var string $expr
			 * @var WhereClause $group
			 * @var mixed $value
			 */
			\extract($item);

			switch ($expr) {
				case 'condition':
					$ret[] = $value;
					break;
				case 'group':
					return \array_merge($ret, $group->getBindings());
				case 'raw':
					return \array_merge($ret, $bindings);
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
		return 'WHERE ' . $this->reduce(function ($ret, $item) {
			/**
			 * @var string $condition
			 * @var string $delimiter
			 * @var string $expr
			 * @var WhereClause $group
			 * @var mixed $value
			 */
			\extract($item);

			if ($delimiter) {
				$ret .= ' ' . \strtoupper($delimiter) . ' ';
			}

			switch ($expr) {
				case 'condition':
					$ret .= "$column $operator ";

					if ($prepared) {
						if (\is_array($value)) {
							$ret .= '(' . static::generatePlaceHolders(\count($value)) . ')';
						} else {
							$ret .= '?';
						}
					} else {
						$ret .= $this->renderValue($value);
					}

					break;
				case 'group':
					$ret .= '(' . $group->render($prepared) . ')';
					break;
				case 'raw':
					$ret .= $condition;
					break;
			}

			return $ret;
		}, '');
	}
	
	public function renderValue(mixed $value): string {
		switch (gettype($value)) {
			case 'array':
				return '(' . \array_map([$this, 'renderValue'], $value) . ')';
			case 'string':
				return '"' . $value . '"';
		}

		return (string)$value;
	}
}
