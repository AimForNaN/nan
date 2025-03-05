<?php

use \NaN\App\TemplateEngine;
use NaN\DI\{
	Container,
};
use NaN\DI\Container\Entry;
use NaN\Http\{
	Request,
	Response,
};

describe('Dependency Injection: Container', function () {
	test('Basic resolution', function () {
		$container = new Container([
			'one' => new Entry(1),
			'test' => new Entry('test'),
		]);

		$one = $container->get('one');
		expect($one)->toBe(1);

		$test = $container->get('test');
		expect($test)->toBe('test');
	});

	test('Class resolution', function () {
		$container = new Container([
			new Entry(Request::class, ['GET', '/']),
		]);

		$request = $container->get(Request::class);
		expect($request)->toBeinstanceOf(Request::class);
		expect($request->getMethod())->toBe('GET');
		expect($request->getUri()->getPath())->toBe('/');
	});

	test('Concrete resolution', function () {
		$response = new Response();
		$container = new Container([
			new Entry($response),
		]);

		expect($container->has(Response::class))->toBeTrue();
		$response = $container->get(Response::class);
		expect($response)->toBeinstanceOf(Response::class);
		expect($response)->toBe($container->get(Response::class));
	});

	test('Delegate', function () {
		$container = new Container();
		$delegate = new Container([
			new Entry(TemplateEngine::class),
		]);

		$container->addDelegate($delegate);

		expect($container->has(TemplateEngine::class))->toBeTrue();
		expect($container->get(TemplateEngine::class))->toBeinstanceOf(TemplateEngine::class);
	});
});
