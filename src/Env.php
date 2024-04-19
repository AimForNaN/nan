<?php

namespace NaN;

/**
 * Manange environment variables.
 */
class Env {
	protected array $env = [];
	/** @var string $root Default directory of .env file. */

	/**
	 * Load .env file.
	 *
	 * @param string $dir Directory of .env file.
	 */
	public function __construct(
		string $dir = '.',
		protected array $aliases = [],
	) {
		$this->load($dir);
	}

	/**
	 * Get environment variable.
	 *
	 * @param string $key Environment variable key.
	 *
	 * @return string Environment variable value.
	 */
	public function __get(string $key): string {
		$key = $this->aliases[$key] ?? $key;
		return $this->env[$key] ?? $_ENV[$key] ?? $_SERVER[$key];
	}

	/**
	 * @see Env::__get()
	 */
	public function __invoke(string $key): string {
		return $this->__get($key);
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
		$this->aliases[$alias] = $original;
	}
}

