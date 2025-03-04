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
	});

	test('Resolve closure', function () {
		$container = new Container(new Definitions([
			new Definition(Request::class, ['GET', '/']),
		]));
		$definition = new Definition(function () {
			expect(\func_get_args())->toHaveLength(0);
			expect($this)->toBeInstanceOf(Container::class);

			return new class {};
		}, [1, Request::class]);

		expect($definition->resolve($container))->toBeObject();
	});
});
