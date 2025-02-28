<?php

use NaN\Collections\Collection;

class CollectionBench {
	public function benchArray() {
		$collection = [];

		for ($i = 0; $i < 1000; $i++) { 
			$collection[] = 0;
		}
	}

	/**
	 * @Warmup(1)
	 */
	public function benchCollection() {
		$collection = [];

		for ($i = 0; $i < 1000; $i++) {
			$collection[] = 0;
		}

		$collection = new Collection($collection);
	}

	/**
	 * @Warmup(1)
	 */
	public function benchCollectionOffsetSet() {
		$collection = new Collection();

		for ($i = 0; $i < 1000; $i++) { 
			$collection[] = 0;
		}
	}
}
