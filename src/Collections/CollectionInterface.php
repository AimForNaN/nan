<?php

namespace NaN\Collections;

interface CollectionInterface extends \ArrayAccess, \Countable, \IteratorAggregate {
	public function every(callable $fn): bool;

	public function filter(callable $filter): CollectionInterface;

	public function find(callable $fn): mixed;

	public function map(callable $fn): CollectionInterface;

	public function some(callable $fn): bool;

	public function toArray(): array;
}
