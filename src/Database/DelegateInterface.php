<?php

namespace NaN\Database;

interface DelegateInterface {
	public function exec(): \PDOStatement | false;

	public function query(): \PDOStatement | false;
}
