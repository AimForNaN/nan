<?php

namespace NaN\Database\Drivers\Traits;

trait DriverTrait {
	protected ?\PDO $connection;

	public function __construct(
		protected array $config,
	) {
	}

	public function closeConnection() {
		$this->connection = null;
	}

	public function exec(callable $fn): mixed {
		return $fn($this->connection);
	}

	public function generateDsn(string $prefix = ''): string {
		$config = $this->config['driver'] ?? [];
		$config = \array_map(
			fn($key, $value) => "{$key}={$value}",
			\array_keys($config),
			\array_values($config),
		);
		return "$prefix:" . \implode(';', $config);
	}

	public function openConnection() {
		$this->connection = new \PDO(
			(string)$this,
			$this->config['username'] ?? null,
			$this->config['password'] ?? null,
			$this->config['options'] ?? [],
		);
	}
}
