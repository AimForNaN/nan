<?php

use NaN\DI\{
	Container,
	Definition,
	Definitions,
};
use NaN\Http\{
	Request,
};

describe('Dependency Injection: Definition', function () {
	test('Resolve class', function () {
		$definition = new Definition(Request::class, ['POST', '/']);

		$request = $definition->resolve();
		expect($request)->toBeInstanceOf(Request::class);
		expect($request->getMethod())->toBe('POST');
		expect($request)->not()->toBe($definition->resolve());
	});

	test('Resolve closure', function () {
		$container = new Container(new Definitions([
			new Definition(Request::class, ['GET', '/']),
		]));
		$definition = new Definition(function () {
			expect(\func_get_args())->toHaveLength(0);
			expect($this)->toBeInstanceOf(Container::class);

			return new class {};
		}, [1]);

		expect($definition->resolve($container))->toBeObject();
	});

	test('Resolve concrete value', function () {
		$definition = new Definition(new Request('GET', '/'));

		$request = $definition->resolve();
		expect($request)->toBeInstanceOf(Request::class);
		expect($request)->toBe($definition->resolve());
	});

	test('Resolve single instance class', function () {
		$definition = new Definition(Request::class, ['GET', '/'], true);

		$request = $definition->resolve();
		expect($request)->toBeInstanceOf(Request::class);
		expect($request)->toBe($definition->resolve());
	});

	test('Resolve single instance closure', function () {
		$definition = new Definition(function () {
			return new Request('GET', '/');
		}, shared: true);

		$request = $definition->resolve();
		expect($request)->toBeInstanceOf(Request::class);
		expect($request)->toBe($definition->resolve());
	});
});
