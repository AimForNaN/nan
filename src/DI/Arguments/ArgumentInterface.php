<?php

namespace NaN\DI\Arguments;

use Psr\Container\ContainerInterface as PsrContainerInterface;

interface ArgumentInterface {
	public function resolve(PsrContainerInterface $container): mixed;

	public function setName(string $name): ArgumentInterface;

	public function setNullable(bool $nullable = true): ArgumentInterface;

	public function setOptional(bool $optional = true): ArgumentInterface;

	public function setType(string $type): ArgumentInterface;

	public function setVariadic(bool $variadic = true): ArgumentInterface;
}
