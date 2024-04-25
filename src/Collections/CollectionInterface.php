<?php

namespace NaN\Collections;

interface CollectionInterface extends \ArrayAccess, \Countable, \IteratorAggregate {
	public function filter(callable $filter): CollectionInterface;

	public function find(callable $fn): mixed;

	public function map(callable $fn): CollectionInterface;

	public function toArray(): array;
}
