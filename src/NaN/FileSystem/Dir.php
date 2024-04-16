<?php

namespace NaN;

/**
 * Lazy directory iterator.
 *
 * Will only scan a path when attempting to iterate,
 *  and no file-system calls will be issued until issued.
 *  As such, you should not assume that the directory exists.
 *  Instead, try what you want to do and handle any thrown errors.
 */
class Dir extends \NaN\Collection implements \ArrayAccess {
	public readonly string $path;

	/**
	 * Will convert all slashes to forward slashes.
	 */
	public function __construct(string ...$paths) {
		$this->path = static::toForwardSlashes(...$paths);
	}

	public function getIterator(): \Traversable {
		return new \FilesystemIterator($this->path);
	}

	/**
	 * Performs a file-system call.
	 */
	public function offsetExists(mixed $offset): bool {
		return \file_exists(static::toForwardSlashes($this->path, $offset));
	}

	public function offsetGet(mixed $offset): \SplFileInfo {
		return new \SplFileInfo(static::toForwardSlashes($this->path, $offset));
	}

	public function offsetSet(mixed $offset, mixed $value): void {
	}

	public function offsetUnset(mixed $offset): void {
	}

	static public function toForwardSlashes(string ...$paths): string {
		return \str_replace(['/', '\\'], '/', \implode('/', $paths));
	}
}
