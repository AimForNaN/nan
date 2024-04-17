<?php

use NaN\App\Response;
use NaN\DI;
use Psr\Http\Message\ResponseInterface;

describe('Dependency Injection', function () {
	test('Basic parameters', function () {
		$handler = function ($id, $test) {
			expect($id)->toBeInt()->toBe(1);
			expect($test)->toBe('test');
		};

		DI::inject($handler, [
			'id' => 1,
			'test' => 'test',
		]);
	});
	test('Default parameters', function () {
		$handler = function (int $id = 1, bool $test = false) {
			expect($id)->toBeInt()->toBe(1);
			expect($test)->toBeFalse();
		};

		DI::inject($handler);
	});
	test('Typed parameters', function () {
		$handler = function (int $id, bool $test) {
			expect($id)->toBeInt()->toBe(1);
			expect($test)->toBeFalse();
		};

		DI::inject($handler, [
			'id' => '1',
			'test' => 'no',
		]);

		$handler = function (int $id, ResponseInterface $test) {
			expect($id)->toBeInt()->toBe(1);
			expect($test)->toBeInstanceOf(ResponseInterface::class);
		};

		DI::inject($handler, [
			'id' => '1',
			'test' => 'no',
		], function ($value, $type) {
			switch ($type) {
				case ResponseInterface::class:
					return new Response();
			}
		});
	});
	test('Variadic parameters', function () {})->skip();
});
