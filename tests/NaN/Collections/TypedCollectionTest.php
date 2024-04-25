<?php

use NaN\Collections\TypedCollection;

describe('TypedCollection', function () {
	test('Instantiation', function () {
		$collection = new TypedCollection([1, 2, 3, 'test'], 'is_int');
		expect($collection)->toHaveLength(3);
		expect([...$collection])->toBe([1,2,3]);
	});
});
