<?php

namespace NaN\Database\Drivers;

use NaN\Database\Query\BuilderInterface;

/**
 * It's best to instantiate without a database name and let the system
 *  decide how to handle selecting databases according to each model.
 */
interface DriverInterface extends \Stringable {
	public function closeConnection();

	public function createQueryBuilder(): BuilderInterface;

	public function exec(callable $fn): mixed;

	public function openConnection();
}
