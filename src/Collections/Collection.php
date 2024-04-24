<?php

namespace NaN\Collections;

class Collection implements \ArrayAccess, \Countable, \IteratorAggregate {
	/**
	 * @param iterable $data 
	 */
	public function __construct(
		protected array $data = [],
	) {
	}

	public function count(): int {
		return \iterator_count($this->getIterator());
	}

	public function filter(callable $filter): static {
		$data = new \CallbackFilterIterator($this->getIterator(), $filter);
		$data = \iterator_to_array($data);

		$ret = clone $this;
		$ret->data = $data;

		return $ret;
	}

	public function find(callable $fn): mixed {
		foreach ($this->getIterator() as $val) {
			if ($fn($val)) {
				return $val;
			}
		}
		return null;
	}

	public function getIterator(): \Traversable {
		return new \ArrayIterator($this->data);
	}

	public function map(callable $fn): \Traversable {
		foreach ($this->getIterator() as $key => $val) {
			yield $fn($val, $key);
		}
	}

	public function offsetExists(mixed $offset): bool {
		return isset($this->data[$offset]);
	}

	public function offsetGet(mixed $offset): mixed {
		return $this->data[$offset];
	}

	public function offsetSet(mixed $offset, mixed $value): void {
		if (!\is_null($offset)) {
			$this->data[$offset] = $value;
		} else {
			$this->data[] = $value;
		}
	}

	public function offsetUnset(mixed $offset): void {
		unset($this->data[$offset]);
	}
	
	public function toArray(): array {
		return \iterator_to_array($this->getIterator());
	}
}
