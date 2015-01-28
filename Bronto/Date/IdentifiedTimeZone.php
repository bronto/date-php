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
 * Time zones as defined by the IANA time zone database. These time zones
 * typically involve daylight saving transition rules.
 */
class IdentifiedTimeZone extends TimeZone {

	private static $UTC;

	/**
	 * Returns a TimeZone representing UTC.
	 *
	 * @return TimeZone a TimeZone representing UTC
	 */
	public static function utc() {
		if (!self::$UTC) {
			self::$UTC = static::fromValidatedString(TimeZone::UTC_ZONE_ID);
		}

		return self::$UTC;
	}

	/**
	 * Use static factory methods.
	 */
	private function __construct() { }

	/**
	 * Get a time zone by id. $id must be an id from the IANA time zone
	 * database. e.g. UTC, America/New_York, Europe/Paris
	 */
	public static function fromId($id) {
		$id = \Bronto\Util\Preconditions::requireString($id, '$id');
		$timeZone = TimeZone::parse($id);

		if ($timeZone instanceof static) {
			return $timeZone;
		}

		throw new \InvalidArgumentException("Invalid time zone id: $id");
	}

	/**
	 * Not supported. See \Bronto\Date\TimeZone::fromDateTimeZone.
	 *
	 * @param \DateTimeZone $zone a DateTimeZone
	 *
	 * @throws \BadMethodCallException always
	 */
	public static function fromDateTimeZone(\DateTimeZone $zone) {
		throw new \BadMethodCallException('IdentifiedTimeZone::fromDateTimeZone is not supported. Use TimeZone::fromDateTimeZone instead.');
	}

	/**
	 * Parses the $zone string into a TimeZone object. This method only supports
	 * time zone ids. See \Bronto\Date\TimeZone for more general support.
	 *
	 * @param string $zone a time zone id
	 *
	 * @return IdentifiedTimeZone a IdentifiedTimeZone object
	 *
	 * @throws \InvalidArgumentException if $zone is not a valid time zone id
	 */
	public static function parse($zone) {
		$zone = \Bronto\Util\Preconditions::requireString($zone, '$zone');
		$timeZone = TimeZone::parse($zone);

		if ($timeZone instanceof static) {
			return $timeZone;
		}

		throw new \InvalidArgumentException("Invalid IdentifiedTimeZone; value = $zone");
	}

	/**
	 * Used internally to construct a TimeZone object from a time zone string
	 * that has already been validated. This should only be called with $zone
	 * strings that are known to represent a valid time zone id.
	 *
	 * @param string $zone a time zone id
	 *
	 * @return IdentifiedTimeZone an IdentifiedTimeZone object
	 *
	 * @throws \InvalidArgumentException if $zone is not a valid time zone id
	 */
	protected static function fromValidatedString($zone) {
		$timeZone = new static();

		try {
			$dateTimeZone = new \DateTimeZone($zone);
		} catch (\Exception $e) {
			// In theory this should never happen as all $zone inputs should
			// have been validated prior to calling this method
			throw new \InvalidArgumentException("Invalid time zone id: $zone");
		}

		$timeZone->dateTimeZone = $dateTimeZone;
		return $timeZone;
	}

}

