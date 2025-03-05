<?php

use NaN\Collections\Collection;

describe('Collection', function () {
	test('Append', function () {
		$collection = new Collection();
		$collection[] = 1;
		$collection[] = 2;
		expect($collection)->toHaveCount(2);
	});

	test('Count', function () {
		$collection = new Collection([1,2,3]);
		expect($collection)->toHaveCount(3);
	});

	test('Map', function () {
		$collection = new Collection([1,2,3]);
		$mappings = $collection->map(fn($item) => (string)$item);
		expect($mappings)->toHaveCount(3);
		expect($mappings)->toBe(['1','2','3']);
	});

	test('Splat operator', function () {
		$collection = new Collection([1,2,3]);
		expect([...$collection])->toBe([1,2,3]);
	});
});
