<?php

namespace NaN\Database\Attrs;

#[\Attribute(\Attribute::TARGET_CLASS)]
class TableAttr {
	public function __construct(
		public readonly string $name,
		public readonly string $database = null,
	) {
	}

	static public function fromClass(string $class): TableAttr {
		$ref = new \ReflectionClass($class);
		[$table] = $ref->getAttributes(TableAttr::class);

		if ($table instanceof \ReflectionAttribute) {
			$table = $table->newInstance();
		}

		return $table;
	}
}
