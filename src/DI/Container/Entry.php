<?php

namespace NaN\DI\Container;

use NaN\DI\Arguments;
use NaN\DI\Container\Interfaces\EntryInterface;
use Psr\Container\ContainerInterface as PsrContainerInterface;

class Entry implements EntryInterface {
	protected array $method_calls = [];
	protected mixed $resolved = null;

	public function __construct(
		protected mixed $concrete,
		protected bool $shared = false,
	) {
	}

	public function getClassName(): ?string {
		return $this->isClass() ? $this->concrete : null;
	}

	public function is(string $id): bool {
		if ($this->concrete === $id) {
			return true;
		}

		if (\is_object($this->concrete) && \is_a($this->concrete, $id)) {
			return true;
		}

		return false;
	}

	public function isClass(): bool {
		return \is_string($this->concrete) && \class_exists($this->concrete);
	}

	public function resolve(?PsrContainerInterface $container = null): mixed {
		if ($this->shared && !\is_null($this->resolved)) {
			return $this->resolved;
		}

		return $this->resolveNew($container);
	}

	protected function resolveClosure(?PsrContainerInterface $container = null): mixed {
		$fn = $container ? \Closure::bind($this->concrete, $container) : $this->concrete;
		return $fn();
	}

	protected function resolveClass(): mixed {
		return new $this->concrete();
	}

	protected function resolveNew(?PsrContainerInterface $container = null): mixed {
		if ($this->concrete instanceof \Closure) {
			$this->resolved = $this->resolveClosure($container);
		} else {
			$this->resolved = $this->concrete;

			if (\is_string($this->concrete)) {
				if (\class_exists($this->concrete)) {
					$this->resolved = $this->resolveClass();
				}
			}
		}

		return $this->resolved;
	}
}
