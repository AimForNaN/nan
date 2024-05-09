<?php

namespace NaN\Database\Drivers;

trait DriverPdoTrait {
	private \PDO $connection;

	public function closeConnection() {
		$this->connection = null;
	}

	public function exec(callable $fn): mixed {
		return $fn($this->connection);
	}

	public function offsetExists(mixed $offset): bool {
		return false;
	}

	/**
	 * Get PDO attribute.
	 */
	public function offsetGet(mixed $offset): mixed {
		return $this->connection->getAttribute($offset);
	}

	/**
	 * Set PDO attribute.
	 */
	public function offsetSet(mixed $offset, mixed $value): void {
		$this->connection->setAttribute($offset, $value);
	}

	/**
	 * Set PDO attribute to false.
	 */
	public function offsetUnset(mixed $offset): void {
		$this->connection->setAttribute($offset, false);
	}

	public function openConnection() {
		$this->connection = new \PDO((string)$this);
	}
}
