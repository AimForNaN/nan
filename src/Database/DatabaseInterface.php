<?php

namespace NaN\Database;

interface DatabaseInterface {
	/**
	 * Use only for prepared statements!
	 *
	 * @param string $query Query string.
	 * @param array $bindings Bindings for prepared statement; cannot be empty!
	 *
	 * @throws \InvalidArgumentException
	 */
	public function exec(string $query, array $bindings): \PDOStatement | false;

	/**
	 * Prepare patch query.
	 *
	 * Update statement equivalent.
	 *
	 * @param string $class Fully-qualified class name.
	 * @param callable $fn Query-builder callback.
	 */
	public function patch(string $class, callable $fn): DelegateInterface;

	/**
	 * Prepare pull query.
	 *
	 * Select statement equivalent.
	 *
	 * @param string $class Fully-qualified class name.
	 * @param callable $fn Query-builder callback.
	 */
	public function pull(string $class, callable $fn): DelegateInterface;

	/**
	 * Prepare purge query.
	 *
	 * Delete statement equivalent.
	 *
	 * @param string $class Fully-qualified class name.
	 * @param callable $fn Query-builder callback.
	 */
	public function purge(string $class, callable $fn): DelegateInterface;

	/**
	 * Prepare push query.
	 *
	 * Insert statement equivalent.
	 *
	 * @param string $class Fully-qualified class name.
	 * @param callable $fn Query-builder callback.
	 */
	public function push(string $class, callable $fn): DelegateInterface;

	/**
	 * Perform simple query.
	 *
	 * @param string $query Unfiltered query string.
	 */
	public function query(string $query): \PDOStatement | false;

	/**
	 * Perform a transaction.
	 *
	 * Will automatically rollback at the first throwable.
	 *
	 * @param callable $fn Callback with DatabaseInterface as first argument.
	 */
	public function transact(callable $fn): bool;
}
