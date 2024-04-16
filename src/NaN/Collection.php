<?php

namespace NaN;

class Collection implements \ArrayAccess, \IteratorAggregate {
	/**
	 * @param iterable $data 
	 */
	public function __construct(protected iterable $data = []) {
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
		return new \IteratorIterator($this->data);
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
		$this->data[$offset] = $value;
	}

	public function offsetUnset(mixed $offset): void {
		unset($this->data[$offset]);
	}
	
	public function toArray(): array {
		return \iterator_to_array($this->getIterator());
	}
}
