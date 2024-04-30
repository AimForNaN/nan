<?php

namespace NaN\App;

use NaN\DI\{
	Arguments\Arguments,
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

class App implements PsrRequestHandlerInterface {
	public function __construct(
		protected PsrContainerInterface $services,
		protected Routes $routes,
	) {
	}

	public function __get(string $name) {
		return $this->services->get($name);
	}

	public function __isset($name): bool {
		return $this->services->has($name);
	}

	protected function assertResponseInterface(PsrResponseInterface $rsp) {}

	protected function assertRoute(Route $route) {}

	public function handle(PsrServerRequestInterface $request): PsrResponseInterface {
		$container = new Container(new Definitions([
			(new Definition($request))->setAlias(PsrServerRequestInterface::class),
		]));
		$container->addDelegate($this->services);

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
		$routes = $this->routes[$method];

		foreach ($routes as $route) {
			if ($route->matches($request)) {
				return $route;
			}
		}

		return null;
	}

	/**
	 * @todo Do something about request service!
	 */
	public function run() {
		$req = new Request('GET', $_SERVER['PATH_INFO'] ?? '/', getallheaders());
		try {
			$rsp = $this->handle($req);
		} catch (\Throwable $th) {
			if ($this->services->has('logger')) {
				$logger = $this->services->get('logger');
				$logger->error($th->getMessage());
			}
			$rsp = new Response(500);
		}
		Response::send($rsp);
	}
}
