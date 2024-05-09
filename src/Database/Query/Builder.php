<?php

namespace NaN\Database\Query;

class Builder implements BuilderInterface {
	public function createPatch() {
	}

	public function createPull(): Statements\PullInterface {
		return new Statements\Pull();
	}

	public function createPurge() {
	}

	public function createPush() {
	}
}
