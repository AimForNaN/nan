<?php

namespace NaN\Database;

use NaN\Database\Query\Statements\{
    PatchInterface,
    PullInterface,
    PurgeInterface,
    PushInterface,
};

abstract class Entity implements \IteratorAggregate {
	public function __get(string $name) {
		$column = $this->getMapping($name);
		return $this->$column;
	}

	public function __set(string $name, mixed $value) {
		$column = $this->getMapping($name);
		$this->$column = $value;
	}

	/**
	 * @return string Database name (can be an empty string).
	 */
	abstract static public function database(): string;

	public function fill(iterable $data) {
		foreach ($data as $column => $value) {
			$this->$column = $value;
		}
	}

	public function getId(): mixed {
		return $this->{static::primaryKey()};
	}

	public function getIterator(): \Traversable {
		return new \ArrayIterator($this);
	}

	public function getMapping($name): string {
		$mappings = static::mappings();
		return $mappings[$name] ?? '';
	}

	/**
	 * @return array Database column mappings.
	 */
	static public function mappings(): array {
		return [
			'id' => static::primaryKey(),
		];
	}

	/**
	 * @return string Database primary key name.
	 */
	abstract static public function primaryKey(): string;

	public function patch(DatabaseInterface $db): \PDOStatement | false {
		return $db->patch(function (PatchInterface $query) {
			$query
				->patch(static::table(), static::database())
				->with($this)
				->whereEquals(static::primaryKey(), $this->getId())
				->limit()
			;
		});
	}

	static public function pull(DatabaseInterface $db, mixed $id): ?static {
		$statement = $db->pull(function (PullInterface $query) use ($id) {
			$query
				->pullAll()
				->from(static::table(), static::database())
				->whereEquals(static::primaryKey(), $id)
				->limit()
			;
		}) ?: null;

		return $statement?->fetchObject(static::class) ?: null;
	}

	public function purge(DatabaseInterface $db): \PDOStatement | false {
		return $db->purge(function (PurgeInterface $query) {
			$query
				->from(static::table(), static::database())
				->whereEquals(static::primaryKey(), $this->getId())
			;
		});
	}

	public function push(DatabaseInterface $db): \PDOStatement | false {
		return $db->push(function (PushInterface $query) {
			$query
				->push($this)
				->into(static::table(), static::database())
			;
		});
	}

	/**
	 * @todo
	 *
	 * @return array Database relations for foreign keys (can be empty).
	 */
	abstract static public function relations(): array;

	/**
	 * @return string Database table name.
	 */
	abstract static public function table(): string;
}
