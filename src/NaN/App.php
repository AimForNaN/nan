<?php

namespace NaN;

use GuzzleHttp\Psr7\Utils;
use League\Container\{
	Container,
	Definition\Definition,
	Definition\DefinitionAggregate,
};
use Psr\Http\Message\{
	ResponseInterface,
	ServerRequestInterface,
};
use Psr\Http\Server\RequestHandlerInterface;
use NaN\App\{
	Request,
    Response,
    Route,
    Routes,
};

/**
 * @property Request request
 */
class App implements \ArrayAccess, RequestHandlerInterface {
	private Container $registry;
	private Routes $routes;

	public function __construct() {
		$aggregate = new DefinitionAggregate([
		(new Definition('request', Request::class))->addArguments([
				$_SERVER['REQUEST_METHOD'],
				$_SERVER['PATH_INFO'] ?? '/',
				getallheaders(),
			]),
		]);
		$this->registry = new Container($aggregate);
		$this->routes = new Routes();
	}

	public function __get(string $name) {
		return $this->registry->get($name);
	}

	public function __isset($name): bool {
		return $this->registry->has($name);
	}

	protected function assertResponseInterface(ResponseInterface $rsp) {}

	protected function assertRoute(Route $route) {}

	/**
	 * @todo Dependency injection for handler!
	 */
	public function handle(ServerRequestInterface $request): ResponseInterface {
		$route = $this->match($request);
		$this->assertRoute($route);

		$handler = $route->toCallable();
		$rsp = new Response(200, ['Content-Type' => 'text/html']);
		$rsp = $handler($rsp);
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

	public function offsetExists(mixed $offset): bool {
		return false;
	}

	public function offsetGet(mixed $offset): mixed {
		return null;
	}

	public function offsetSet(mixed $offset, mixed $value): void {
		if (!\is_array($value)) {
			$value = [$value];
		}
		$this->registerRoutes($value);
	}

	public function offsetUnset(mixed $offset): void {
	}

	public function match(ServerRequestInterface $request): ?Route {
		$method = \strtoupper($request->getMethod());
		$routes = $this->routes[$method] ?? [];

		foreach ($routes as $route) {
			$route->pattern->compile();

			if ($route->pattern->match($request)) {
				return $route;
			}
		}

		return null;
	}

	public function registerRoutes(array $routes) {
		foreach ($routes as $route) {
			$this->routes[] = $route;
		}
	}

	/**
	 * Register service locally.
	 *
	 * Any service that is registered can be accessed as a property by name.
	 */
	public function registerService(string $name, string $class, array $args = []) {
		$this->registry->add($name, $class)->addArguments($args);
	}

	public function run() {
		$req = $this->registry->get('request');
		try {
			$rsp = $this->handle($req);
		} catch (\Throwable $th) {
			if ($this->registry->has('logger')) {
				$logger = $this->registry->get('logger');
				$logger->error($th->getMessage());
			}
			$rsp = new Response(500);
		}
		Response::send($rsp);
	}
}
