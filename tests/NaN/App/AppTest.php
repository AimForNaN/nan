<?php

use NaN\{
	App\App,
	App\Router\Route,
	App\Router\Routes,
	DI\Container,
	DI\Definition,
	DI\Definitions,
	Http\Request,
	Http\Response};
use Psr\Http\{
	Message\ResponseInterface,
};

describe('App', function () {
	test('Route dependency injection', function () {
		$definitions = new Definitions([
			(new Definition(Response::class, [200]))->setAlias(ResponseInterface::class)->setShared(),
		]);
		$container = new Container($definitions);
		$routes = new Routes([
			Route::get('/{id}', function ($id, App $app) {
				return $app[ResponseInterface::class];
			}),
		]);
		$app = new App($container, $routes);

		expect($container->has(ResponseInterface::class))->toBeTrue();

		$rsp = $app->handle(new Request('GET', '/1'));
		expect($rsp)->toBeInstanceOf(Response::class);
	});
});
