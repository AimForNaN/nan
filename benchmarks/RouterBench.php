<?php

use NaN\App\Router\{
    Route,
	Router,
};
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
		$root = new Route();
		$router = new Router($root);

		$param_route = new Route();
		$root['param'] = $param_route;

		for ($x = 0; $x < 1000; $x++) {
			$route = new Route();
			$param_route[$x] = $route;
			$route['{id}'] = new Route(null, function ($id) {
				return new Response(200);
			});
		}

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
			$routes[] = new Route('/param/' . $x . '/1', function ($id) {
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
		$root = new Route();
		$router = new Router($root);

		$param_route = new Route();
		$root['param'] = $param_route;

		for ($x = 0; $x < 1000; $x++) {
			$route = new Route();
			$param_route[$x] = $route;
			$route['1'] = new Route(null, function ($id) {
				return new Response(200);
			});
		}

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
		return $router[$request->getUri()->getPath()];
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
		return $router[$request->getUri()->getPath()];
	}

	/**
	 * @Iterations(20)
	 * @Revs(10)
	 * @Warmup(1)
	 */
	public function benchStaticNanRoutesArrayLookup() {
		$routes = $this->benchStaticNanRoutesArrayInsert();
		$request = new Request('GET', '/param/' . rand(0, 999) . '/1', getallheaders());

		foreach ($routes as $route) {
			if ($route->matchesRequest($request)) {
				return;
			}
		}
	}
}
