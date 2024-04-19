<?php

namespace NaN;

interface ProvideInterface {
	/**
	 * Used for dependency injection.
	 *
	 * @param mixed $data Useful for instantiating the class.
	 *
	 * @return static Instance of class.
	 */
	static public function provide(mixed $data): static;
}
