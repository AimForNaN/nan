<?php

namespace NaN\DI\Interfaces;

use Psr\Container\ContainerInterface as PsrContainerInterface;

interface ArgumentInterface {
	public function resolve(?PsrContainerInterface $container = null): mixed;

	public function setName(string $name): ArgumentInterface;

	public function setNullable(bool $nullable = true): ArgumentInterface;

	public function setOptional(bool $optional = true): ArgumentInterface;

	public function setType(string $type): ArgumentInterface;

	public function setVariadic(bool $variadic = true): ArgumentInterface;
}
