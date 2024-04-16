<?php

namespace NaN;

/**
 * For the sake of performance,
 *  type-checking doesn't occur until iterated or when adding elements.
 */
abstract class TypedCollection extends Collection {
	/**
	 * @param string $type Fully-qualified class (e.g. MyClass::class) or function name (e.g. 'is_string').
	 */
	public function __construct(
		private string $type,
	) {
		parent::__construct();
	}

	protected function assertType(mixed $item) {
		$err = new \InvalidArgumentException();

		if (\function_exists($this->type)) {
			if (!${$this->type}($item)) {
				throw $err;
			}
		}

		if (!\is_a($item, $this->type)) {
			throw $err;
		}
	}

	public function getIterator(): \Traversable {
		return new \CallbackFilterIterator($this->data, function ($item) {
			$this->assertType($item);
			return true;
		});
	}

	public function offsetSet(mixed $offset, mixed $value): void {
		$this->assertType($value);
		$this->data[$offset] = $value;
	}
}
