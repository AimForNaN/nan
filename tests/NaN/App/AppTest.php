<?php

use NaN\App\App;
use NaN\App\Router\{
	Route,
	Router,
};
use NaN\DI\{
	Container,
	Definition,
	Definitions,
};
use NaN\Http\{
	Request,
	Response,
};
use Psr\Http\{
	Message\ResponseInterface,
};

describe('App', function () {
	test('Route dependency injection', function () {
		$routes = new Router([
			Route::get('/{id}', function ($id, App $app) {
				return $app[ResponseInterface::class];
			}),
		]);
		$definitions = new Definitions([
			(new Definition(Response::class, [200]))->setAlias(ResponseInterface::class)->setShared(),
			(new Definition($routes))->setAlias('router')->setShared(),
		]);
		$container = new Container($definitions);
		$app = new App($container);

		expect($container->has(ResponseInterface::class))->toBeTrue();

		$rsp = $app->handle(new Request('GET', '/1'));
		expect($rsp)->toBeInstanceOf(Response::class);
	});
});
