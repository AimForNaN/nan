<?php

use NaN\Database\Database;
use NaN\Database\Drivers\Sqlite\Driver;

describe('Database', function () {
	test('Basic queries', function () {
		$db = new Database(new Driver());

		expect($db->query('CREATE TABLE `test` (`id` int);'))->toBeTruthy();

		$result = $db->query('SELECT `name` FROM `sqlite_master` WHERE type="table" AND name="test";');
		expect($result)->toBeInstanceOf(\PDOStatement::class);

		$result = [...$result];
		expect($result)->toHaveCount(1);
	});
});
