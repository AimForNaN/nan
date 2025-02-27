<?php

use NaN\Collections\Collection;

class CollectionBench {
	public function benchArray() {
		$collection = [];

		for ($i = 0; $i < 1000; $i++) { 
			$collection[] = 0;
		}
	}

	public function benchCollection() {
		$collection = new Collection();

		for ($i = 0; $i < 1000; $i++) { 
			$collection[] = 0;
		}
	}
}
