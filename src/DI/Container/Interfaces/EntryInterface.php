<?php

namespace NaN\DI\Container\Interfaces;

use Psr\Container\ContainerInterface as PsrContainerInterface;

interface EntryInterface {
	public function is(string $id): bool;

	public function resolve(?PsrContainerInterface $container = null): mixed;
}
