<?php

namespace NaN;

/**
 * Manange environment variables.
 */
final class Env {
	static protected array $aliases = [];
	static protected array $env = [];
	static protected bool $loaded = false;

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

	static public function isLoaded(): bool {
		return static::$loaded;
	}

	/**
	 * Load variables from `.env` file.
	 *
	 * Can only be run once per session.
	 */
	static public function load(?string $dir = null): void {
		if (!static::$loaded) {
			$env = \Dotenv\Dotenv::createImmutable($dir ?? $_SERVER['DOCUMENT_ROOT']);
			static::$env = $env->safeLoad();
			static::$loaded = true;
		}
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

