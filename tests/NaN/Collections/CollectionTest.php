<?php

use NaN\Collections\Collection;

describe('Collection', function () {
	test('Splat operator', function () {
		$collection = new Collection([1,2,3]);
		expect([...$collection])->toBe([1,2,3]);
	});
});
