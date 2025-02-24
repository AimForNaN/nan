<?php

namespace NaN\App;

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
use Psr\Log\LoggerInterface as PsrLoggerInterface;

/**
 * @property ?PsrLoggerInterface $logger
 */
class App implements \ArrayAccess, PsrRequestHandlerInterface {
	protected Middleware $middleware;

	public function __construct(
		protected PsrContainerInterface $services,
	) {
		$this->middleware = new Middleware();
	}

	public function __get(string $name) {
		return $this->services->get($name);
	}

	public function handle(PsrServerRequestInterface $request): PsrResponseInterface {
		try {
			return $this->middleware->handle($request, $this);
		} catch (\Exception $exception) {
			if ($this->services->has('logger')) {
				$logger = $this->services->get('logger');
				$logger->error($exception->getMessage());
			}
		}

		return new Response(500);
	}

	public function offsetExists(mixed $offset): bool {
		return $this->services->has($offset);
	}

	public function offsetGet(mixed $offset): mixed {
		return $this->services->get($offset);
	}

	public function offsetSet(mixed $offset, mixed $value): void {
		throw new \RuntimeException('Services are to be defined in the constructor!');
	}

	public function offsetUnset(mixed $offset): void {
		throw new \RuntimeException('Cannot unset a service!');
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

	public function use(PsrMiddlewareInterface $middleware) {
		$this->middleware[] = $middleware;
	}
}
