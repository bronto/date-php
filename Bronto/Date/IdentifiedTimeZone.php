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
			self::$UTC = self::fromValidatedString(TimeZone::UTC_ZONE_ID);
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

		if ($timeZone instanceof self) {
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

		if ($timeZone instanceof self) {
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
		$timeZone = new self();

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

