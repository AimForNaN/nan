<?php

namespace NaN\DI;

use NaN\DI\Interfaces\DefinitionInterface;

class Definitions {

	public function get(string $id): ?DefinitionInterface {
	}

	public function has(string $id): bool {
		if ($this->offsetExists($id)) {
			return true;
		}

		return $this->some(fn(DefinitionInterface $definition) => $definition->is($id));
	}
}
