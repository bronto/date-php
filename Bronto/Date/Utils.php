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
 * A collection of helpful methods for dealing with \DateTime objects.
 */
class Utils {

	/**
	 * Gets the timestamp from a \DateTime instance. Calling getTimestamp() on a
	 * \DateTime object can corrupt it. For more details see \Bronto\Date\DateTime's
	 * documentation, section [1.2 Timestamp corruption].
	 *
	 * @param \DateTime $dateTime a \DateTime object
	 *
	 * @return int the date time's timestamp; the number of seconds since UNIX
	 * epoch
	 */
	public static function extractTimestamp(\DateTime $dateTime) {
		return intval($dateTime->format('U'));
	}

	/**
	 * Gets the microseconds field of a \DateTime instance. The \DateTime class
	 * does not fully support microsecond precision, so even though it stores
	 * said precision, there is no equivalant getter to read it back out.
	 *
	 * @param \DateTime $dateTime a \DateTime object
	 *
	 * @return int the microsecond of the second of the instant of the
	 * date time
	 */
	public static function extractMicroseconds(\DateTime $dateTime) {
		return intval($dateTime->format('u'));
	}
}

