<?php

namespace NaN\Database\Query;

interface BuilderInterface {
	public function createPatch();
	
	public function createPull(): Statements\PullInterface;

	public function createPurge();

	public function createPush();
}
