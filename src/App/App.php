<?php

namespace NaN\App;

use NaN\DI\{
	Container,
	Definition,
	Definitions,
};
use Psr\Container\ContainerInterface as PsrContainerInterface;
use Psr\Http\{
    Message\ResponseInterface as PsrResponseInterface,
	Message\ServerRequestInterface as PsrServerRequestInterface,
	Server\RequestHandlerInterface as PsrRequestHandlerInterface,
};

class App implements \ArrayAccess, PsrRequestHandlerInterface {
	public function __construct(
		protected PsrContainerInterface $services,
		protected Routes $routes,
	) {
	}

	protected function assertResponseInterface(PsrResponseInterface $rsp) {}

	protected function assertRoute(Route $route) {}

	public function handle(PsrServerRequestInterface $request): PsrResponseInterface {
		$container = new Container(new Definitions([
			(new Definition($this))->setAlias(App::class),
			(new Definition($request))->setAlias(PsrServerRequestInterface::class),
		]));

		$route = $this->match($request);
		$this->assertRoute($route);

		$rsp = $route->handle($request, $container);
		$this->assertResponseInterface($rsp);

		switch ($request->getMethod()) {
			case 'HEAD':
				return new Response(204, $rsp->getHeaders());
			case 'OPTIONS':
				return new Response(204, headers: [
					'Allow' => $route->method,
				]);
		}

		return $rsp;
	}

	public function match(PsrServerRequestInterface $request): ?Route {
		$method = \strtoupper($request->getMethod());
		$routes = $this->routes->getByMethod($method);

		foreach ($routes as $route) {
			if ($route->matches($request)) {
				return $route;
			}
		}

		return null;
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

	public function run() {
		$req = new Request($_SERVER['REQUEST_METHOD'], $_SERVER['PATH_INFO'] ?? '/', getallheaders());
		$rsp = $this->handle($req);
		Response::send($rsp);
	}
}
