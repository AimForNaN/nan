<?php

use Monolog\Handler\ErrorLogHandler;
use Monolog\Logger;
use NaN\App\TemplateEngine;
use NaN\Database\{
	Database,
	Drivers\Sqlite\Driver as SqliteDriver,
};
use NaN\DI\Container;

$db = new Database(new SqliteDriver());

$logger = new Logger('dev_log');
$logger->pushHandler(new ErrorLogHandler());

$tpl = new TemplateEngine($_SERVER['DOCUMENT_ROOT'] . '/views/', 'tpl.php');

return new Container([
	Database::class => $db,
	TemplateEngine::class => $tpl,
	Logger::class => $logger,
]);
