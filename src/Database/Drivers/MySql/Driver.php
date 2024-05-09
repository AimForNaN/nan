<?php

namespace NaN\Database\Drivers\MySql;

use NaN\Database\{
	Drivers\DriverInterface,
	Drivers\DriverPdoConfigTrait,
	Query\BuilderInterface,
	Query\Builder,
};

class Driver implements DriverInterface, \ArrayAccess {
	use DriverPdoConfigTrait;

	public function __toString(): string {
		return $this->generateDsn('mysql');
	}

	public function createQueryBuilder(): BuilderInterface {
		return new Builder();
	}
}
