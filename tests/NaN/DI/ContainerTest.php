<?php

use NaN\DI\{
	Container,
	Definition,
	Definitions,
	Exceptions\NotFoundException,
};
use NaN\Env;
use NaN\Http\Request;

describe('Dependency Injection: Container', function () {
	test('Basic resolution', function () {
		$container = new Container(new Definitions([
			(new Definition(1))->setAlias('one'),
			(new Definition('test'))->setAlias('test'),
		]));

		$one = $container->get('one');
		expect($one)->toBe(1);

		$test = $container->get('test');
		expect($test)->toBe('test');
	});

	test('Class resolution', function () {
		$container = new Container(new Definitions([
			new Definition(Request::class, ['GET', '/']),
		]));

		$request = $container->get(Request::class);
		expect($request)->toBeinstanceOf(Request::class);
		expect($request->getMethod())->toBe('GET');
		expect($request->getUri()->getPath())->toBe('/');
	});

	test('Delegate', function () {
		$container = new Container(new Definitions());
		$delegate = new Container(new Definitions([
			new Definition(Env::class, ['.']),
		]));

		$container->addDelegate($delegate);

		expect($container->has(Env::class))->toBeTrue();
		expect($container->get(Env::class))->toBeinstanceOf(Env::class);
	});

	test('Not found exception', function () {
		$container = new Container(new Definitions());
		$delegate = new Container(new Definitions([
			new Definition(Request::class, ['GET', '/']),
		]));

		$container->addDelegate($delegate);

		expect($container->has(Env::class))->toBeFalse();
		$container->get(Env::class);
	})->throws(NotFoundException::class);
});
