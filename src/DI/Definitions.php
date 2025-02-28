<?php

namespace NaN\DI;

use NaN\DI\Interfaces\DefinitionInterface;

class Definitions extends \NaN\Collections\TypedCollection {
	protected mixed $type = DefinitionInterface::class;

	public function get(string $alias): ?DefinitionInterface {
		if ($this->offsetExists($alias)) {
			return $this->offsetGet($alias);
		}

		return $this->find(fn(DefinitionInterface $definition) => $definition->is($alias));
	}

	public function has(string $alias): bool {
		if ($this->offsetExists($alias)) {
			return true;
		}

		return $this->some(fn(DefinitionInterface $definition) => $definition->is($alias));
	}
}
