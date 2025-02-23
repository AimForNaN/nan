<?php

namespace NaN\Database\Query;

use NaN\Database\Query\Statements\Interfaces\{
	PatchInterface,
	PullInterface,
	PurgeInterface,
	PushInterface,
};

interface BuilderInterface {
	public function createPatch(): PatchInterface;

	public function createPull(): PullInterface;

	public function createPurge(): PurgeInterface;

	public function createPush(): PushInterface;
}
