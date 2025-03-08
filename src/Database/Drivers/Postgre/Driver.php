<?php

namespace NaN\Database\Drivers\Postgre;

use NaN\Database\Drivers\Interfaces\DriverInterface;
use NaN\Database\Drivers\Traits\DriverTrait;
use NaN\Database\Query\Builder;
use NaN\Database\Query\Interfaces\BuilderInterface;

class Driver implements DriverInterface {
	use DriverTrait;

	public function __toString(): string {
		return $this->generateDsn('pgsql');
	}

	public function createQueryBuilder(): BuilderInterface {
		return new Builder();
	}
}
