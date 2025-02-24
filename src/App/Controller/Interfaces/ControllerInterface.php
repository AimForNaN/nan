<?php

namespace NaN\App\Controller\Interfaces;

use Psr\Http\Message\{
	ResponseInterface as PsrResponseInterface,
	ServerRequestInterface as PsrServerRequestInterface,
};
use Psr\Http\Server\RequestHandlerInterface as PsrRequestHandlerInterface;

interface ControllerInterface extends PsrRequestHandlerInterface {
	public function handle(PsrServerRequestInterface $request): PsrResponseInterface;
}
