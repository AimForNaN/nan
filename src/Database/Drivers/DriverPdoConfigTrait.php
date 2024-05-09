<?php

namespace NaN\Database\Drivers;

trait DriverPdoConfigTrait {
	use DriverPdoTrait;

	public function __construct(
		private array $config,
	) {
	}

	public function generateDsn(string $prefix = ''): string {
		$config = $this->config['driver'] ?? [];
		$config = \array_map(
			fn($key, $value) => "$key=$value",
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
