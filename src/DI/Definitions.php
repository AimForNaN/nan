<?php

namespace NaN\DI;

use NaN\DI\Interfaces\DefinitionInterface;

class Definitions extends \NaN\Collections\TypedCollection {
	protected mixed $type = DefinitionInterface::class;

	public function get(string $id): ?DefinitionInterface {
		if ($this->offsetExists($id)) {
			return $this->offsetGet($id);
		}

		return $this->find(fn(DefinitionInterface $definition) => $definition->is($id));
	}

	public function has(string $id): bool {
		if ($this->offsetExists($id)) {
			return true;
		}

		return $this->some(fn(DefinitionInterface $definition) => $definition->is($id));
	}
}
