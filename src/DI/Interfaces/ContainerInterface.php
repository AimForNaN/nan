<?php

namespace NaN\DI\Interfaces;

use Psr\Container\ContainerInterface as PsrContainerInterface;

interface ContainerInterface extends PsrContainerInterface {
	public function addDelegate(PsrContainerInterface $container);
}
