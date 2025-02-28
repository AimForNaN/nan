<?php

namespace NaN\Collections;

/**
 * For the sake of performance,
 *  type-checking doesn't occur until iterated.
 *  If type is null, all elements are permitted.
 */
abstract class TypedCollection extends Collection {
	protected mixed $type = '';

	public function assertType(mixed $item) {
		if (!$this->checkType($item)) {
			throw new \InvalidArgumentException();
		}
	}

	public function checkType(mixed $item): bool {
		if (empty($this->type)) {
			return true;
		}

		if (\is_callable($this->type)) {
			$callable = $this->type;
			return $callable($item);
		}

		return \is_a($item, $this->type);
	}

	public function getIterator(): \Traversable {
		return new \CallbackFilterIterator(new \ArrayIterator($this->data), [$this, 'checkType']);
	}

	public function offsetGet(mixed $offset): mixed {
		$item = parent::offsetGet($offset);
		$this->assertType($item);
		return $item;
	}
}
