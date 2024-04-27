<?php

use Monolog\{
	Handler\ErrorLogHandler,
	Logger,
};
use NaN\{
	App\Request,
	DI\Container,
	DI\Definition,
	DI\Definitions,
};

describe('Dependency Injection: Definition', function () {
	test('Resolution with arguments', function () {
		$container = new Container(new Definitions([
			new Definition(Request::class, ['GET', '/']),
		]));
		$definition = new Definition(function ($id, Request $req) {
			expect($id)->toBe(1);
			return $req->getMethod() === 'GET';
		}, [1, Request::class]);

		expect($definition->resolve($container))->toBeTrue();
	});

	test('Method calls', function () {
		$container = new Container(new Definitions([
			(new Definition(Logger::class, ['dev_log']))->setAlias('logger')->addMethodCall('pushHandler', [ErrorLogHandler::class])->setShared(),
			(new Definition(ErrorLogHandler::class)),
		]));

		expect($container->get('logger'))->toBeInstanceOf(Logger::class);
	});
});
