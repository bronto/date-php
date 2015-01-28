<?php
/**
 * PHP version 5
 *
 * Copyright 2015 Bronto Software, Inc.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 *
 * @category    Date
 * @package     Date
 * @author      Ryan Scudellari <ryan@scudellari.com>
 * @copyright   2015 Bronto Software Inc.
 * @license     http://www.apache.org/licenses/LICENSE-2.0  Apache License, Version 2.0
 * @link        https://github.com/bronto/date-php
 */

namespace Bronto\Util;

/**
 * A utility class to help validate and convert argument values.
 */
class Preconditions {

	/**
	 * Ensure an argument is of type int. If $value is not of type int or cannot
	 * trivially be interpretted as an int, throw an InvalidArgumentException.
	 *
	 * @param int|string $value an int value, or a string representation of an
	 * int
	 * @param string $argumentName the name of the argument, to be used if an
	 * exception must be thrown
	 *
	 * @throws \InvalidArgumentException if $value is not an int or a string
	 * representation of an int
	 *
	 * @return the int value
	 */
	public static function requireInt($value, $argumentName) {
		if (is_int($value)) {
			return $value;
		}

		if (!is_string($value) || !is_numeric($value)) {
			throw new \InvalidArgumentException("$argumentName must be an int");
		}

		if ($value === '0') {
			return 0;
		}

		$intValue = intval($value);
		// This checks that $value really represents an int, and not a
		// float/double.
		if (((string) $intValue) !== $value) {
			throw new \InvalidArgumentException("$argumentName must be an int");
		}

		return $intValue;
	}

	/**
	 * Ensure an argument is of type string. If $value is not a string, throw an
	 * InvalidArgumentException.
	 *
	 * @param string $value a string value
	 * @param string $argumentName the name of the argument, to be used if an
	 * exception must be thrown
	 *
	 * @throws \InvalidArgumentException if $value is not a string
	 *
	 * @return the string value
	 */
	public static function requireString($value, $argumentName) {
		if (!is_string($value)) {
			throw new \InvalidArgumentException("$argumentName must be a string");
		}

		return $value;
	}
	
	/**
	 * Ensure an argument is non-null.
	 *
	 * @param mixed $argument an argument
	 * @param string $argumentName the name of the argument, to be used if an
	 * exception must be thrown
	 *
	 * @throws \InvalidArgumentException if $argument is null
	 */
	public static function checkNotNull($argument, $argumentName) {
		if (is_null($argument)) {
			throw new \InvalidArgumentException("$argumentName cannot be null");
		}
	}

}

