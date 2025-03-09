<?php

use NaN\Database\Query\Statements\{
    Patch,
    Pull,
    Purge,
    Push,
};

describe('Statements', function () {
	test('Patch', function () {
		$patch = new Patch();

		$patch->patch('test')->with(['id' => 255]);
		expect($patch->render())->toBe('UPDATE test SET id = 255');
		expect($patch->render(true))->toBe('UPDATE test SET id = ?');
	});

	test('Pull', function () {
		$pull = new Pull();

		$pull->from('test');
		expect($pull->render())->toBe('SELECT * FROM test');

		$pull->pull(['id'])->from('test');
		expect($pull->render())->toBe('SELECT id FROM test');
	});

	test('Purge', function () {
		$purge = new Purge();

		$purge->from('test');
		expect($purge->render())->toBe('DELETE FROM test');
	});

	test('Push', function () {
		$push = new Push();

		$push->push(['id' => 255])->into('test');
		expect($push->render())->toBe('INSERT INTO test (id) VALUES (255)');
		expect($push->render(true))->toBe('INSERT INTO test (id) VALUES (?)');
	});
});
