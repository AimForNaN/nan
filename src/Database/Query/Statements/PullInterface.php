<?php

namespace NaN\Database\Query\Statements;

interface PullInterface extends StatementInterface {
	public function from(callable $fn): PullInterface;

	public function fromClass($class): PullInterface;

	public function groupBy(string ...$columns): PullInterface;

	public function limit(int $limit, int $offset = 0): PullInterface;

	public function orderBy(callable $fn): PullInterface;

	public function select(callable $fn): PullInterface;

	public function selectAll(): PullInterface;

	public function selectColumns(string ...$columns): PullInterface;

	public function where(callable $fn): PullInterface;

	public function whereEquals(string $column, mixed $value): PullInterface;

	public function whereGreaterThan(string $column, mixed $value): PullInterface;

	public function whereGreaterThanEquals(string $column, mixed $value): PullInterface;

	public function whereIn(string $column, array $values): PullInterface;

	public function whereLessThan(string $column, mixed $value): PullInterface;

	public function whereLessThanEquals(string $column, mixed $value): PullInterface;
}
