<?php

namespace NaN\Database\Query\Builders;

use NaN\Database\Query\Builders\Interfaces\SqlQueryBuilderInterface;
use NaN\Database\Query\Statements\{Patch, Pull, Purge, Push};
use NaN\Database\Query\Statements\Interfaces\StatementInterface;

class SqlQueryBuilder implements SqlQueryBuilderInterface {
	public function __construct(
		protected \PDO $connection,
		protected string $table,
		protected ?string $database = null,
	) {
	}

	protected function exec(StatementInterface $statement): \PDOStatement | false {
		$bindings = $statement->getBindings();
		return $this->raw(
			$statement->render(!empty($bindings)),
			$bindings,
		);
	}

	public function getLastInsertId(): string | false {
		return $this->connection->lastInsertId();
	}

	public function patch(callable $fn): mixed {
		$patch = new Patch($this->table, $this->database);

		$fn($patch);

		return $this->exec($patch);
	}

	public function pull(callable $fn): mixed {
		$pull = new Pull($this->table, $this->database);

		$fn($pull);

		return $this->exec($pull);
	}

	public function purge(callable $fn): mixed {
		$purge = new Purge($this->table, $this->database);

		$fn($purge);

		return $this->exec($purge);
	}

	public function push(callable $fn): mixed {
		$push = new Push($this->table, $this->database);

		$fn($push);

		return $this->exec($push);
	}

	public function raw(string $query, array $bindings = []): mixed {
		$db = $this->connection;

		if (empty($bindings)) {
			return $db->query($query);
		}

		$stmt = $db->prepare($query);

		if ($stmt instanceof \PDOStatement) {
			if (!$stmt->execute($bindings)) {
				return false;
			}

			return $stmt;
		}

		return false;
	}

	public function transact(callable $fn): bool {
		$db = $this->connection;

		try {
			$db->beginTransaction();
			$fn($this);
			return $db->commit();
		} catch (\Throwable) {
			$db->rollBack();
		}

		return false;
	}
}