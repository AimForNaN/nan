<?php

namespace NaN;

use League\Container\{
	Container,
	Definition\DefinitionInterface,
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
    TemplateEngine,
};

/**
 * @property Env env
 * @property Request request
 * @property TemplateEngine tpl
 */
class App implements \ArrayAccess, RequestHandlerInterface {
	private Container $registry;
	private Routes $routes;

	public function __construct() {
		$this->registry = new Container();
		$this->routes = new Routes();
	}

	public function __get(string $name) {
		return $this->registry->get($name);
	}

	public function __isset($name): bool {
		return $this->registry->has($name);
	}

	/**
	 * @disregard
	 */
	protected function assertResponseInterface(ResponseInterface $rsp) {}

	/**
	 * @disregard
	 */
	protected function assertRoute(Route $route) {}

	public function extendService(string $name): DefinitionInterface {
		return $this->registry->extend($name);
	}

	/**
	 * @todo Dependency injection for handler!
	 */
	public function handle(ServerRequestInterface $request): ResponseInterface {
		$route = $this->match($request);
		$this->assertRoute($route);
		$route->pattern->compile();
		$route->pattern->match($request);
		$args = $route->pattern->getMatches();

		$handler = $route->toCallable();

		$rsp = new Response(200, ['Content-Type' => 'text/html']);
		$rsp = DI::inject($handler, $args, function ($value, $type) use ($request, $rsp) {
			switch ($type) {
				case App::class:
					return $this;
				case Env::class:
					return $this->registry->get('env');
				case Response::class:
				case ResponseInterface::class:
					return $rsp;
				case Request::class:
				case ServerRequestInterface::class:
					return $request;
				case TemplateEngine::class:
					return $this->registry->get('tpl');
			}

			if ($this->registry->has($type)) {
				return $this->registry->get($type);
			}

			return null;
		});
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
		$routes = $this->routes[$method];

		foreach ($routes as $route) {
			$route->pattern->compile();

			if ($route->pattern->match($request)) {
				return $route;
			}
		}

		return null;
	}

	public function registerDefaultEnvService(string $alias = 'env'): DefinitionInterface {
		return $this->registerService($alias, Env::class)->setShared(true);
	}

	public function registerDefaultRequestService(string $alias = 'request'): DefinitionInterface {
		return $this->registerService($alias, Request::class)->addArguments([
			$_SERVER['REQUEST_METHOD'],
			$_SERVER['PATH_INFO'] ?? '/',
			getallheaders(),
		])->setShared(true);
	}

	public function registerDefaultTemplateService(string $alias = 'tpl'): DefinitionInterface {
		return $this->registerService($alias, TemplateEngine::class)->setShared(true);
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
	public function registerService(string $name, string $class, array $args = []): DefinitionInterface {
		return $this->registry->add($name, $class)->addArguments($args);
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
