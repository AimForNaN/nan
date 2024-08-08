<?php

namespace NaN\Database\Query\Statements;

use NaN\Database\Query\Statements\{
	Clauses\ClauseInterface,
	Clauses\FromClause,
	Clauses\GroupByClause,
	Clauses\LimitClause,
	Clauses\OrderByClause,
	Clauses\SelectClause,
	Clauses\WhereClause,
};

class Pull implements PullInterface {
	private Array $query = [];

	public function from(callable $fn): PullInterface {
		$from_clause = new FromClause();
		$this->query[1] = $from_clause;
		$fn($from_clause);
		return $this;
	}

	public function fromClass($class): PullInterface {
		return $this->from(function (FromClause $from) use ($class) {
			$from->addTableFromClass($class);
		});
	}

	/**
	 * @todo
	 */
	public function getBindings(): array {
		return \array_reduce($this->query, function (Array $ret, ClauseInterface $stmt): Array {
			return \array_merge($ret, $stmt->getBindings());
		}, []);
	}

	public function groupBy(string ...$columns): PullInterface {
		$group_by_clause = new GroupByClause(...$columns);
		$this->query[3] = $group_by_clause;
		return $this;
	}

	public function limit(int $limit, int $offset = 0): PullInterface {
		$limit_clause = new LimitClause($limit, $offset);
		$this->query[5] = $limit_clause;
		return $this;
	}

	public function orderBy(callable $fn): PullInterface {
		$order_by_clause = new OrderByClause();
		$this->query[4] = $order_by_clause;
		$fn($order_by_clause);
		return $this;
	}

	public function render(bool $prepared = false): string {
		return \implode(' ', \array_filter(
			\array_map(fn(ClauseInterface $stmt) => $stmt->render($prepared), $this->query)
		));
	}

	public function select(callable $fn): PullInterface {
		$select_clause = new SelectClause();
		$this->query[0] = $select_clause;
		$fn($select_clause);
		return $this;
	}

	public function selectAll(): PullInterface {
		return $this->select(function (SelectClause $select) {
			$select->addColumn('*');
		});
	}

	public function selectColumns(string ...$columns): PullInterface {
		return $this->select(function (SelectClause $select) use ($columns) {
			$select->addColumns($columns);
		});
	}

	public function where(callable $fn): PullInterface {
		$where_clause = new WhereClause();
		$this->query[2] = $where_clause;
		$fn($where_clause);
		return $this;
	}

	public function whereEquals(string $column, mixed $value): PullInterface {
		return $this->where(function (WhereClause $where) use ($column, $value) {
			$where->addColumn(null, $column, '=', $value);
		});
	}

	public function whereGreaterThan(string $column, mixed $value): PullInterface {
		return $this->where(function (WhereClause $where) use ($column, $value) {
			$where->addColumn(null, $column, '>', $value);
		});
	}

	public function whereGreaterThanEquals(string $column, mixed $value): PullInterface {
		return $this->where(function (WhereClause $where) use ($column, $value) {
			$where->addColumn(null, $column, '>=', $value);
		});
	}

	public function whereIn(string $column, array $values): PullInterface {
		return $this->where(function (WhereClause $where) use ($column, $values) {
			$where->addColumn(null, $column, 'IN', $values);
		});
	}

	public function whereLessThan(string $column, mixed $value): PullInterface {
		return $this->where(function (WhereClause $where) use ($column, $value) {
			$where->addColumn(null, $column, '<', $value);
		});
	}

	public function whereLessThanEquals(string $column, mixed $value): PullInterface {
		return $this->where(function (WhereClause $where) use ($column, $value) {
			$where->addColumn(null, $column, '<=', $value);
		});
	}
}
