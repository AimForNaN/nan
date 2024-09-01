<?php

namespace NaN\Database\Query;

class Builder implements BuilderInterface {
	public function createPull(): Statements\PullInterface {
		return new Statements\Pull();
	}

	public function createPurge() {
	}

	public function createPush(): Statements\PushInterface {
		return new Statements\Push();
	}
}
