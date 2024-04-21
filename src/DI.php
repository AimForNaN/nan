<?php

namespace NaN;

/**
 * Dependency Injection
 */
class DI {
	static public function analyze(callable $handler): array {
		$rf = new \ReflectionFunction($handler);
		$params = $rf->getParameters();
		return [\array_reduce($params, function (array $ret, \ReflectionParameter $param) {
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

			$ret[] = [
				'name' => $name,
				'default_value' => $default_value,
				'nullable' => $nullable,
				'optional' => $optional,
				'type' => $type,
				'variadic' => $variadic,
			];

			return $ret;
		}, []), $rf];
	}

	static public function cast(mixed $value, mixed $type, ?callable $type_resolver = null): mixed {
		if ($type === null) {
			return null;
		}

		if ($type === '') {
			return $value;
		}

		switch ($type) {
			case 'bool':
			case 'boolean':
				return static::toBool($value);
			case 'double':
			case 'float':
				return (double)$value;
			case 'int':
			case 'integer':
				return (int)$value;
			case 'string':
				return (string)$value;
		}

		if (\is_callable($type_resolver)) {
			$ret = $type_resolver($value, $type);
			if (!\is_null($ret)) {
				return $ret;
			}
		}

		if (\is_a($type, ProvideInterface::class)) {
			return $type::provide($value);
		}

		return $value;
	}

	/**
	 * @param callable $handler Closure to be analyzed and invoked.
	 * @param array [$args] Arguments to pass to handler.
	 * @param callable [$type_resolver] Secondary type resolution.
	 */
	static public function inject(callable $handler, array $args = [], callable $type_resolver = null): mixed {
		[$analysis, $rf] = static::analyze($handler);

		foreach ($analysis as $meta) {
			\extract($meta);

			if ($variadic) {
				// @todo
				continue;
			} else if (isset($args[$name])) {
				$value = $args[$name];
				$args[$name] = DI::cast($value, $type, $type_resolver);
			} else if (!$optional) {
				$args[$name] = DI::cast(null, $type, $type_resolver);
			}
		}

		return $rf->invokeArgs($args);
	}

	static public function toBool(mixed $value): bool {
		switch ($value) {
			case 'false':
			case 'no':
				return false;
			case 'true':
			case 'yes':
				return true;
		}
		return (bool)$value;
	}
}
