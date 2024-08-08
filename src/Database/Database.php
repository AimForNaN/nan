<?php

namespace NaN\Database;

class Database implements DatabaseInterface {
	public function __construct(
		private Drivers\DriverInterface $driver,
	) {
		$driver->openConnection();
	}

	public function execRaw(string $query, array $bindings): \PDOStatement | false {
		if (empty($bindings)) {
			throw new \InvalidArgumentException('Cannot provide empty bindings! Run query() instead if not using prepared statements!');
		}

		return $this->driver->exec(function (\PDO $db) use ($query, $bindings) {
			$stmt = $db->prepare($query);

			if ($stmt instanceof \PDOStatement) {
				$stmt->execute($bindings);
				// $stmt->fetchAll();
				return $stmt;
			}

			return false;
		});
	}

	public function getLastInsertId(): string | false {
		return $this->driver->exec(fn(\PDO $db) => $db->lastInsertId());
	}

	public function patch(callable $fn): DelegateInterface {
		$builder = $this->driver->createQueryBuilder();
		$query = $builder->createPatch();
		$fn($query);
		return new Delegate($this, $query);
	}

	public function pull(callable $fn): Delegate {
		$builder = $this->driver->createQueryBuilder();
		$query = $builder->createPull();
		$fn($query);
		return new Delegate($this, $query);
	}

	public function purge(callable $fn): DelegateInterface {
		$builder = $this->driver->createQueryBuilder();
		$query = $builder->createPurge();
		$fn($query);
		return new Delegate($this, $query);
	}

	public function push(callable $fn): DelegateInterface {
		$builder = $this->driver->createQueryBuilder();
		$query = $builder->createPush();
		$fn($query);
		return new Delegate($this, $query);
	}

	public function queryRaw(string $query): \PDOStatement | false {
		return $this->driver->exec(function (\PDO $db) use ($query) {
			return $db->query($query);
		});
	}

	public function transact(callable $fn): bool {
		return $this->driver->exec(function (\PDO $db) use ($fn) {
			try {
				$db->beginTransaction();
				$fn($this);
				return $db->commit();
			} catch (\Throwable) {
				$db->rollBack();
			}

			return false;
		});
	}
}
