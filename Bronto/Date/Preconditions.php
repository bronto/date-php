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

namespace Bronto\Date;

/**
 * A utility class to help validate and convert Date argument values.
 */
class Preconditions {
	
	/**
	 * Ensure an argument is a valid time zone. If $value is not of type
	 * Bronto\Date\TimeZone or a string representation of a time zone, throw an
	 * InvalidArgumentException.
	 *
	 * @param Bronto\Date\TimeZone|string $value a TimeZone object, or a string
	 * representation of a time zone
	 * @param string $argumentName the name of the argument, to be used if an
	 * exception must be thrown
	 *
	 * @throws \InvalidArgumentException if $value is not a TimeZone or a valid
	 * string representation of a time zone
	 *
	 * @return a Bronto\Date\TimeZone object
	 */
	public static function requireTimeZone($timeZone, $argumentName) {
		if ($timeZone instanceof TimeZone) {
			return $timeZone;
		}

		if (!is_string($timeZone)) {
			throw new \InvalidArgumentException("$argumentName must a Bronto\\Date\\TimeZone object or a time zone string [\$timeZone = " . print_r($timeZone, true));
		}

		return TimeZone::parse($timeZone);
	}

}

