<?php

namespace NaN\DI;

use NaN\DI\Interfaces\ContainerInterface;
use Psr\Container\{
	ContainerExceptionInterface as PsrContainerExceptionInterface,
	ContainerInterface as PsrContainerInterface,
};
use NaN\DI\Interfaces\DefinitionInterface;

class Container implements \ArrayAccess, ContainerInterface {
	protected array $delegates = [];

	public function __construct(
		protected Definitions $definitions = new Definitions(),
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

	public function offsetExists(mixed $offset): bool {
		return $this->has($offset);
	}

	public function offsetGet(mixed $offset): mixed {
		return $this->get($offset);
	}

	public function offsetSet(mixed $offset, mixed $value): void {
		$this->definitions[$offset] = $value;
	}

	public function offsetUnset(mixed $offset): void {
		$this->definitions->offsetUnset($offset);
	}
}
