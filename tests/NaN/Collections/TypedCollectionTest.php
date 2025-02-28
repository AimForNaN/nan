<?php

use NaN\Collections\TypedCollection;

describe('TypedCollection', function () {
	test('Instantiation', function () {
		$collection = new class ([1, 2, 3, 'test']) extends TypedCollection {
			protected mixed $type = 'is_int';
		};
		expect($collection)->toHaveLength(3);
		expect([...$collection])->toBe([1,2,3]);
	});
});
