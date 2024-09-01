<?php

namespace NaN\Database\Drivers\Sqlite;

use NaN\Database\{
	Drivers\DriverInterface,
	Drivers\DriverPdoTrait,
	Query\Builder,
	Query\BuilderInterface,
};

class Driver implements DriverInterface, \ArrayAccess {
	use DriverPdoTrait;

	public function __construct(
		private string $db = ':memory:',
	) {
	}

	public function __toString(): string {
		return \sprintf('sqlite:%s', $this->db);
	}

	public function createQueryBuilder(): BuilderInterface {
		return new Builder();
	}
}
