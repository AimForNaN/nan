<?php

use NaN\App\Middleware\Router;
use NaN\App\Middleware\Router\Route;

describe('Router', function () {
	test('Adding routes (index)', function () {
		$routes = new Router();
		$routes['/nested/route'] = function () {};
		$route = $routes['/nested/route'];

		expect($route)->toBeInstanceOf(Route::class);
	});

	test('Adding routes (manually)', function () {
		$root = new Route('/', null, [
			'nested' => new Route('/nested', null, [
				'route' => new Route('/nested/route', function () {}),
			]),
		]);

		$routes = new Router($root);
		$route = $routes['/nested/route'];

		expect($route)->toBeInstanceOf(Route::class);
	});

	/**
	 * @todo
	 */
	test('Get named route', function () {
		$routes = new Router();
		$get = $routes->matchName('home');

		expect($get)->toBeInstanceOf(Route::class);
	})->skip();
});
