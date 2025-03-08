<?php

namespace NaN\Database\Drivers\Sqlite;

use NaN\Database\Drivers\Interfaces\DriverInterface;
use NaN\Database\Drivers\Traits\DriverTrait;
use NaN\Database\Query\Builder;
use NaN\Database\Query\Interfaces\BuilderInterface;

class Driver implements DriverInterface {
	use DriverTrait;

	public function __toString(): string {
		return \sprintf('sqlite:%s', $this->config['driver']);
	}

	public function createQueryBuilder(): BuilderInterface {
		return new Builder();
	}

	public function openConnection() {
		$this->connection = new \PDO((string)$this);
	}
}
