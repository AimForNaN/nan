<?php

use \NaN\App\TemplateEngine;
use NaN\DI\Container;
use NaN\DI\Container\Entry;
use NaN\Http\Response;

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
			Response::class => new Entry(Response::class),
		]);
		$response = $container->get(Response::class);

		expect($response)->toBeinstanceOf(Response::class);
	});

	test('Closure resolution', function () {
		$container = new Container([
			Response::class => new Entry(function () {
				expect(\func_get_args())->toHaveLength(0);
				expect($this)->toBeInstanceOf(Container::class);
				
				return new Response();
			}),
		]);
		$response = $container->get(Response::class);

		expect($response)->toBeinstanceOf(Response::class);
	});

	test('Concrete resolution', function () {
		$container = new Container([
			Response::class => new Entry(new Response()),
		]);
		$response = $container->get(Response::class);

		expect($container->has(Response::class))->toBeTrue();
		expect($response)->toBeinstanceOf(Response::class);
		expect($response)->toBe($container->get(Response::class));
	});

	test('Delegate', function () {
		$container = new Container();
		$delegate = new Container([
			TemplateEngine::class => new Entry(TemplateEngine::class),
		]);

		$container->addDelegate($delegate);

		expect($container)->toHaveCount(1);
		expect($container->has(TemplateEngine::class))->toBeTrue();
		expect($container->get(TemplateEngine::class))->toBeinstanceOf(TemplateEngine::class);
	});
});
