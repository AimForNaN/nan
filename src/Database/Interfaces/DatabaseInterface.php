<?php

namespace NaN\Database\Interfaces;

interface DatabaseInterface {
	/**
	 * Perform patch query.
	 *
	 * Update statement equivalent.
	 *
	 * @param callable $fn Query-builder callback.
	 */
	public function patch(callable $fn): \PDOStatement | false;

	/**
	 * Perform pull query.
	 *
	 * Select statement equivalent.
	 *
	 * @param callable $fn Query-builder callback.
	 */
	public function pull(callable $fn): \PDOStatement | false;

	/**
	 * Perform purge query.
	 *
	 * Delete statement equivalent.
	 *
	 * @param callable $fn Query-builder callback.
	 */
	public function purge(callable $fn): \PDOStatement | false;

	/**
	 * Perform push query.
	 *
	 * Insert statement equivalent.
	 *
	 * @param callable $fn Query-builder callback.
	 */
	public function push(callable $fn): \PDOStatement | false;

	/**
	 * Perform raw query.
	 *
	 * If `$bindings` is not empty, will use a prepared statement.
	 *
	 * @param string $query Unfiltered query string.
	 * @param array [$bindings] Bindings for prepared statement.
	 */
	public function raw(string $query, array $bindings = []): \PDOStatement | false;

	/**
	 * Perform a transaction.
	 *
	 * Will automatically roll back at the first throwable.
	 *
	 * @param callable $fn Callback with DatabaseInterface as first argument.
	 */
	public function transact(callable $fn): bool;
}
