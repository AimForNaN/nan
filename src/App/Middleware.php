<?php

namespace NaN\App;

use NaN\App;
use NaN\Http\Response;
use Psr\Http\Message\{
	ResponseInterface as PsrResponseInterface,
	ServerRequestInterface as PsrServerRequestInterface,
};
use Psr\Http\Server\{
	MiddlewareInterface as PsrMiddlewareInterface,
	RequestHandlerInterface as PsrRequestHandlerInterface,
};

class Middleware extends \SplDoublyLinkedList implements PsrMiddlewareInterface, PsrRequestHandlerInterface {
	public function handle(PsrServerRequestInterface $request, ?App $app = null): PsrResponseInterface {
		$this->rewind();
		return $this->process($request, $this, $app);
	}

	public function process(PsrServerRequestInterface $request, PsrRequestHandlerInterface $handler, ?App $app = null): PsrResponseInterface {
		if (!$this->valid()) {
			return new Response(404);
		}

		$middleware = $this->current();
		$this->next();

		return $middleware->process($request, $handler, $app);
	}
}