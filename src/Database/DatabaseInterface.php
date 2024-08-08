<?php

namespace NaN\Database;

interface DatabaseInterface {
	/**
	 * Use only for raw prepared statements!
	 *
	 * @param string $query Query string.
	 * @param array $bindings Bindings for prepared statement; cannot be empty!
	 *
	 * @throws \InvalidArgumentException
	 */
	public function execRaw(string $query, array $bindings): \PDOStatement | false;

	/**
	 * Prepare patch query.
	 *
	 * Update statement equivalent.
	 *
	 * @param callable $fn Query-builder callback.
	 */
	public function patch(callable $fn): DelegateInterface;

	/**
	 * Prepare pull query.
	 *
	 * Select statement equivalent.
	 *
	 * @param callable $fn Query-builder callback.
	 */
	public function pull(callable $fn): DelegateInterface;

	/**
	 * Prepare purge query.
	 *
	 * Delete statement equivalent.
	 *
	 * @param callable $fn Query-builder callback.
	 */
	public function purge(callable $fn): DelegateInterface;

	/**
	 * Prepare push query.
	 *
	 * Insert statement equivalent.
	 *
	 * @param callable $fn Query-builder callback.
	 */
	public function push(callable $fn): DelegateInterface;

	/**
	 * Perform simple raw query.
	 *
	 * @param string $query Unfiltered query string.
	 */
	public function queryRaw(string $query): \PDOStatement | false;

	/**
	 * Perform a transaction.
	 *
	 * Will automatically rollback at the first throwable.
	 *
	 * @param callable $fn Callback with DatabaseInterface as first argument.
	 */
	public function transact(callable $fn): bool;
}
