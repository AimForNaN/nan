<?php

namespace NaN\Database;

use NaN\Database\Query\Statements\StatementInterface;

class Delegate implements DelegateInterface {
	public function __construct(
		private Database $db,
		private StatementInterface $statement,
	) {
	}

	public function exec(): \PDOStatement | false {
		return $this->db->execRaw(
			$this->statement->render(true),
			$this->statement->getBindings(),
		);
	}

	public function query(): \PDOStatement | false {
		return $this->db->queryRaw(
			$this->statement->render(),
		);
	}
}
