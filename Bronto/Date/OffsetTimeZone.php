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
 * UTC offset time zones. These time zones are fixed offsets relative to UTC and
 * do NOT involved and daylight saving transition rules.
 */
class OffsetTimeZone extends TimeZone {

	private function __construct() { }

	/**
	 * This method call on this class is invalid. It happens to inherit this
	 * call from the base clas TimeZone, but UTC is an IdentifiedTimeZone and
	 * it would not make sense for the OffsetTimeZone class to return an
	 * IdentifiedTimeZone instance.
	 *
	 * @throws \BadMethodCallException always
	 */
	public static function utc() {
		throw new \BadMethodCallException('OffsetTimeZone::utc() is invalid. Use TimeZone::utc or IdentifiedTimeZone::utc instead.');
	}

	/**
	 * Parses the $zone string into a TimeZone object. This method only supports
	 * UTC offset time zones. See \Bronto\Date\TimeZone for more general support.
	 *
	 * @param string $zone a UTC offset time zone
	 *
	 * @return OffsetTimeZone a OffsetTimeZone object
	 *
	 * @throws \InvalidArgumentException if $zone is not a valid UTC offset time zone
	 */
	public static function parse($zone) {
		$zone = \Bronto\Util\Preconditions::requireString($zone, '$zone');
		$timeZone = TimeZone::parse($zone);

		if ($timeZone instanceof static) {
			return $timeZone;
		}

		throw new \InvalidArgumentException("Invalid UTC offset time zone; value = $zone");
	}

	/**
	 * Not supported. See \Bronto\Date\TimeZone::fromDateTimeZone.
	 *
	 * @param \DateTimeZone $zone a DateTimeZone
	 *
	 * @throws \BadMethodCallException always
	 */
	public static function fromDateTimeZone(\DateTimeZone $zone) {
		throw new \BadMethodCallException('OffsetTimeZone::fromDateTimeZone is not supported. Use TimeZone::fromDateTimeZone instead.');
	}

	/**
	 * Used internally to construct a TimeZone object from a time zone string
	 * that has already been validated. This should only be called with $zone
	 * strings that are known to represent a valid UTC offset time zone.
	 *
	 * @param string $zone a UTC offset time zone
	 *
	 * @return OffsetTimeZone an OffsetTimeZone object
	 *
	 * @throws \InvalidArgumentException if $zone is not a valid UTC offset time
	 * zone
	 */
	protected static function fromValidatedString($zone) {
		$timeZone = new static();
		$tmpDate = new \DateTime($zone);
		$timeZone->dateTimeZone = $tmpDate->getTimezone();
		return $timeZone;
	}

}

