<?php

use NaN\App\App;
use NaN\App\Router\Router;
use NaN\Http\Request;
use Psr\Http\Message\{
	ResponseInterface as PsrResponseInterface,
};

describe('App', function () {
	test('Route dependency injection (closure)', function () {
		$routes = new Router();
		$routes['/1'] = function (PsrResponseInterface $response) {
			return $response;
		};

		$app = new App();
		$app->use($routes);

		$rsp = $app->handle(new Request('GET', '/1'));
		expect($rsp)->toBeInstanceOf(PsrResponseInterface::class);
		expect($rsp->getStatusCode())->toBe(200);
	});

	test('Route param injection (closure)', function () {
		$routes = new Router();
		$routes['/{id}'] = function ($id, PsrResponseInterface $response) {
			expect($id)->toBe('1');
			return $response;
		};

		$app = new App();
		$app->use($routes);

		$rsp = $app->handle(new Request('GET', '/1'));
		expect($rsp)->toBeInstanceOf(PsrResponseInterface::class);
		expect($rsp->getStatusCode())->toBe(200);
	});
});
