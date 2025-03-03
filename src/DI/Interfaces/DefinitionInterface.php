<?php

namespace NaN\DI\Interfaces;

use Psr\Container\ContainerInterface as PsrContainerInterface;

interface DefinitionInterface {
	public function is(string $id): bool;

	public function resolve(PsrContainerInterface $container): mixed;
}
