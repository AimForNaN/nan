<?php

namespace NaN\Database\Query\Builders;

use NaN\Database\Query\Builders\Interfaces\SqlQueryBuilderInterface;
use NaN\Database\Query\Statements\{Patch, Pull, Purge, Push};
use NaN\Database\Query\Statements\Interfaces\StatementInterface;

class SqlQueryBuilder implements \ArrayAccess, SqlQueryBuilderInterface {
	public function __construct(
		protected \PDO $connection,
		protected array $options = [],
	) {
	}

	public function exec(StatementInterface $statement): mixed {
		if (!$statement->validate()) {
			\trigger_error('Mal-structured database query!', \E_USER_ERROR);
		}

		$bindings = $statement->getBindings();
		return $this->raw(
			$statement->render(!empty($bindings)),
			$bindings,
		);
	}

	public function getLastInsertId(): string | false {
		return $this->connection->lastInsertId();
	}

	public function offsetExists($offset): bool {
		return isset($this->options[$offset]);
	}

	public function offsetGet($offset): mixed {
		return $this->options[$offset] ?? null;
	}

	public function offsetSet($offset, $value): void {
		$this->options[$offset] = $value;
	}

	public function offsetUnset($offset): void {
	}

	public function patch(callable $fn): mixed {
		$patch = new Patch();

		$fn($patch);

		return $this->exec($patch);
	}

	public function pull(callable $fn): mixed {
		$pull = new Pull();

		$fn($pull);

		return $this->exec($pull);
	}

	public function purge(callable $fn): mixed {
		$purge = new Purge();

		$fn($purge);

		return $this->exec($purge);
	}

	public function push(callable $fn): mixed {
		$push = new Push();

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