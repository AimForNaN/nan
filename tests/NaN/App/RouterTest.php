<?php

use NaN\App\Router\{Route, Router};

describe('Router', function () {
	test('Adding routes (index)', function () {
		$routes = new Router();
		$routes['/nested/route'] = function () {};
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
