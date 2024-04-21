<?php

namespace NaN;

use League\Container\{
	Definition\DefinitionInterface,
};
use Psr\Http\Message\{
	ResponseInterface,
	ServerRequestInterface,
};
use Psr\Container\ContainerInterface;
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
class App implements RequestHandlerInterface {
	public readonly Routes $routes;

	public function __construct(
		public readonly ContainerInterface $services,
	) {
		$this->routes = new Routes();
	}

	public function __get(string $name) {
		return $this->services->get($name);
	}

	public function __isset($name): bool {
		return $this->services->has($name);
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
		return $this->services->extend($name);
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
					return $this->services->get('env');
				case Response::class:
				case ResponseInterface::class:
					return $rsp;
				case Request::class:
				case ServerRequestInterface::class:
					return $request;
				case TemplateEngine::class:
					return $this->services->get('tpl');
			}

			if ($this->services->has($type)) {
				return $this->services->get($type);
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

	public function run() {
		$req = $this->services->get('request');
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
