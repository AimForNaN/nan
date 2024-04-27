<?php

namespace NaN\DI;

use Psr\Container\ContainerInterface as PsrContainerInterface;

interface ContainerInterface extends PsrContainerInterface {
	public function addDelegate(PsrContainerInterface $container);
}
