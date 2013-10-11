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

