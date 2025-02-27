<?php

namespace NaN\App\Router;

use NaN\App\App;
use NaN\DI\{Arguments, Container, Definition, Definitions};
use NaN\Http\Response;
use Psr\Http\Message\{
	ResponseInterface as PsrResponseInterface,
	ServerRequestInterface as PsrServerRequestInterface,
};
use Psr\Http\Server\{
	MiddlewareInterface as PsrMiddlewareInterface,
	RequestHandlerInterface as PsrRequestHandlerInterface,
};

class Router implements \ArrayAccess, PsrMiddlewareInterface {
	public function __construct(
		protected Route $root = new Route(),
	) {
	}

	static public function fromArray(array $routes): static {
		$router = new static(Route::fromArray($routes));
		return $router;
	}

	public function insert(string $path, mixed $handler): Route {
		$parts = $this->parsePath($path);
		$current = $this->root;
		$route_path = null;

		foreach ($parts as $part) {
			$route_path .= '/' . $part;

			if (!isset($current[$part])) {
				$route = new Route($route_path);
				$current[$part] = $route;
			}

			$current = $current[$part];
		}

		$current->handler = $handler;

		return $current;
	}

	public function match(string $path): ?Route {
		$parts = $this->parsePath($path);

		$parent = $this->root;
		foreach ($parts as $part) {
			if (!isset($parent[$part])) {
				return null;
			}

			$parent = $parent[$part];
		}

		return $parent;
	}

	public function offsetExists(mixed $offset): bool {
		return (bool)$this->match($offset);
	}

	public function offsetGet(mixed $offset): mixed {
		return $this->match($offset);
	}

	public function offsetSet(mixed $offset, mixed $value): void {
		$this->insert($offset, $value);
	}

	public function offsetUnset(mixed $offset): void {
	}

	protected function parsePath(string $path): array {
		return \array_filter(\explode('/', $path));
	}

	public function process(PsrServerRequestInterface $request, PsrRequestHandlerInterface $handler, ?App $app = null): PsrResponseInterface {
		$response = new Response();
		$route = $this->match($request->getUri()->getPath());

		if (!$route) {
			return $response->withStatus(404);
		}

		$pattern = new RoutePattern($route->path);
		$pattern->compile();
		$pattern->matchesRequest($request);

		$values = $pattern->getMatches();
		$route_handler = $route->toCallable($request);
		$arguments = Arguments::fromCallable($route_handler, $values);
		$definition = new Definition($route_handler, $arguments->toArray());

		$container = new Container(new Definitions([
			(new Definition($app)),
			(new Definition($request)),
			(new Definition($response)),
		]));

		return $definition->resolve($container);
	}
}
