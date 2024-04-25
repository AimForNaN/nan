<?php

namespace NaN\Collections;

/**
 * For the sake of performance,
 *  type-checking doesn't occur until iterated or when adding elements.
 */
class TypedCollection extends Collection {
	/**
	 * @param string $type Fully-qualified class (e.g. MyClass::class) or function name (e.g. 'is_string').
	 */
	public function __construct(
		protected array $data,
		private string $type,
	) {
		parent::__construct($this->filter([$this, 'checkType'])->toArray());
	}

	protected function assertType(mixed $item) {
		if (!$this->checkType($item)) {
			throw new \InvalidArgumentException();
		}
	}

	protected function checkType(mixed $item): bool {
		if (\is_callable($this->type)) {
			$callable = $this->type;
			return $callable($item);
		}

		return \is_a($item, $this->type);
	}

	public function offsetSet(mixed $offset, mixed $value): void {
		$this->assertType($value);
		parent::offsetSet($offset, $value);
	}
}
