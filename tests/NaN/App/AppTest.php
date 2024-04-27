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
	Env,
};
use Psr\Http\{
	Message\ResponseInterface,
	Message\ServerRequestInterface,
};

describe('App', function () {
	test('Route dependency injection', function () {
		$container = new Container(new Definitions([
			(new Definition(Env::class, ['.']))->setShared(),
			(new Definition(Request::class, ['GET', '/']))->setShared(),
			(new Definition(Response::class, ['GET', '/']))->setShared(),
		]));
		$routes = new Routes([
			Route::get('/', function (Env $env, ServerRequestInterface $req, ResponseInterface $rsp) {
				return $rsp;
			}),
		]);
		$app = new App($container, $routes);

		expect($container->has(Env::class))->toBeTrue();
		expect($container->has(Request::class))->toBeTrue();
		expect($container->has(Response::class))->toBeTrue();

		$rsp = $app->handle($container->get(Request::class));
		expect($rsp)->toBeInstanceOf(Response::class);
	});
});
