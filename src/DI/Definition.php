<?php

namespace NaN\DI;

use NaN\DI\Interfaces\DefinitionInterface;
use Psr\Container\ContainerInterface as PsrContainerInterface;

class Definition implements DefinitionInterface {
	protected ?string $alias = null;
	protected array $method_calls = [];
	protected mixed $resolved = null;
	protected bool $shared = false;

	public function __construct(
		protected mixed $concrete,
		protected array $arguments = [],
	) {
	}

	public function addMethodCall(string $method, array $args = []): DefinitionInterface {
		$this->method_calls[] = [
			'arguments' => $args,
			'method' =>  $method,
		];
		return $this;
	}

	public function addMethodCalls(array $method_calls): DefinitionInterface {
		foreach ($method_calls as $method => $args) {
			$this->addMethodCall($method, $args);
		}
		return $this;
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

	public function resolve(PsrContainerInterface $container): mixed {
		if ($this->shared && !\is_null($this->resolved)) {
			return $this->resolved;
		}

		return $this->resolveNew($container);
	}

	protected function resolveClosure(PsrContainerInterface $container): mixed {
		$arguments = Arguments::fromValues($this->arguments);
		return \call_user_func($this->concrete, ...$arguments->resolve($container));
	}

	protected function resolveClass(PsrContainerInterface $container): mixed {
		$arguments = Arguments::fromValues($this->arguments);
		return new $this->concrete(...$arguments->resolve($container));
	}

	protected function resolveNew(PsrContainerInterface $container): mixed {
		if ($this->concrete instanceof \Closure) {
			$this->resolved = $this->resolveClosure($container);
		} else {
			$this->resolved = $this->concrete;

			if (\is_string($this->concrete)) {
				if (\class_exists($this->concrete)) {
					$this->resolved = $this->resolveClass($container);

					foreach ($this->method_calls as $method_call) {
						\extract($method_call);
						$arguments = Arguments::fromValues($arguments);
						$this->resolved->$method(...$arguments->resolve($container));
					}
				}
			}
		}

		return $this->resolved;
	}

	/**
	 * Whether to treat it like a singleton!
	 */
	public function setShared(bool $shared = true): DefinitionInterface {
		$this->shared = $shared;
		return $this;
	}
}
