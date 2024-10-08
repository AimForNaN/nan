<?php

namespace NaN\DI;

use Psr\Container\ContainerExceptionInterface as PsrContainerExceptionInterface;
use Psr\Container\ContainerInterface as PsrContainerInterface;

class Container implements ContainerInterface {
	protected array $delegates = [];

	public function __construct(
		protected Definitions $definitions,
	) {
	}

	public function addDelegate(PsrContainerInterface $container) {
		$this->delegates[] = $container;
	}

	public function get(string $id): mixed {
		$definition = $this->definitions->get($id);
		if ($definition instanceof DefinitionInterface) {
			return $definition->resolve($this);
		}

		foreach ($this->delegates as $delegate) {
			// No point in wasting time checking if it has it! 
			try {
				return $delegate->get($id);
			} catch (PsrContainerExceptionInterface) {
				continue;
			}
		}

		throw new Exceptions\NotFoundException("Entity {$id} not found!");
	}

	public function has(string $id): bool {
		if ($this->definitions->has($id)) {
			return true;
		}

		foreach ($this->delegates as $delegate) {
			if ($delegate->has($id)) {
				return true;
			}
		}

		return false;
	}
}
