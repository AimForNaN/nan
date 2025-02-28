<?php

namespace NaN\DI\Interfaces;

use Psr\Container\ContainerInterface as PsrContainerInterface;

interface DefinitionInterface {
	public function addMethodCall(string $method, array $args = []): DefinitionInterface;

	public function addMethodCalls(array $method_calls): DefinitionInterface;

	public function is(string $id): bool;

	public function resolve(PsrContainerInterface $container): mixed;

	public function setShared(bool $shared = true): DefinitionInterface;
}
