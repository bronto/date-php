<?php
/**
 * PHP version 5
 *
 * Copyright (c) 2013 Bronto Software Inc.
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal in
 * the Software without restriction, including without limitation the rights to
 * use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies
 * of the Software, and to permit persons to whom the Software is furnished to do
 * so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS
 * OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY,
 * WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN
 * CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 *
 * @category    Date
 * @package     Date
 * @author      Ryan Scudellari <ryan@scudellari.com>
 * @copyright   2013 Bronto Software Inc.
 * @license     http://www.opensource.org/licenses/mit-license.html  MIT License
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

