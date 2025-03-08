<?php

namespace NaN\Database\Query\Interfaces;

use NaN\Database\Interfaces\DatabaseInterface;
use NaN\Database\Query\Statements\Interfaces\{
	PatchInterface,
	PullInterface,
	PurgeInterface,
	PushInterface,
};

interface BuilderInterface {
	public function createPatch(DatabaseInterface $db): PatchInterface;

	public function createPull(DatabaseInterface $db): PullInterface;

	public function createPurge(DatabaseInterface $db): PurgeInterface;

	public function createPush(DatabaseInterface $db): PushInterface;
}
