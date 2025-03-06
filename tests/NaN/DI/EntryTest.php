<?php

use NaN\DI\Container;
use NaN\DI\Container\Entry;
use NaN\Http\{
	Request,
	Response,
};

describe('Dependency Injection: Definition', function () {
	test('Resolve class', function () {
		$entry = new Entry(Response::class);
		$response = $entry->resolve();

		expect($response)->toBeInstanceOf(Response::class);
		expect($response)->not()->toBe($entry->resolve());
	});

	test('Resolve closure', function () {
		$container = new Container([
			Request::class => new Entry(Request::class),
		]);
		$entry = new Entry(function () {
			expect(\func_get_args())->toHaveLength(0);
			expect($this)->toBeInstanceOf(Container::class);

			return new class {};
		});
		$anon = $entry->resolve($container);

		expect($anon)->toBeObject();
		expect($anon)->not()->toBe($entry->resolve($container));
	});

	test('Resolve concrete value', function () {
		$entry = new Entry(new Request('GET', '/'));
		$request = $entry->resolve();

		expect($request)->toBeInstanceOf(Request::class);
		expect($request)->toBe($entry->resolve());
	});

	test('Resolve single instance class', function () {
		$entry = new Entry(Response::class, true);
		$response = $entry->resolve();

		expect($response)->toBeInstanceOf(Response::class);
		expect($response)->toBe($entry->resolve());
	});

	test('Resolve single instance closure', function () {
		$entry = new Entry(function () {
			return new Request('GET', '/');
		}, true);
		$request = $entry->resolve();

		expect($request)->toBeInstanceOf(Request::class);
		expect($request)->toBe($entry->resolve());
	});
});
