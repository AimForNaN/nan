<?php

namespace NaN;

class Debug {
	static public function off() {
		\error_reporting(0);
	}

	static public function on(int $level = null) {
		\error_reporting($level ?: \E_ALL);
	}
}
