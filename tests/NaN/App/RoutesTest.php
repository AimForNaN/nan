<?php

use NaN\App\Router\{
	Route,
	Routes,
};

describe('Routes', function () {
	test('Adding routes', function () {
		$routes = new Routes([
			new Route('GET', '/', function () {}),
		]);
		expect($routes)->toHaveLength(1);
	});

	test('Adding non-route', function () {
		$routes = new Routes([
			['GET', '/', function () {}],
		]);
		expect($routes)->toHaveLength(0);
	});

	test('Get routes', function () {
		$routes = new Routes([
			new Route('GET', '/', function () {}),
		]);

		$get = $routes->getByMethod('GET');
		expect($get)->toHaveLength(1);
	});

	test('Get named route', function () {
		$routes = new Routes([
			'home' => new Route('GET', '/', function () {}),
		]);

		$get = $routes['home'];
		expect($get)->toBeInstanceOf(Route::class);
	});
});
