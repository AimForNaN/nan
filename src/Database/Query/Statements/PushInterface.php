<?php

namespace NaN\Database\Query\Statements;

interface PushInterface extends StatementInterface {
	public function into(string $table, string $database = null): PushInterface;

	public function push(iterable $columns): PushInterface;

	public function where(callable $fn): PushInterface;

	public function whereEquals(string $column, mixed $value): PushInterface;

	public function whereGreaterThan(string $column, mixed $value): PushInterface;

	public function whereGreaterThanEquals(string $column, mixed $value): PushInterface;

	public function whereIn(string $column, array $value): PushInterface;

	public function whereLessThan(string $column, mixed $value): PushInterface;

	public function whereLessThanEquals(string $column, mixed $value): PushInterface;
}
