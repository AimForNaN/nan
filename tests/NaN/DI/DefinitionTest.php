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
	test('Resolution closure with arguments', function () {
		$container = new Container(new Definitions([
			new Definition(Request::class, ['GET', '/']),
		]));
		$definition = new Definition(function (int $id, Request $req) {
			expect($id)->toBe(1);
			return $req->getMethod() === 'GET';
		}, [1, Request::class]);

		expect($definition->resolve($container))->toBeTrue();
	});
});
