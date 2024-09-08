<?php

namespace NaN\Database\Query;

interface BuilderInterface {
	public function createPatch(): Statements\PatchInterface;

	public function createPull(): Statements\PullInterface;

	public function createPurge(): Statements\PurgeInterface;

	public function createPush(): Statements\PushInterface;
}
