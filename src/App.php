<?php

namespace NaN;

use NaN\App\Middleware;
use NaN\DI\Container;
use NaN\Http\{
    Request,
    Response,
};
use Psr\Container\ContainerInterface as PsrContainerInterface;
use Psr\Http\Message\{
	ResponseInterface as PsrResponseInterface,
	ServerRequestInterface as PsrServerRequestInterface,
};
use Psr\Http\Server\{
	MiddlewareInterface as PsrMiddlewareInterface,
	RequestHandlerInterface as PsrRequestHandlerInterface,
};

class App implements \ArrayAccess, PsrRequestHandlerInterface {

	public function __construct(
		protected PsrContainerInterface $services = new Container(),
		protected Middleware $middleware = new Middleware(),
	) {
	}

	public function __get(string $name) {
		return $this->services->get($name);
	}

	public function handle(PsrServerRequestInterface $request): PsrResponseInterface {
		return $this->middleware->handle($request, $this);
	}

	public function offsetExists(mixed $offset): bool {
		return $this->services->has($offset);
	}

	public function offsetGet(mixed $offset): mixed {
		return $this->services->get($offset);
	}

	public function offsetSet(mixed $offset, mixed $value): void {
		$this->services->offsetSet($offset, $value);
	}

	public function offsetUnset(mixed $offset): void {
		$this->services->offsetUnset($offset);
	}

	/**
	 * Exceptions and errors should be handled on a global level
	 *  (e.g. register_shutdown_function, set_error_handler, set_exception_handler, etc).
	 */
	public function run() {
		$req = Request::fromGlobals();
		$rsp = $this->handle($req);
		Response::send($rsp);
	}

	public function use(PsrMiddlewareInterface $middleware): static {
		$this->middleware[] = $middleware;
		return $this;
	}
}
