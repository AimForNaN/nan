<?php

use NaN\App\Request;
use NaN\App\RoutePattern;

describe('Route patterns', function () {
	test('Basic matches', function () {
		$pattern = new RoutePattern('/');
		expect($pattern->compile())->toBe('#^/$#i');

		$pattern = new RoutePattern('/test');
		expect($pattern->compile());
		expect($pattern->compile())->toBe('#^/test$#i');
	});

	test('Variable matches', function () {
		$request = new Request('GET', '/test/1/');
		$pattern = new RoutePattern('/test/{id}/');
		expect($pattern->compile())->toBe('#^/test/(?P<id>[^/]+)/$#i');
		expect($pattern->match($request))->toBeTrue();
		expect($pattern->getGroups())->toBe([
			'id',
		]);
		expect($pattern->getMatches())->toBe([
			'id' => '1',
		]);

		$request = new Request('GET', '/page-1/123');
		$pattern = new RoutePattern('/page-{page}/{id}');
		expect($pattern->compile())->toBe('#^/page-(?P<page>[^/]+)/(?P<id>[^/]+)$#i');
		expect($pattern->match($request))->toBeTrue();
		expect($pattern->getGroups())->toBe([
			'page',
			'id',
		]);
		expect($pattern->getMatches())->toBe([
			'page' => '1',
			'id' => '123',
		]);
	});
});
