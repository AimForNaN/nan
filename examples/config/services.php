<?php

use Monolog\Handler\ErrorLogHandler;
use Monolog\Logger;
use NaN\DI\Container;

$logger = new Logger('dev_log');
$logger->pushHandler(new ErrorLogHandler());

return new Container([
	Logger::class => $logger,
]);
