<?php

namespace NaN\Database;

use NaN\Database\Query\Statements\{
	Clauses\FromClause,
	Clauses\SelectClause,
	PullInterface,
	StatementInterface,
};

class Database implements DatabaseInterface {
	public function __construct(
		private Drivers\DriverInterface $driver,
	) {
		$driver->openConnection();
	}

	protected function applyClassAttributes(StatementInterface $statement, string $class) {
		if ($statement instanceof PullInterface) {
			$statement->from(function (FromClause $from) use ($class) {
				$from->addTableFromClass($class);
			});
		}
	}

	public function exec(string $query, array $bindings): \PDOStatement | false {
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

	public function patch(string $class, callable $fn): DelegateInterface {
		$builder = $this->driver->createQueryBuilder();
		$query = $builder->createPatch();

		$this->applyClassAttributes($query, $class);
		$fn($query);

		return new Delegate($this, $query);
	}

	public function pull(string $class, callable $fn): Delegate {
		$builder = $this->driver->createQueryBuilder();
		$query = $builder->createPull();

		$this->applyClassAttributes($query, $class);

		$fn($query->select(function (SelectClause $select) {
			$select->addColumn('*');
		}));

		return new Delegate($this, $query);
	}

	public function purge(string $class, callable $fn): DelegateInterface {
		$builder = $this->driver->createQueryBuilder();
		$query = $builder->createPurge();

		$this->applyClassAttributes($query, $class);

		$fn($query);

		return new Delegate($this, $query);
	}

	public function push(string $class, callable $fn): DelegateInterface {
		$builder = $this->driver->createQueryBuilder();
		$query = $builder->createPush();

		$this->applyClassAttributes($query, $class);

		$fn($query);

		return new Delegate($this, $query);
	}

	public function query(string $query): \PDOStatement | false {
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
