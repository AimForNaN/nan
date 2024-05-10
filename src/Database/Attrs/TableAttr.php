<?php

namespace NaN\Database\Attrs;

#[\Attribute(\Attribute::TARGET_CLASS)]
class TableAttr {
	public function __construct(
		public readonly string $name,
		public readonly ?string $database = null,
	) {
	}
}
