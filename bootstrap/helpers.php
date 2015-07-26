<?php

if ( ! function_exists('is_array_of')) {
	function is_array_of(array $objects, $className)
	{
		if (count($objects) === 0) {
			return false;
		}

		foreach ($objects as $object) {
			if (! is_a($object, $className)) {
				return false;
			}
		}

		return true;
	}
}
