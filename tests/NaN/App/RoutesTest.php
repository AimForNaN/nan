<?php

use NaN\App\Router\{Route, Router};

describe('Routes', function () {
	test('Adding routes', function () {
		$routes = new Router();
		$routes['/'] = function () {};
		$route = $routes['/'];

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
