<?php

use NaN\App\Middleware\Router;
use NaN\App\Middleware\Router\Route;
use NaN\Http\{
	Request,
	Response,
};

class RouterBench {
	/**
	 * @Iterations(20)
	 * @Revs(10)
	 * @Warmup(1)
	 */
	public function benchParamNanRoutesArrayInsert(): array {
		$routes = [];

		for ($x = 0; $x < 1000; $x++) {
			$routes[] = new Route('/param/' . $x . '/{id}', function ($id) {
				return new Response(200);
			});
		}

		return $routes;
	}

	/**
	 * @Iterations(20)
	 * @Revs(10)
	 * @Warmup(1)
	 */
	public function benchParamNanRouterInsertManual(): Router {
		$generator = function () {
			for ($x = 0; $x < 1000; $x++) {
				yield $x => new Route('/param/' . $x, null, [
					'{id}' => new Route('/param/' . $x . '/{id}', function ($id) {
						return new Response(200);
					}),
				]);
			}
		};
		$root = new Route('/', null, [
			'param' => new Route('/param', null, \iterator_to_array($generator())),
		]);
		$router = new Router($root);

		return $router;
	}

	/**
	 * @Iterations(20)
	 * @Revs(10)
	 * @Warmup(1)
	 */
	public function benchParamNanRouterInsertIndex(): Router {
		$router = new Router();

		for ($x = 0; $x < 1000; $x++) {
			$router['/param/' . $x . '/{id}'] = function ($id) {
				return new Response(200);
			};
		}

		return $router;
	}

	/**
	 * @Iterations(20)
	 * @Revs(10)
	 * @Warmup(1)
	 */
	public function benchParamNanRouterInsertArray(): Router {
		$routes = [
			':path' => '/',
			'param' => [
				':path' => '/param',
			],
		];

		for ($x = 0; $x < 1000; $x++) {
			$routes['param'][$x] = [
				':path' => '/param/' . $x,
				'{id}' => [
					':path' => '/param/' . $x . '/{id}',
					':handler' => function () {
						return new Response(200);
					},
				],
			];
		}

		return Router::fromArray($routes);
	}

	/**
	 * @Iterations(20)
	 * @Revs(10)
	 * @Warmup(1)
	 */
	public function benchStaticNanRoutesArrayInsert(): array {
		$routes = [];

		for ($x = 0; $x < 1000; $x++) {
			$path = '/param/' . $x . '/1';
			$routes[$path] = new Route($path, function ($id) {
				return new Response(200);
			});
		}

		return $routes;
	}

	/**
	 * @Iterations(20)
	 * @Revs(10)
	 * @Warmup(1)
	 */
	public function benchStaticNanRouterInsertManual(): Router {
		$generator = function () {
			for ($x = 0; $x < 1000; $x++) {
				yield $x => new Route('/param/' . $x, null, [
					'1' => new Route('/param/' . $x . '/1', function ($id) {
						return new Response(200);
					}),
				]);
			}
		};
		$root = new Route('/', null, [
			'param' => new Route('/param', null, \iterator_to_array($generator())),
		]);
		$router = new Router($root);

		return $router;
	}

	/**
	 * @Iterations(20)
	 * @Revs(10)
	 * @Warmup(1)
	 */
	public function benchStaticNanRouterInsertIndex(): Router {
		$router = new Router();

		for ($x = 0; $x < 1000; $x++) {
			$router['/param/' . $x . '/1'] = function ($id) {
				return new Response(200);
			};
		}

		return $router;
	}

	/**
	 * @Iterations(20)
	 * @Revs(10)
	 * @Warmup(1)
	 */
	public function benchStaticNanRouterInsertArray(): Router {
		$routes = [
			':path' => '/',
			'param' => [
				':path' => '/param',
			],
		];

		for ($x = 0; $x < 1000; $x++) {
			$routes['param'][$x] = [
				':path' => '/param/' . $x,
				'1' => [
					':path' => '/param/' . $x . '/1',
					':handler' => function () {
						return new Response(200);
					},
				],
			];
		}

		return Router::fromArray($routes);
	}

	/**
	 * @Iterations(20)
	 * @Revs(10)
	 * @Warmup(1)
	 */
	public function benchParamNanRouterLookup() {
		$router = $this->benchParamNanRouterInsertManual();
		$request = new Request('GET', '/param/' . rand(0, 999) . '/1', getallheaders());
		$route = $router[$request->getUri()->getPath()];

		$route->matchesRequest($request);
	}

	/**
	 * @Iterations(20)
	 * @Revs(10)
	 * @Warmup(1)
	 */
	public function benchParamNanRoutesArrayLookup() {
		$routes = $this->benchParamNanRoutesArrayInsert();
		$request = new Request('GET', '/param/' . rand(0, 999) . '/1', getallheaders());

		foreach ($routes as $route) {
			if ($route->matchesRequest($request)) {
				return;
			}
		}
	}

	/**
	 * @Iterations(20)
	 * @Revs(10)
	 * @Warmup(1)
	 */
	public function benchStaticNanRouterLookup() {
		$router = $this->benchStaticNanRouterInsertManual();
		$request = new Request('GET', '/param/' . rand(0, 999) . '/1', getallheaders());
		$route = $router[$request->getUri()->getPath()];
		$route->matchesRequest($request);
	}

	/**
	 * @Iterations(20)
	 * @Revs(10)
	 * @Warmup(1)
	 */
	public function benchStaticNanRoutesArrayLookup() {
		$routes = $this->benchStaticNanRoutesArrayInsert();
		$request = new Request('GET', '/param/' . rand(0, 999) . '/1', getallheaders());
		$route = $routes[$request->getUri()->getPath()];

		$route->matchesRequest($request);
	}
}
