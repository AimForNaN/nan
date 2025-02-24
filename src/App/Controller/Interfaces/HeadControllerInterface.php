<?php

namespace NaN\App\Controller\Interfaces;

use Psr\Http\Message\ResponseInterface as PsrResponseInterface;

interface HeadControllerInterface extends ControllerInterface {
	public function head(): PsrResponseInterface;
}