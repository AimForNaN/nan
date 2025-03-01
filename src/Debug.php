<?php

namespace NaN;

class Debug {
	static public function off() {
		\error_reporting(0);
	}

	static public function on(int $level = \E_ALL) {
		\error_reporting($level);
		$whoops = new \Whoops\Run();
		$whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler());
		$whoops->register();
	}
}
