<?php

namespace NaN;

/**
 * Manange environment variables.
 */
final class Env {
	static protected array $env = [];
	static protected array $aliases = [];
	/** @var string $root Default directory of .env file. */

	/**
	 * Load .env file.
	 *
	 * @param string $dir Directory of .env file.
	 */
	private function __construct() {}

	/**
	 * Get environment variable.
	 *
	 * @param string $key Environment variable key.
	 * @param string $fallback Fallback value (defaults to null).
	 *
	 * @return ?string Environment variable value or fallback.
	 */
	static public function get(string $key, ?string $fallback = null): ?string {
		$key = static::$aliases[$key] ?? $key;
		return static::$env[$key] ?? $_ENV[$key] ?? $_SERVER[$key] ?? $fallback;
	}

	static public function load(?string $dir = null): void {
		$env = \Dotenv\Dotenv::createImmutable($dir ?? $_SERVER['DOCUMENT_ROOT']);
		static::$env = $env->safeLoad();
	}

	/**
	 * Register an alias key for an environment variable.
	 *
	 * @param string $alias Alias key.
	 * @param string $original Original key.
	 */
	static public function registerAlias(string $alias, string $original): void {
		static::$aliases[$alias] = $original;
	}
}

