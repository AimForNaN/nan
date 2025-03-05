<?php

namespace NaN\DI;

use NaN\DI\Container\Entry;
use NaN\DI\Container\Interfaces\EntryInterface;
use NaN\DI\Interfaces\ContainerInterface;
use Psr\Container\{
	ContainerInterface as PsrContainerInterface,
};

class Container extends \NaN\Collections\TypedCollection implements ContainerInterface {
	protected array $delegates = [];
	protected mixed $type = Entry::class;

	public function addDelegate(PsrContainerInterface $container) {
		$this->delegates[] = $container;
	}

	public function get(string $id): mixed {
		$entry = $this->data[$id] ?? null;

		if (!$entry) {
			foreach ($this->data as $container_entry) {
				if ($container_entry->is($id)) {
					$entry = $container_entry;
					break;
				}
			}
		}

		if ($entry instanceof EntryInterface) {
			return $entry->resolve($this);
		}

		foreach ($this->delegates as $delegate) {
			if ($delegate->has($id)) {
				return $delegate->get($id);
			}
		}

		throw new Exceptions\NotFoundException("Entity {$id} could not be found!");
	}

	public function getIterator(): \Traversable {
		$iter = new \AppendIterator();

		$iter->append(new \ArrayIterator($this->data));

		foreach ($this->delegates as $delegate) {
			$iter->append(new \ArrayIterator($delegate->data));
		}

		return $iter;
	}

	public function has(string $id): bool {
		if (isset($this->data[$id])) {
			return true;
		}

		foreach ($this->data as $entry) {
			if ($entry->is($id)) {
				return true;
			}
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
}
