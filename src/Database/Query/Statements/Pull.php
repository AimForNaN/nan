<?php

namespace NaN\Database\Query\Statements;

use NaN\Database\Query\Statements\Clauses\{
	FromClause,
	GroupByClause,
	LimitClause,
	OrderByClause,
	SelectClause,
	WhereClause,
};

class Pull implements PullInterface {
	use StatementTrait;

	public function first(): PullInterface {
		$this->limit(1);
		return $this;
	}

	public function from(string $table, string $database = null): PullInterface {
		$from_clause = new FromClause();
		$from_clause->addTable($table, $database);
		return $this->setFrom($from_clause);
	}

	public function fromClass($class): PullInterface {
		$from_clause = new FromClause();
		$from_clause->addTableFromClass($class);
		return $this->setFrom($from_clause);
	}

	public function fromSubQuery(callable $fn): PullInterface {
		$query = new static();
		$from_clause = new FromClause();
		$from_clause->addSubQuery($query);
		$fn($query);
		return $this->setFrom($from_clause);
	}

	public function groupBy(array $columns): PullInterface {
		$group_by_clause = new GroupByClause($columns);
		return $this->setGroupBy($group_by_clause);
	}

	public function last(string $column): PullInterface {
		$this->orderBy([$column => 'desc']);
		$this->limit(1);
		return $this;
	}

	public function limit(int $limit, int $offset = 0): PullInterface {
		$limit_clause = new LimitClause($limit, $offset);
		return $this->setLimit($limit_clause);
	}

	public function orderBy(array $order): PullInterface {
		$order_by_clause = new OrderByClause($order);
		return $this->setOrderBy($order_by_clause);
	}

	public function pull(array $columns, bool $distinct = false): PullInterface {
		$select_clause = new SelectClause();
		$select_clause->addColumns($columns);

		if ($distinct) {
			$select_clause->distinct();
		}

		return $this->setSelect($select_clause);
	}

	public function pullAll(bool $distinct = false): PullInterface {
		$select_clause = new SelectClause();
		$select_clause->addAllColumns();

		if ($distinct) {
			$select_clause->distinct();
		}

		return $this->setSelect($select_clause);
	}

	public function setFrom(FromClause $from): PullInterface {
		$this->query[1] = $from;
		return $this;
	}

	public function setGroupBy(GroupByClause $group_by): PullInterface {
		$this->query[3] = $group_by;
		return $this;
	}

	public function setLimit(LimitClause $limit): PullInterface {
		$this->query[5] = $limit;
		return $this;
	}

	public function setOrderBy(OrderByClause $order_by): PullInterface {
		$this->query[4] = $order_by;
		return $this;
	}

	public function setSelect(SelectClause $select): PullInterface {
		$this->query[0] = $select;
		return $this;
	}

	public function setWhere(WhereClause $where): PullInterface {
		$this->query[2] = $where;
		return $this;
	}

	public function where(callable $fn): PullInterface {
		$where_clause = new WhereClause();
		$fn($where_clause);
		return $this->setWhere($where_clause);
	}

	public function whereEquals(string $column, mixed $value): PullInterface {
		$where_clause = new WhereClause();
		$where_clause->addColumn(null, $column, '=', $value);
		return $this->setWhere($where_clause);
	}

	public function whereGreaterThan(string $column, mixed $value): PullInterface {
		$where_clause = new WhereClause();
		$where_clause->addColumn(null, $column, '>', $value);
		return $this->setWhere($where_clause);
	}

	public function whereGreaterThanEquals(string $column, mixed $value): PullInterface {
		$where_clause = new WhereClause();
		$where_clause->addColumn(null, $column, '>=', $value);
		return $this->setWhere($where_clause);
	}

	public function whereIn(string $column, array $value): PullInterface {
		$where_clause = new WhereClause();
		$where_clause->addColumn(null, $column, 'IN', $value);
		return $this->setWhere($where_clause);
	}

	public function whereLessThan(string $column, mixed $value): PullInterface {
		$where_clause = new WhereClause();
		$where_clause->addColumn(null, $column, '<', $value);
		return $this->setWhere($where_clause);
	}

	public function whereLessThanEquals(string $column, mixed $value): PullInterface {
		$where_clause = new WhereClause();
		$where_clause->addColumn(null, $column, '<=', $value);
		return $this->setWhere($where_clause);
	}
}
