<?php

namespace NaN\DI;

use Psr\Container\ContainerInterface as PsrContainerInterface;

interface DefinitionInterface {
	public function addMethodCall(string $method, array $args): DefinitionInterface;

	public function addMethodCalls(array $method_calls): DefinitionInterface;

	public function is(string $alias): bool;

	public function resolve(PsrContainerInterface $container): mixed;

	public function setAlias(string $alias): DefinitionInterface;

	public function setShared(bool $shared = true): DefinitionInterface;
}
