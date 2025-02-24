<?php

namespace NaN\App\Controller\Interfaces;

use Psr\Http\Message\ResponseInterface as PsrResponseInterface;

interface GetControllerInterface extends ControllerInterface {
	public function get(): PsrResponseInterface;
}