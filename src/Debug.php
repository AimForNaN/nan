<?php

namespace NaN;

use Monolog\Handler\ErrorLogHandler;
use Monolog\Logger;

class Debug {
	static public function log(string $msg) {
		static $logger = null;

		if ($logger === null) {
			$logger = new Logger('dev_log');
			$logger->pushHandler(new ErrorLogHandler());
		}

		$logger->debug($msg);
	}

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
