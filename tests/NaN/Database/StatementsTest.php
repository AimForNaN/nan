<?php

use NaN\Database\Query\Statements\{
	Pull,
	Push,
};

describe('Statements', function () {
	test('Pull', function () {
		$pull = new Pull();
		$pull->select(['id'])->from('test')->whereEquals('id', 255);

		expect($pull->render())->toBe('SELECT id FROM test WHERE id = 255');
		expect($pull->render(true))->toBe('SELECT id FROM test WHERE id = ?');

		$pull->whereGreaterThan('id', 255);
		expect($pull->render())->toBe('SELECT id FROM test WHERE id > 255');
		expect($pull->render(true))->toBe('SELECT id FROM test WHERE id > ?');

		$pull->whereGreaterThanEquals('id', 255);
		expect($pull->render())->toBe('SELECT id FROM test WHERE id >= 255');
		expect($pull->render(true))->toBe('SELECT id FROM test WHERE id >= ?');

		$pull->whereIn('id', [255]);
		expect($pull->render())->toBe('SELECT id FROM test WHERE id IN (255)');
		expect($pull->render(true))->toBe('SELECT id FROM test WHERE id IN (?)');

		$pull->whereLessThan('id', 255);
		expect($pull->render())->toBe('SELECT id FROM test WHERE id < 255');
		expect($pull->render(true))->toBe('SELECT id FROM test WHERE id < ?');

		$pull->whereLessThanEquals('id', 255);
		expect($pull->render())->toBe('SELECT id FROM test WHERE id <= 255');
		expect($pull->render(true))->toBe('SELECT id FROM test WHERE id <= ?');
	});

	test('Push', function () {
		$push = new Push();
		$push->push(['id' => 255])->into('test');

		expect($push->render())->toBe('INSERT INTO test (id) VALUES (255)');
		expect($push->render(true))->toBe('INSERT INTO test (id) VALUES (?)');

		$push->whereEquals('id', 255);
		expect($push->render())->toBe('UPDATE test SET (id = 255) WHERE id = 255');
		expect($push->render(true))->toBe('UPDATE test SET (id = ?) WHERE id = ?');

		$push->whereGreaterThan('id', 255);
		expect($push->render())->toBe('UPDATE test SET (id = 255) WHERE id > 255');
		expect($push->render(true))->toBe('UPDATE test SET (id = ?) WHERE id > ?');

		$push->whereGreaterThanEquals('id', 255);
		expect($push->render())->toBe('UPDATE test SET (id = 255) WHERE id >= 255');
		expect($push->render(true))->toBe('UPDATE test SET (id = ?) WHERE id >= ?');

		$push->whereIn('id', [255]);
		expect($push->render())->toBe('UPDATE test SET (id = 255) WHERE id IN (255)');
		expect($push->render(true))->toBe('UPDATE test SET (id = ?) WHERE id IN (?)');

		$push->whereLessThan('id', 255);
		expect($push->render())->toBe('UPDATE test SET (id = 255) WHERE id < 255');
		expect($push->render(true))->toBe('UPDATE test SET (id = ?) WHERE id < ?');

		$push->whereLessThanEquals('id', 255);
		expect($push->render())->toBe('UPDATE test SET (id = 255) WHERE id <= 255');
		expect($push->render(true))->toBe('UPDATE test SET (id = ?) WHERE id <= ?');
	});
});
