<?php

namespace NaN\Database\Query\Statements;

interface PullInterface extends StatementInterface {
	public function first(): PullInterface;

	public function from(string $table, string $database = null): PullInterface;

	public function fromClass($class): PullInterface;

	public function fromSubQuery(callable $fn): PullInterface;

	public function groupBy(array $columns): PullInterface;

	public function last(string $column): PullInterface;

	public function limit(int $limit, int $offset = 0): PullInterface;

	public function orderBy(array $order): PullInterface;

	public function pull(array $columns, bool $distinct = false): PullInterface;

	public function pullAll(bool $distinct = false): PullInterface;

	public function where(callable $fn): PullInterface;

	public function whereEquals(string $column, mixed $value): PullInterface;

	public function whereGreaterThan(string $column, mixed $value): PullInterface;

	public function whereGreaterThanEquals(string $column, mixed $value): PullInterface;

	public function whereIn(string $column, array $value): PullInterface;

	public function whereLessThan(string $column, mixed $value): PullInterface;

	public function whereLessThanEquals(string $column, mixed $value): PullInterface;
}
