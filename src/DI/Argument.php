<?php

namespace NaN\DI;

use NaN\DI\Interfaces\ArgumentInterface;
use Psr\Container\{
	ContainerExceptionInterface as PsrContainerExceptionInterface,
	ContainerInterface as PsrContainerInterface
};

class Argument implements ArgumentInterface {
	protected ?string $name = null;
	protected bool $nullable = false;
	protected bool $optional = false;
	protected ?string $type = '';
	protected bool $variadic = false;

	public function __construct(
		protected mixed $value,
	) {
	}

	public function resolve(?PsrContainerInterface $container = null): mixed {
		if (\is_object($this->value)) {
			return $this->value;
		}

		if ($this->type === null) {
			return null;
		}

		if ($this->type === '') {
			return $this->value;
		}

		switch ($this->type) {
			case 'bool':
			case 'boolean':
				return $this->resolveBoolean();
			case 'double':
			case 'float':
				return $this->resolveFloat();
			case 'int':
			case 'integer':
				return $this->resolveInteger();
			case 'string':
				return $this->resolveString();
		}

		if ($container) {
			if ($container->has($this->type)) {
				return $container->get($this->type);
			}
		}

		return $this->value;
	}

	public function resolveBoolean(): bool {
		switch ($this->value) {
			case 'false':
			case 'no':
				return false;
			case 'true':
			case 'yes':
				return true;
		}
		return (bool)$this->value;
	}

	public function resolveFloat(): float {
		return (float)$this->value;
	}

	public function resolveInteger(): int {
		return (int)$this->value;
	}

	public function resolveString(): string {
		return (string)$this->value;
	}

	public function setName(string $name): ArgumentInterface {
		$this->name = $name;
		return $this;
	}

	public function setNullable(bool $nullable = true): ArgumentInterface
	{
		$this->nullable = $nullable;
		return $this;
	}

	public function setOptional(bool $optional = true): ArgumentInterface {
		$this->optional = $optional;
		return $this;
	}

	public function setType(string $type): ArgumentInterface {
		$this->type = $type;
		return $this;
	}

	public function setVariadic(bool $variadic = true): ArgumentInterface {
		$this->variadic = $variadic;
		return $this;
	}
}
