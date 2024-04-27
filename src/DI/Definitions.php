<?php

namespace NaN\DI;

use Psr\Container\ContainerInterface as PsrContainerInterface;

class Definitions extends \NaN\Collections\TypedCollection implements PsrContainerInterface {
	public function __construct(
		protected array $data = [],
	) {
		parent::__construct($data, Definition::class);
	}

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
