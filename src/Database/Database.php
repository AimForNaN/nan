<?php

namespace NaN\Database;

class Database implements DatabaseInterface {
	public function __construct(
		private Drivers\DriverInterface $driver,
	) {
		$driver->openConnection();
	}

	public function exec($statement): \PDOStatement | false {
		$bindings = $statement->getBindings();
		return $this->raw(
			$statement->render(!empty($bindings)),
			$bindings,
		);
	}

	public function getLastInsertId(): string | false {
		return $this->driver->exec(fn(\PDO $db) => $db->lastInsertId());
	}

	public function patch(callable $fn): \PDOStatement | false {
		$builder = $this->driver->createQueryBuilder();
		$query = $builder->createPatch();

		$fn($query);

		return $this->exec($query);
	}

	public function pull(callable $fn): \PDOStatement | false {
		$builder = $this->driver->createQueryBuilder();
		$query = $builder->createPull();

		$fn($query);

		return $this->exec($query);
	}

	public function purge(callable $fn): \PDOStatement | false {
		$builder = $this->driver->createQueryBuilder();
		$query = $builder->createPurge();

		$fn($query);

		return $this->exec($query);
	}

	public function push(callable $fn): \PDOStatement | false {
		$builder = $this->driver->createQueryBuilder();
		$query = $builder->createPush();

		$fn($query);

		return $this->exec($query);
	}

	public function raw(string $query, array $bindings = []): \PDOStatement | false {
		return $this->driver->exec(function (\PDO $db) use ($query, $bindings) {
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
