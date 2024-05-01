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
		$container = new Container(new Definitions([
			(new Definition(Request::class, ['GET', '/']))->setShared(),
			(new Definition(Response::class, [200]))->setAlias(ResponseInterface::class)->setShared(),
		]));
		$routes = new Routes([
			Route::get('/', function (App $app) {
				return $app[ResponseInterface::class];
			}),
		]);
		$app = new App($container, $routes);

		expect($container->has(Request::class))->toBeTrue();
		expect($container->has(Response::class))->toBeTrue();

		$rsp = $app->handle($container->get(Request::class));
		expect($rsp)->toBeInstanceOf(Response::class);
	});
});
