<?php

use NaN\DI\Container;
use NaN\DI\Container\Entry;
use NaN\Http\{
	Request,
};

describe('Dependency Injection: Definition', function () {
	test('Resolve class', function () {
		$definition = new Entry(Request::class, ['POST', '/']);

		$request = $definition->resolve();
		expect($request)->toBeInstanceOf(Request::class);
		expect($request->getMethod())->toBe('POST');
		expect($request)->not()->toBe($definition->resolve());
	});

	test('Resolve closure', function () {
		$container = new Container([
			new Entry(Request::class, ['GET', '/']),
		]);
		$definition = new Entry(function () {
			expect(\func_get_args())->toHaveLength(0);
			expect($this)->toBeInstanceOf(Container::class);

			return new class {};
		}, [1]);

		$anon = $definition->resolve($container);
		expect($anon)->toBeObject();
		expect($anon)->not()->toBe($definition->resolve($container));
	});

	test('Resolve concrete value', function () {
		$definition = new Entry(new Request('GET', '/'));

		$request = $definition->resolve();
		expect($request)->toBeInstanceOf(Request::class);
		expect($request)->toBe($definition->resolve());
	});

	test('Resolve single instance class', function () {
		$definition = new Entry(Request::class, ['GET', '/'], true);

		$request = $definition->resolve();
		expect($request)->toBeInstanceOf(Request::class);
		expect($request)->toBe($definition->resolve());
	});

	test('Resolve single instance closure', function () {
		$definition = new Entry(function () {
			return new Request('GET', '/');
		}, shared: true);

		$request = $definition->resolve();
		expect($request)->toBeInstanceOf(Request::class);
		expect($request)->toBe($definition->resolve());
	});
});
