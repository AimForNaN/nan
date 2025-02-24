<?php

namespace NaN\Collections;

interface CollectionInterface extends \ArrayAccess, \Countable, \IteratorAggregate {
	public function every(callable $fn): bool;

	public function filter(callable $filter): \Traversable;

	public function find(callable $fn): mixed;

	public function implode(string $delimiter);

	public function map(callable $fn): array;

	public function reduce(callable $fn, mixed $ret = null): mixed;

	public function some(callable $fn): bool;

	public function toArray(): array;
}
