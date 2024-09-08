<?php

namespace NaN\Database\Query;

class Builder implements BuilderInterface {
	public function createPatch(): Statements\PatchInterface {
		return new Statements\Patch();
	}

	public function createPull(): Statements\PullInterface {
		return new Statements\Pull();
	}

	public function createPurge(): Statements\PurgeInterface {
		return new Statements\Purge();
	}

	public function createPush(): Statements\PushInterface {
		return new Statements\Push();
	}
}
