<?php

use NaN\App\Router\{
    Route,
	Routes,
};
use NaN\Http\{
	Request,
	Response,
};

class RouterBench {
	/**
	 * @Iterations(20)
	 * @Revs(10)
	 */
	public function benchParam() {
		$routes = new Routes();
		$request = new Request('GET', '/param/' . rand(0, 999) . '/1', getallheaders());

		for ($x = 0; $x < 1000; $x++) { 
			$routes[] = Route::get('/param/' . $x . '/{id}', function ($id) {
				return new Response(200);
			});
		}

		foreach ($routes->getByMethod($request->getMethod()) as $route) {
			if ($route->matches($request)) {
				return;
			}
		}
	}
	/**
	 * @Iterations(20)
	 * @Revs(10)
	 */
	public function benchParamArray() {
		$routes = [];
		$request = new Request('GET', '/param/' . rand(0, 999) . '/1', getallheaders());

		for ($x = 0; $x < 1000; $x++) { 
			$routes[] = Route::get('/param/' . $x . '/{id}', function ($id) {
				return new Response(200);
			});
		}

		foreach ($routes as $route) {
			if ($route->matches($request)) {
				return;
			}
		}
	}

	/**
	 * @Iterations(20)
	 * @Revs(10)
	 */
	public function benchStatic() {
		$routes = new Routes();
		$request = new Request('GET', '/param/' . rand(0, 999) . '/1', getallheaders());

		for ($x = 0; $x < 1000; $x++) { 
			$routes[] = Route::get('/param/' . $x . '/1', function () {
				return new Response(200);
			});
		}

		foreach ($routes->getByMethod($request->getMethod()) as $route) {
			if ($route->matches($request)) {
				return;
			}
		}
	}

	/**
	 * @Iterations(20)
	 * @Revs(10)
	 */
	public function benchStaticArray() {
		$routes = [];
		$request = new Request('GET', '/param/' . rand(0, 999) . '/1', getallheaders());

		for ($x = 0; $x < 1000; $x++) { 
			$routes[] = Route::get('/param/' . $x . '/1', function () {
				return new Response(200);
			});
		}

		foreach ($routes as $route) {
			if ($route->matches($request)) {
				return;
			}
		}
	}
}
