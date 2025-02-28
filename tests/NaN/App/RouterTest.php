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
		$root = new Route('/');
		$nested_route = new Route('/nested');
		$nested_route_route = new Route('/nested/route', function () {});

		$root->insert('nested', $nested_route);
		$nested_route->insert('route', $nested_route_route);

		$routes = new Router($root);
		$route = $routes['/nested/route'];

		expect($route)->toBeInstanceOf(Route::class);
	});

	test('Adding routes (array)', function () {
		$routes = Router::fromArray([
			':handler' => null,
			':path' => '/',
			'nested' => [
				':handler' => null,
				':path' => '/nested',
				'route' => [
					':handler' => function () {},
					':path' => '/nested/route',
				],
			],
		]);
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
