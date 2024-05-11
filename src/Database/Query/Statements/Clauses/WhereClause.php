<?php

namespace NaN\Database\Query\Statements\Clauses;

class WhereClause extends \NaN\Collections\Collection implements ClauseInterface {
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

	public function addRaw(?string $delimiter, string $condition, array $bindings = []): static {
		$this->data[] = [
			'expr' => 'raw',
			'delimiter' => $delimiter,
			'bindings' => $bindings,
			'condition' => $condition,
		];
		return $this;
	}

	static public function generatePlaceHolders(int $count): string {
		return \implode(', ', \array_fill(0, $count, '?'));
	}

	public function getBindings(): array {
		return $this->reduce(function ($ret, $item) {
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
