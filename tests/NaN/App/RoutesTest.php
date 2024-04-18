<?php

use NaN\App\{
	Route,
	Routes,
};

describe('Routes', function () {
	test('Adding routes', function () {
		$routes = new Routes();
		$routes[] = new Route('GET', '/', function () {});
		expect($routes)->toHaveLength(1);
	});

	test('Adding non-route', function () {
		$routes = new Routes();
		$routes[] = ['GET', '/', function () {}];
	})->throws(Exception::class);

	test('Get routes', function () {
		$routes = new Routes();
		$routes[] = new Route('GET', '/', function () {});

		$get = $routes['GET'];
		expect($get)->toHaveLength(1);
	});
});
