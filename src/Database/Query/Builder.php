<?php

namespace NaN\Database\Query;

use NaN\Database\Query\Interfaces\BuilderInterface;
use NaN\Database\Query\Statements\{
	Interfaces\PatchInterface,
	Interfaces\PullInterface,
	Interfaces\PurgeInterface,
	Interfaces\PushInterface,
	Patch,
	Pull,
	Purge,
	Push,
};

class Builder implements BuilderInterface {
	public function createPatch(): PatchInterface {
		return new Patch();
	}

	public function createPull(): PullInterface {
		return new Pull();
	}

	public function createPurge(): PurgeInterface {
		return new Purge();
	}

	public function createPush(): PushInterface {
		return new Push();
	}
}
