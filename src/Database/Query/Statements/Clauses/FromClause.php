<?php

namespace NaN\Database\Query\Statements\Clauses;

use NaN\Database\Attrs\TableAttr;

class FromClause extends \NaN\Collections\Collection implements ClauseInterface {
	public function addTable(string $table, string $db = null, string $alias = null) {
		$this->offsetSet(null, [
			'expr' => 'table',
			'alias' => $alias,
			'database' => $db,
			'table' => $table,
		]);
		return $this;
	}

	public function addTableFromClass(string $class, string $alias = null) {
		$table = $this->getTableFromClass($class);
		$this->addTable($table->name, $table->database, $alias);
		return $this;
	}

	public function getTableFromClass(string $class): TableAttr {
		$ref = new \ReflectionClass($class);
		[$table] = $ref->getAttributes(TableAttr::class);

		if ($table instanceof \ReflectionAttribute) {
			$table = $table->newInstance();
		}

		return $table;
	}

	/**
	 * @todo
	 */
	public function render(bool $prepared = false): string {
		return 'FROM';
	}
}
