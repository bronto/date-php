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
 * TimeZone class.
 *
 * This class serves two purposes:
 *   1. It is the base class for IdentifiedTimeZone and OffsetTimeZone.
 *   2. It serves as the main entry point for calling code. The vast majority of
 *   use cases should be using this class instead of the concrete
 *   implementations.
 */
abstract class TimeZone {

	// The time zone id for UTC as defined by the IANA time zone database
	const UTC_ZONE_ID = 'UTC';

	// The following three constants are internal DateTimeZone type values.
	// These are not documented on php.net. See details about each type in
	// section [2 Time Zones] of Bronto\Date\DateTime.php's documentation.
	
	// Offset time zones, e.g. +0100, -01:00
	private static $ZONE_TYPE_OFFSET       = 1;

	// Abbreviated time zones, e.g. EST, PDT
	private static $ZONE_TYPE_ABBREVIATION = 2;

	// Time zone ids, as defined by the IANA Time Zone Database, e.g.
	// America/New_York, UTC, Europe/Paris
	private static $ZONE_TYPE_ID           = 3;

	protected $dateTimeZone;

	/**
	 * Returns a TimeZone representing UTC.
	 *
	 * @return TimeZone a TimeZone representing UTC
	 */
	public static function utc() {
		return IdentifiedTimeZone::utc();
	}

	/**
	 * Parses the $zone string into a TimeZone object. Both time zone ids and
	 * UTC offset representations are supported, e.g. UTC, America/New_York,
	 * +0400, -05:00. Time zone abbreviations are NOT supported, e.g. EST, PDT.
	 *
	 * @param string $zone a time zone id or utc offset
	 *
	 * @return TimeZone a TimeZone object
	 *
	 * @throws \InvalidArgumentException if $zone is not a valid time zone
	 * string
	 * @throws \InvalidArgumentException if $zone is a time zone abbreviation
	 */
	public static function parse($zone) {
		$zone = \Bronto\Util\Preconditions::requireString($zone, '$zone');
		$details = date_parse($zone);
		
		if ($details === false || $details['error_count'] > 0 ||
				$details['year'] !== false || $details['month'] !== false ||
				$details['day'] !== false || $details['hour'] !== false ||
				$details['minute'] !== false || $details['second'] !== false ||
				$details['fraction'] !== false || !isset($details['zone_type'])) {
			throw new \InvalidArgumentException("Invalid TimeZone; value = $zone");
		}

		if (strtoupper($zone) == self::UTC_ZONE_ID) {
			return static::utc();
		}

		$zoneType = $details['zone_type'];
		if ($zoneType == self::$ZONE_TYPE_ABBREVIATION) {
			throw new \InvalidArgumentException('Time zones cannot be specified by abbreviations; use UTC offsets or TimeZone identifiers');
		}

		if ($zoneType == self::$ZONE_TYPE_OFFSET) {
			return OffsetTimeZone::fromValidatedString($zone);
		}

		return IdentifiedTimeZone::fromValidatedString($details['tz_id']);
	}

	/**
	 * Creates a TimeZone from a \DateTimeZone object. This only supports
	 * \DateTimeZones representing time zone ids or UTC offsets. Time zone
	 * abbreviations are NOT supported.
	 *
	 * @param \DateTimeZone $zone a \DateTimeZone representing a time zone id or
	 * a UTC offset
	 *
	 * @return TimeZone a TimeZone object
	 *
	 * @throws \InvalidArgumentException if $zone represents a time zone
	 * abbreviation
	 */
	public static function fromDateTimeZone(\DateTimeZone $zone) {
		return static::parse($zone->getName());
	}

	/**
	 * Converts this TimeZone to a \DateTimeZone.
	 *
	 * @return \DateTimeZone a \DateTimeZone object
	 */
	public function toDateTimeZone() {
		return $this->dateTimeZone;
	}

	/**
	 * Get a string representation of the TimeZone
	 *
	 * @return string a string representation of the TimeZone
	 */
	public function __toString() {
		return $this->getName();
	}

	/**
	 * Get the name of the time zone.
	 *
	 * @return string the time zone's name
	 */
	public function getName() {
		return $this->dateTimeZone->getName();
	}

	/**
	 * Get the time zone offset in seconds relative to UTC at an instant. The
	 * $timestamp instant is required as the UTC offset of a time zone can
	 * change over time due to daylight savings transition rules.
	 *
	 * @param int $timestamp a timestamp, the UTC offset will be calculated for
	 * this time zone at this instant in time; the number of seconds since UNIX
	 * epoch (1907-01-01 00:00:00.000000 UTC)
	 *
	 * @return int the number of seconds this time zone is offset from UTC
	 *
	 * @throws \InvalidArgumentException if $timestamp is not an int or a valid
	 * string representation of an int
	 */
	public function getUtcOffset($timestamp) {
		$timestamp = \Bronto\Util\Preconditions::requireInt($timestamp, '$timestamp');
		$dateTime = new \DateTime();
		$dateTime->setTimestamp($timestamp);
		return $this->dateTimeZone->getOffset($dateTime);
	}

	/**
	 * Evaluates equality of this time zone to another.
	 *
	 * @param TimeZone $timeZone a TimeZone
	 *
	 * @return true if this time zone matches $timeZone
	 */
	public function equals(TimeZone $timeZone) {
		return get_class($timeZone) == get_class($this) && $timeZone->getName() == $this->getName();
	}


}

