<?php

use NaN\{
	App\App,
	App\Request,
	App\Response,
	App\Route,
	App\Routes,
	DI\Container,
	DI\Definition,
	DI\Definitions,
};
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
			Route::get('/', function (App $app) {
				return $app[ResponseInterface::class];
			}),
		]);
		$app = new App($container, $routes);

		expect($container->has(ResponseInterface::class))->toBeTrue();

		$rsp = $app->handle(new Request('GET', '/'));
		expect($rsp)->toBeInstanceOf(Response::class);
	});
});
