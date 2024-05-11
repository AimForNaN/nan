<?php

namespace NaN\Database\Query\Statements;

interface PullInterface extends StatementInterface {
	public function distinct(): PullInterface;

	public function from(callable $fn): PullInterface;

	public function groupBy(string ...$columns): PullInterface;

	public function limit(int $limit, int $offset = 0): PullInterface;

	public function orderBy(callable $fn): PullInterface;

	public function select(callable $fn): PullInterface;

	public function where(callable $fn): PullInterface;
}
