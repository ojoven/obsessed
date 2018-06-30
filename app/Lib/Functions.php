<?php

namespace App\Lib;

class Functions {

	public static function getArrayValuesFromArrayIndex($array, $index) {

		$arrayValues = [];

		foreach ($array as $element) {
			$arrayValues[] = $element[$index];
		}

		return $arrayValues;
	}

}