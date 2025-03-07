<?php

use \NaN\App\TemplateEngine;
use NaN\DI\Container;
use NaN\DI\Container\Entry;
use NaN\Http\Response;

describe('Dependency Injection: Container', function () {
	test('Class resolution', function () {
		$container = new Container([
			Response::class => Response::class,
		]);
		$response = $container->get(Response::class);

		expect($response)->toBeinstanceOf(Response::class);
	});

	test('Closure resolution', function () {
		$container = new Container([
			Response::class => function () {
				expect(\func_get_args())->toHaveLength(0);
				expect($this)->toBeInstanceOf(Container::class);

				return new Response();
			},
		]);
		$response = $container->get(Response::class);

		expect($response)->toBeinstanceOf(Response::class);
	});

	test('Delegate', function () {
		$container = new Container();
		$delegate = new Container([
			TemplateEngine::class => TemplateEngine::class,
		]);

		$container->addDelegate($delegate);

		expect($container)->toHaveCount(1);
		expect($container->has(TemplateEngine::class))->toBeTrue();
		expect($container->get(TemplateEngine::class))->toBeinstanceOf(TemplateEngine::class);
	});

	test('Single instance resolution', function () {
		$container = new Container([
			Response::class => new Response(),
		]);
		$response = $container->get(Response::class);

		expect($container->has(Response::class))->toBeTrue();
		expect($response)->toBeinstanceOf(Response::class);
		expect($response)->toBe($container->get(Response::class));
	});
});
