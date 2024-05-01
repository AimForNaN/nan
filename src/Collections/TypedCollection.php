<?php

namespace NaN\Collections;

/**
 * For the sake of performance,
 *  type-checking doesn't occur until iterated.
 *  If type is null, all elements are permitted.
 */
class TypedCollection extends Collection {
	/**
	 * @param array $data
	 * @param string $type Fully-qualified class (e.g. MyClass::class) or function name (e.g. 'is_string').
	 */
	public function __construct(
		protected array $data = [],
		private string $type = '',
	) {
		parent::__construct($data);
	}

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
}
