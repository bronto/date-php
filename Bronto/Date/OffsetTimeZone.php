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

