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

