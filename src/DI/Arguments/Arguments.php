<?php

namespace NaN\DI\Arguments;

use Psr\Container\ContainerInterface as PsrContainerInterface;

class Arguments extends \NaN\Collections\TypedCollection {
	public function __construct(
		protected array $data = [],
	) {
		parent::__construct($data, Argument::class);
	}

	static protected function analyzeCallable(callable $callable): array {
		$rf = new \ReflectionFunction($callable);
		return \array_map([static::class, 'analyzeParameter'], $rf->getParameters());
	}

	static protected function analyzeClass(string $class): array {
		$rf = new \ReflectionClass($class);
		$rf = $rf->getConstructor();
		return \array_map([static::class, 'analyzeParameter'], $rf->getParameters());
	}

	static protected function analyzeClassMethod(string $class, string $method): array {
		$rf = new \ReflectionClass($class);
		$rf = $rf->getMethod($method);
		return \array_map([static::class, 'analyzeParameter'], $rf->getParameters());
	}

	static protected function analyzeParameter(\ReflectionParameter $param): array {
		$name = $param->getName();
		$type = $param->hasType() ? $param->getType() : '';
		$optional = $param->isOptional();
		$default_value = $optional ? $param->getDefaultValue() : null;
		$nullable = $param->allowsNull();
		$variadic = $param->isVariadic();

		if ($type instanceof \ReflectionUnionType) {
			$type = \array_map(fn(\ReflectionNamedType $item) => $item->getName(), $type->getTypes());
		} else if ($type instanceof \ReflectionNamedType) {
			$type = $type->getName();
		} else {
			$type = '';
		}

		return \compact('default_value', 'name', 'nullable', 'optional', 'type', 'variadic');
	}

	static protected function fromAnalysis(array $arguments, array $values = []): static {
		return new static(\array_map(function ($argument, $idx) use ($values): Argument {
			\extract($argument);

			// If not named parameter, then indexed!
			if (isset($values[$name])) {
				$default_value = $values[$name];
			} else if (isset($values[$idx])) {
				$default_value = $values[$idx];
			}

			return (new Argument($default_value))
				->setName($name)
				->setNullable($nullable)
				->setOptional($optional)
				->setType($type)
				->setVariadic($variadic)
			;
		}, $arguments, \array_keys($arguments)));
	}

	/**
	 * @param callable $callable Callable to analyze.
	 * @param array [$values] Argument values to match against.
	 */
	static public function fromCallable(callable $callable, array $values = []): static {
		$arguments = static::analyzeCallable($callable);
		return static::fromAnalysis($arguments, $values);
	}

	static public function fromClass(string $class, array $values = []): static {
		$arguments = static::analyzeClass($class);
		return static::fromAnalysis($arguments, $values);
	}

	static public function fromValues(array $values): static {
		return new static(\array_map(function ($value) {
			if ($value instanceof ArgumentInterface) {
				return $value;
			}

			$argument = new Argument($value);

			if (\is_string($value)) {
				if (\class_exists($value)) {
					$argument->setType($value);
				}
			}

			return $argument;
		}, $values));
	}

	public function resolve(PsrContainerInterface $container): array {
		return $this->map(fn(ArgumentInterface $argument) => $argument->resolve($container));
	}
}
