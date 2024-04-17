<?php

namespace NaN;

/**
 * Manange environment variables.
 */
class Env {
	static protected $aliases = [];
	protected array $env = [];
	/** @var string $root Default directory of .env file. */

	/**
	 * Load .env file.
	 *
	 * @param string $dir Directory of .env file.
	 */
	public function __construct(string $dir = '.') {
		$this->load($dir);
	}

	/**
	 * Get environment variable.
	 *
	 * @param string $key Environment variable key.
	 *
	 * @return string Environment variable value.
	 */
	public function __invoke(string $key): string {
		$key = static::$aliases[$key] ?? $key;
		return $this->env[$key] ?? $_ENV[$key] ?? $_SERVER[$key];
	}

	protected function load(string $dir): void {
		$env = \Dotenv\Dotenv::createImmutable($dir);
		$this->env = $env->safeLoad();
	}

	/**
	 * Register an alias key for an environment variable.
	 *
	 * @param string $original Original key.
	 * @param string $alias Alias key.
	 */
	public function registerAlias(string $original, string $alias): void {
		static::$aliases[$alias] = $original;
	}
}

