<?php

namespace NaN\Database\Query\Statements\Clauses;

class WhereClause extends \NaN\Collections\Collection implements ClauseInterface {
	public function __invoke(string $column, string $operator, mixed $value): static {
		$this->addColumn(null, $column, $operator, $value);
		return $this;
	}

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
		$this->data[$delimiter ? \count($this->data) : 0] = [
			'expr' => 'condition',
			'delimiter' => $delimiter,
			'column' => $column,
			'operator' => $operator,
			'value' => $value,
		];
		return $this;
	}

	/**
	 * Add sub where clause.
	 *
	 * @param ?string $delimiter AND, OR...
	 * @param Closure $fn
	 *
	 * @return static
	 */
	public function addGroup(?string $delimiter, \Closure $fn): static {
		$where_group = new static();
		$this->data[$delimiter ? \count($this->data) : 0] = [
			'expr' => 'group',
			'delimiter' => $delimiter,
			'group' => $where_group,
		];

		$fn($where_group);

		return $this;
	}

	/**
	 * Add AND where expression.
	 *
	 * @param Closure|string $column
	 * @param string $operator =, >=, <=, IN...
	 * @param mixed $value
	 *
	 * @return static
	 *
	 * @see addColumn()
	 */
	public function and(\Closure|string $column, string $operator = null, mixed $value = null): static {
		if ($column instanceof \Closure) {
			return $this->addGroup('AND', $column);
		}

		return $this->addColumn('AND', $column, $operator, $value);
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

	/**
	 * Add OR where expression.
	 *
	 * @param Closure|string $column
	 * @param string $operator =, >=, <=, IN...
	 * @param mixed $value
	 *
	 * @return static
	 *
	 * @see addColumn()
	 */
	public function or(\Closure|string $column, string $operator = null, mixed $value = null): static {
		if ($column instanceof \Closure) {
			return $this->addGroup('OR', $column);
		}

		return $this->addColumn('OR', $column, $operator, $value);
	}

	public function render(bool $prepared = false): string {
		return 'WHERE ' . $this->reduce(function ($ret, $item) use ($prepared) {
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
					$ret .= "$column $operator " . $this->renderValue($value, $prepared);
					break;
				case 'group':
					$ret .= '(' . \str_replace('WHERE ', '', $group->render($prepared)) . ')';
					break;
			}

			return $ret;
		}, '');
	}
	
	public function renderValue(mixed $value, bool $prepared = false): string {
		switch (gettype($value)) {
			case 'array':
				return '(' . \implode(', ', \array_map(fn($v) => $this->renderValue($v, $prepared), $value)) . ')';
			case 'string':
				return $prepared ? '?' : '"' . $value . '"';
		}

		return $prepared ? '?' : (string)$value;
	}
}
