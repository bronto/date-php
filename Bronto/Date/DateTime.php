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
 * DateTime is an implementation of an unmodifiable datetime class.
 *
 * It represents an exact point on the timeline, limited to the precision
 * of microseconds. A DateTime calculates its fields with respect to a time
 * zone.
 *
 *                         Implementation Details
 *
 * PHP's built-in \DateTime class is used to back this implementation. \DateTime
 * provides powerful date parsing, modification and formatting methods which
 * would be near impossible to replicate correctly or with the same level of
 * performance. While \DateTime *is* sophisticated, its API is
 * clumsy, its implementation is buggy and its mutability causes headaches and
 * confusion. \Bronto\Date\DateTime is a wrapper that aims to address each of these
 * issues:
 *
 * 1. The API is more intuitive, provides a central place to implement any
 * additional convenience methods deemed useful in the future.
 * 2. Bugs are identified and worked around internally. Users of
 * \Bronto\Date\DateTime do not need to concern themselves with PHP's
 * DateTime-related quirks.
 * 3. \Bronto\Date\DateTime objects are immutable.
 *
 * The \DateTime class is the built-in PHP implementation to encapsulate
 * an instant and time zone. An instant is a point in time, best
 * represented by a timestamp, e.g. the number of seconds since
 * 1970-01-01 00:00:00 UTC. A time zone, and its associated UTC offset and
 * daylight savings transition rules, is used to interpret the instant as a
 * formatted date people are accustomed to reading and understanding.
 *
 *
 *                               [0 Internals]
 *
 * There are two logical parts a \Bronto\Date\DateTime represents. An instant on a
 * timeline with microsecond precision, and a time zone. Internally, we keep
 * four variables of state:
 *
 *   $dateTime:  \DateTime; This is a complete representation of the
 *               DateTime, including the timestamp, microseconds and time zone.
 *               This object is used for the vast majority of the operations:
 *               date formatting and date math primarily.
 *
 *   $timeZone:  \Bronto\Date\TimeZone; Represents either a UTC offset or
 *               an identified time zone. Time zone abbreviations are not
 *               supported currently. See section [2 Time Zones] for details.
 *
 *   $timestamp: Integer; The number of seconds since epoch (1970-01-01 00:00:00
 *               UTC). getTimestamp() cannot be called safely on the internal
 *               $dateTime object (see sections [1.1 Sub-second precision] and
 *               [1.2 Timestamp corruption] for details), so it is best to keep
 *               this value separate. $timestamp can be negative to represent
 *               instants prior to epoch. On 64-bit systems integers range from
 *               -9223372036854775807 to 9223372036854775807, so this DateTime
 *               implementation supports dates within the range
 *               [-219246529-01-27T08:29:53.000000+00:00,
 *               219250468-12-04T15:30:07.999999+00:00].
 *
 *   $micros:    Integer; The number of microseconds since $timestamp. The only
 *               way to read this from the internal $dateTime is through date
 *               formatting (see section [1.1 Sub-second precision] for details),
 *               so it is best to keep this value separate. Valid values range:
 *               [0, 999999].
 *
 *
 *                           [1 \DateTime Limitations]
 *
 * \DateTime suffers from a number of limitations, bugs and quircks. Each
 * relevant to this library's implementation will be documented here.
 *
 *                          [1.1 Sub-second precision]
 *
 * \DateTime supports fractional seconds in very limited way. Fractional seconds
 * can only be set on \DateTime objects through \DateTime::createFromFormat.
 * Take note this is a static factory method and not a setter; therefore, the
 * fractional seconds componenet of a \DateTime object is immutable.
 *
 * There is no getter for fractional seconds. The only way to read fractional
 * seconds from a \DateTime object is through \DateTime::format, using the 'u'
 * format parameter.
 *
 *                          [1.2 Timestamp corruption]
 *
 * In some situations a \DateTime's timestamp may change unexpectedly. The
 * precise nature of this issue is still unknown, but the following oddities
 * have been observed:
 * - calls to \DateTime's setTimestamp or getTimestamp after a time zone has been
 *   set can result in the object's timestamp to be different than expected
 * - changing a \DateTime's time zone can result in the object's timestamp
 *   changing; changing a time zone should NEVER change the timestamp, only the
 *   date or time fields
 *
 * Here are several examples illustrating the issues:
 *
 * 1.2.1 Calling setTimestamp after setting a time zone
 *
 * $date = new DateTime();
 * $date->setTimezone(new DateTimeZone('America/Los_Angeles'));
 * $date->setTimestamp(1383469200);
 * echo "{$date->format('c')}\n";
 *
 * Expected output: 2013-11-03T01:00:00-08:00
 * Actual output:   2013-11-03T01:00:00-07:00
 *
 * Workaround: moving line #2 after line #3 results in the expected behavior.
 *
 * 1.2.2 Calling getTimestamp
 *
 * $date = new DateTime();
 * $date->setTimestamp(1383469200);
 * $date->setTimezone(new DateTimeZone('America/Los_Angeles'));
 * echo "{$date->getTimestamp()}\n";
 *
 * Expected output: 1383469200
 * Actual output:   1383465600
 * 
 * Workaround: Call use \DateTime format to extract the timestamp value with the
 * 'U' parameter string.
 *
 *
 *                               [2 Time Zones]
 *
 * \DateTimeZone is the built-in class for time zone representation. Three
 * types of time zone representations exists:
 *
 *   Time zone id: This is the official id as definied by the IANA Time Zone
 *                 Database. e.g. UTC, America/New_York, Europe/Paris. @see
 *                 http://www.php.net/manual/en/timezones.php for more details
 *                 on supported time zones in PHP. \Bronto\Date\IdentifiedTimeZone
 *                 is the \Bronto\Date\TimeZone implementation.
 * 
 *   UTC offset:   The number of hours and minutes offset from UTC time. e.g.
 *                 +04:00, -08:00. \Bronto\Date\OffsetTimeZone is the
 *                 \Bronto\Date\TimeZone implementation.
 *
 *   Abbreviation: EST, PDT, CEST, etc. There is no \Bronto\Date\TimeZone
 *                 implementation for this type.
 *
 * Currently only time zone id and UTC offset time zones are supported by
 * \Bronto\Date. Time zone abbreviations can be ambiguous and may result in
 * confusion without support for locales. For example, AMST could mean either
 * Armenia Summer Time (UTC + 5 hours) or Amazon Summer Time (UTC - 3 hours).
 *
 * The vast majority of use cases should use the TimeZone and DateTime classes
 * when interacting with time zones in the \Bronto\Date library. Both
 * IdentifiedTimeZone and OffsetTimeZone implementations are mostly private
 * implementation details, although there may be some cases where one would want
 * to verify a time zone is one type or another.
 *
 *                        [2.1 \DateTimeZone Limitations]
 *
 * The \DateTimeZone constructor only accepts time zone ids as input, therefore
 * the only way to instantiate UTC offset time zones is via
 * \DateTime::createFromFormat. For example, in order to get an instance of a
 * \DateTimeZone for '-08:00' one would need to do the following:
 *
 * $dateTime = \DateTime::createFromFormat('P', '-08:00');
 * $timeZone = $dateTime->getTimezone();
 */
class DateTime {

	const MILLIS_PER_SECOND = 1000;
	const MICROS_PER_MILLI = 1000;
	const MICROS_PER_SECOND = 1000000; // 1000 micros * 1000 millis
	const MICROS_PER_MINUTE = 60000000; // 1000 micros * 1000 millis * 60 seconds
	const MICROS_PER_HOUR = 3600000000; // 1000 micros * 1000 millis * 60 seconds * 60 minutes

	const EPOCH_YEAR                  = 1970;
	const EPOCH_MONTH_OF_YEAR         = 1;
	const EPOCH_DAY_OF_MONTH          = 1;
	const EPOCH_HOUR_OF_DAY           = 0;
	const EPOCH_MINUTE_OF_HOUR        = 0;
	const EPOCH_SECOND_OF_MINUTE      = 0;
	const EPOCH_MICROSECOND_OF_SECOND = 0;

	private $timestamp;
	private $micros;

	private $dateTime;
	private $timeZone;

	/**
	 * A private constructor. Use the static factory methods to create DateTime
	 * instances.
	 */
	private function __construct() { }

	/**
	 * Creates a DateTime with second precision from a UNIX timestamp and
	 * optionally a time zone. If sub-second precision is needed, use
	 * fromMillisTimestamp or fromMicrosTimestamp instead.
	 *
	 * @static
	 *
	 * @param int $timestamp a UNIX timestamp; the number of seconds since
	 * 1970-01-01 00:00:00 UTC
	 * @param TimeZone|string $timeZone a TimeZone object or a TimeZone
	 * string
	 *
	 * @throws \InvalidArgumentException if $timestamp is not an int
	 * @throws \InvalidArgumentException if $timeZone is not a TimeZone object
	 * or time zone string
	 *
	 * @return \Bronto\Date\DateTime
	 */
	public static function fromTimestamp($timestamp, $timeZone = null) {
		return static::fromMicrosTimestamp($timestamp, 0, $timeZone);
	}

	/**
	 * Creates a DateTime with millisecond precision.
	 *
	 * @static
	 *
	 * @param int $millisTimestamp the number of milliseconds since
	 * 1970-01-01 00:00:00.000000 UTC
	 * @param TimeZone|string $timeZone a TimeZone object or a time zone string
	 *
	 * @throws \InvalidArgumentException if $millisTimestamp is not an int
	 * @throws \InvalidArgumentException if $timeZone is not a TimeZone object
	 * or time zone string
	 *
	 * @return \Bronto\Date\DateTime
	 */
	public static function fromMillisTimestamp($millisTimestamp, $timeZone = null) {
		$millisTimestamp = \Bronto\Util\Preconditions::requireInt($millisTimestamp, '$millisTimestamp');

		$timeZone = $timeZone ?: TimeZone::utc();
		$timeZone = Preconditions::requireTimeZone($timeZone, '$timeZone');
		
		// calculate number of seconds since epoch
		$timestamp = intval(floor($millisTimestamp / self::MILLIS_PER_SECOND));
		// calculate number of milliseconds elapsed since $timestamp instant
		$millis = $millisTimestamp - ($timestamp * self::MILLIS_PER_SECOND);
		// calculate number of microseconds elapsed since $timestamp instant
		$micros = $millis * self::MICROS_PER_MILLI;
		return static::fromMicrosTimestamp($timestamp, $micros, $timeZone);
	}

	/**
	 * Creates a DateTime with microsecond precision.
	 *
	 * @static
	 *
	 * @param int $timestamp a UNIX timestamp; the number of seconds since
	 * 1970-01-01 00:00:00 UTC
	 * @param int $micros the microsecond of the second; must be within range [0,999999]
	 * @param TimeZone|string $timeZone a TimeZone object or a time zone string
	 *
	 * @throws \InvalidArgumentException if $timestamp is not an int
	 * @throws \InvalidArgumentException if $micros is not an int
	 * @throws \InvalidArgumentException if $micros is not within [0,999999]
	 * @throws \InvalidArgumentException if $timeZone is not a TimeZone object
	 * or time zone string
	 *
	 * @return \Bronto\Date\DateTime
	 */
	public static function fromMicrosTimestamp($timestamp, $micros, $timeZone = null) {
		$timestamp = \Bronto\Util\Preconditions::requireInt($timestamp, '$timestamp');
		$micros = \Bronto\Util\Preconditions::requireInt($micros, '$micros');

		if ($micros < 0 || $micros > 999999) {
			throw new \InvalidArgumentException("\$micros must be a whole number within [0,999999]; \$micros = {$micros}");
		}

		if (is_null($timeZone)) {
			$timeZone = TimeZone::utc();
		} else {
			$timeZone = Preconditions::requireTimeZone($timeZone, '$timeZone');
		}
		
		/***************************** WARNING ********************************/
		/***************************** WARNING ********************************/
		/***************************** WARNING ********************************/
		// The rest of this method is extremely fragile. \DateTime objects are
		// extremely finicky and buggy and must be used with great care. Please
		// read EVERYTHING below before changing anything.
		//
		// Keep the following in mind when making changes below:
		//
		// - Fractional seconds can only be set on a \DateTime via
		//   \DateTime::createFromFormat.
		// - UTC offset time zones can only be set on a \DateTime via
		//   \DateTime::createFromFormat.
		// - You cannot call setTimestamp or getTimestamp on a \DateTime object
		//   after associating it with a time zone -- either via setTimezone or
		//   \DateTime::createdFromFormat. Always apply IdentifiedTimeZones
		//   to a \DateTime last.
		//
		// See [1 \DateTime Limitations] in the class docblock for more details.
		//                      
		/***************************** WARNING ********************************/
		/***************************** WARNING ********************************/
		/***************************** WARNING ********************************/
		$dateTime = new static();
		$dateTime->timestamp = $timestamp;
		$dateTime->micros = $micros;
		$dateTime->timeZone = $timeZone;

		if ($timeZone instanceof IdentifiedTimeZone) {
			$partialDateFormat = 'u';
			$partialDateString = sprintf('%06d', $micros);
		} else {
			$partialDateFormat = 'u P';
			$partialDateString = sprintf('%06d %s', $micros, $timeZone->getName());
		}

		$dateTime->dateTime = \DateTime::createFromFormat($partialDateFormat, $partialDateString);
		$dateTime->dateTime->setTimestamp($timestamp);

		if ($timeZone instanceof IdentifiedTimeZone) {
			$dateTime->dateTime->setTimezone($timeZone->toDateTimeZone());
		}

		return $dateTime;
	}

	/**
	 * Parses a DateTime from a string.
	 *
	 * There are two ways the $date string can be interpreted within the context of
	 * a time zone. Either the time zone can be included in the $date string, or
	 * a time zone can be passed in as through the $timeZone argument. The
	 * $timeZone argument should only be used if no time zone is present in the
	 * $date string. If both are present and they conflict, then an
	 * InvalidArgumentException will be thrown. If neither are present, then the
	 * $date string will be interpreted with a UTC time zone.
	 *
	 * Partial datetimes may be represented in the $date string. The field
	 * values from UNIX timestamp epoch (1970-01-01 00:00:00.000000 UTC) will be used
	 * as default values for any missing fields. For example, '2013-05-06' would be
	 * interpreted as 2013-05-06 00:00:00 UTC and '2013 +02:00' would be
	 * interpreted as '2013-01-01 00:00:00 +02:00'.
	 *
	 * The \Bronto\Date\Format class contains a number of standard DateTime formats
	 * that can be used with the $format argument.
	 * 
	 * @static
	 *
	 * @param string $date a string representation of a datetime
	 * @param string $format the format string; see
	 * http://www.php.net/manual/en/datetime.createfromformat.php for supported
	 * syntax; see \Bronto\Date\Format for a collection of stanard DateTime formats
	 * @param TimeZone|string $timeZone the time zone to be used when interpreting the
	 * $date string
	 *
	 * @throws \InvalidArgumentException if $date is not a valid string
	 * representation of a datetime
	 * @throws \InvalidArgumentException if $format is not a string
	 * @throws \InvalidArgumentException if $date includes a time zone and
	 * $timeZone does not match; these two must match both in UTC offset and
	 * time zone type (identified vs fixed utc offset, see section [2 Time
	 * Zones])
	 *
	 * @return \Bronto\Date\DateTime
	 */
	public static function parse($date, $format, $timeZone = null) {
		$date = \Bronto\Util\Preconditions::requireString($date, '$date');
		$format = \Bronto\Util\Preconditions::requireString($format, '$format');

		if (!is_null($timeZone)) {
			$timeZone = Preconditions::requireTimeZone($timeZone, '$timeZone');
		}

		// This method is more strict than \DateTime::createFromFormat.
		// 2012-02-30 will result in an error here instead of 2012-03-01 as with
		// createFromFormat.
		$dateParts = date_parse_from_format($format, $date);
		if ($dateParts['error_count'] > 0 || $dateParts['warning_count'] > 0) {
			throw new \InvalidArgumentException("Invalid \$date or \$format; \$date = $date ; \$format = $format");
		}

		$dateTime = \DateTime::createFromFormat($format, $date);

		if ($dateTime === false) {
			throw new \InvalidArgumentException("Invalid \$date or \$format; \$date = $date ; \$format = $format");
		}

		// Checks if a time zone was included in the $date string.
		if (isset($dateParts['zone_type'])) {
			$encodedTimeZone = TimeZone::fromDateTimeZone($dateTime->getTimezone());

			// Checks if a time zone was passed in as an explicit argument and
			// whether or not it matches the one found in the $date string
			if (!is_null($timeZone) && !$timeZone->equals($encodedTimeZone)) {
				throw new \InvalidArgumentException("The time zone provided in \$date conflicts with the provided \$timeZone argument. \$date = $date ; \$timeZone = $timeZone");
			}

			// We have verified that if $timeZone exists at this point, that it
			// is the same as $encodedTimeZone -- so setting $timeZone to
			// $encodedTimeZone is correct when both are provided, and when only
			// $encodedTimeZone is provided.
			$timeZone = $encodedTimeZone;
		}

		if (is_null($timeZone)) {
			$timeZone = TimeZone::utc();
		}

		$micros = Utils::extractMicroseconds($dateTime);

		// Set reasonable defaults for missing date fields
		$year = $dateParts['year'] !== false ? $dateParts['year'] : self::EPOCH_YEAR;
		$month = $dateParts['month'] ? $dateParts['month'] : self::EPOCH_MONTH_OF_YEAR;
		$day = $dateParts['day'] ? $dateParts['day'] : self::EPOCH_DAY_OF_MONTH;

		// Set reasonable defaults for missing time fields
		$hour = $dateParts['hour'] !== false ? $dateParts['hour'] : self::EPOCH_HOUR_OF_DAY;
		$minute = $dateParts['minute'] !== false ? $dateParts['minute'] : self::EPOCH_MINUTE_OF_HOUR;
		$second = $dateParts['second'] !== false ? $dateParts['second'] : self::EPOCH_SECOND_OF_MINUTE;

		$dateString = sprintf('%04d-%02d-%02d %02d:%02d:%02d %s', $year, $month, $day, $hour, $minute, $second, $timeZone->getName());
		$dateTime = new \DateTime($dateString);
		$timestamp = Utils::extractTimestamp($dateTime);
		$result = static::fromMicrosTimestamp($timestamp, $micros, $timeZone);

		// If the resulting time is different from the time passed in then we
		// know PHP has adjusted due to daylight savings transitions.
		if ($result->getHourOfDay() !== $hour || $result->getMinuteOfHour() !== $minute) {
			throw new \InvalidArgumentException("Illegal instant due to time zone offset transition ($timeZone). \$date = $date");
		}

		return $result;
	}

	/**
	 * Create a DateTime from an alternative datetime implementation. Supported
	 * datetime implementations: \Bronto\Date\DateTime and \DateTime
	 *
	 * If a \Bronto\Date\DateTime object is passed in, the same object is returned.
	 *
	 * @static
	 *
	 * @param mixed $date a datetime object; see above for the list of support
	 * datetime implementations
	 * 
	 * @throws \InvalidArgumentException if $date is of an unsupported type
	 *
	 * @return \Bronto\Date\DateTime
	 */
	public static function createFrom($date) {
		if ($date instanceof static) {
			return $date;
		}

		if ($date instanceof \DateTime) {
			// Note: Do NOT call $date->getTimestamp(). See [1.2 Timestamp
			// corruption] for more details.
			$timestamp = Utils::extractTimestamp($date);
			return static::fromTimestamp($timestamp, $date->getTimezone()->getName());
		}

		$type = gettype($date);
		if ($type == 'object') {
			$type = get_class($date);
		}
		throw new \InvalidArgumentException("This date type is not supported. type = $type");
	}

	/**
	 * Create a DateTime with the current date and time.
	 *
	 * @static
	 *
	 * @param TimeZone|string $timeZone a TimeZone object or a TimeZone
	 * identifier
	 *
	 * @throws \InvalidArgumentException if $timeZone is not a TimeZone object
	 * or identifier
	 *
	 * @return \Bronto\Date\DateTime
	 */
	public static function now($timeZone = null) {
		$timeZone = $timeZone ?: TimeZone::utc();
		$timeZone = Preconditions::requireTimeZone($timeZone, '$timeZone');

		list($micros, $seconds) = explode(" ", microtime(false));
		$micros = intval(substr($micros, 2, 6));
		$seconds = intval($seconds);
		return static::fromMicrosTimestamp($seconds, $micros, $timeZone);
	}

	/**
	 * Returns a copy of this datetime plus an interval. The interval to add is
	 * composed of a $value and a $unit. $unit is one of any units supported by
	 * \DateTime::modify.
	 *
	 * This method uses \DateTime's modify method and is subject to any of its
	 * behavior and quirks. It will do its best to only update the fields in
	 * question, but it may have to adjust time or date fields due to daylight
	 * savings time rules, leap year rules and the varying number of days in
	 * each month.
	 *
	 * For example, if adding a month to '2013-01-30' would result in
	 * '2013-03-02'. This is due to the fact that there are only 28 days in
	 * February in 2013 and \DateTime's modify method rolls over the extra two
	 * days into the next month.
	 *
	 * In another example, if we are working with a time zone whose daylight
	 * savings rules cut over from 1:59 to 3:00 and plusInterval would result in
	 * the time 2:30, the modify call will adjust it to 3:30.
	 *
	 * Returns this instance if $value is 0.
	 *
	 * @param int $value the magnitude of the interval
	 * @param string $unit the unit of the interval; see \DateTime::modify for
	 * supported units
	 *
	 * @throws InvalidArgumentException if $value is not an int
	 * @throws InvalidArgumentException if $unit is not a string
	 *
	 * @return \Bronto\Date\DateTime a copy of this datetime plus the interval
	 */
	private function plusInterval($value, $unit) {
		$value = \Bronto\Util\Preconditions::requireInt($value, '$value');
		$unit = \Bronto\Util\Preconditions::requireString($unit, '$unit');

		if ($value == 0) {
			return $this;
		}

		$valueString = sprintf('%+d', $value);
		$modify = "{$valueString} {$unit}";
		return $this->modify($modify);
	}

	/**
	 * Returns a copy of this datetime after applying a modify string to the
	 * internal \DateTime object. See \DateTime::modify for details on the
	 * supported modify input string.
	 *
	 * This method uses \DateTime's modify method and is subject to any of its
	 * behavior and quirks. It will do its best to only update the fields in
	 * question, but it may have to adjust time or date fields due to daylight
	 * savings time rules, leap year rules and the varying number of days in
	 * each month.
	 *
	 * For example, if adding a month to '2013-01-30' would result in
	 * '2013-03-02'. This is due to the fact that there are only 28 days in
	 * February in 2013 and \DateTime's modify method rolls over the extra two
	 * days into the next month.
	 *
	 * In another example, if we are working with a time zone whose daylight
	 * savings rules cut over from 1:59 to 3:00 and plusInterval would result in
	 * the time 2:30, the modify call will adjust it to 3:30.
	 *
	 * @param string $modify the input string for \DateTime::modify
	 *
	 * @return \Bronto\Date\DateTime a copy of this datetime after applying the modify string
	 */
	private function modify($modify) {
		$modify = \Bronto\Util\Preconditions::requireString($modify, '$modify');

		$tmpDate = clone $this->dateTime;
		$tmpDate->modify($modify);
		$timestamp = Utils::extractTimestamp($tmpDate);
		return static::fromMicrosTimestamp($timestamp, $this->getMicrosOfSecond(), $this->getTimeZone());
	}

	/**
	 * Returns a copy of this datetime after adding the specified number of
	 * years.
	 *
	 * This method only changes the year when possible. In some cases other
	 * fields must be changed in order to represent a valid date. For example,
	 * 2012-02-29 is valid, but add a year the result of 2013-02-29 is a
	 * non-existent date, so it is adjusted to 2013-02-28.
	 *
	 * Returns $this if $years is 0.
	 *
	 * @param int $years the number of years to add
	 *
	 * @throws InvalidArgumentException if $years is not an int
	 *
	 * @return \Bronto\Date\DateTime a copy of this datetime after adding the specified
	 * number of years
	 */
	public function plusYears($years) {
		$value = \Bronto\Util\Preconditions::requireInt($years, '$years');
		return $this->plusMonths($years * 12);
	}

	/**
	 * Returns a copy of this datetime after subtracting the specified number of
	 * years.
	 *
	 * This method only changes the year when possible. In some cases other
	 * fields must be changed in order to represent a valid date. For example,
	 * 2012-02-29 is valid, but substract a year the result of 2011-02-29 is a
	 * non-existent date, so it is adjusted to 2011-02-28.
	 *
	 * Returns $this if $years is 0.
	 *
	 * @param int $years the number of years to subtract
	 *
	 * @throws InvalidArgumentException if $years is not an int
	 *
	 * @return \Bronto\Date\DateTime a copy of this datetime after subtracting the specified
	 * number of years
	 */
	public function minusYears($years) {
		$value = \Bronto\Util\Preconditions::requireInt($years, '$years');
		return $this->plusYears(-$years);
	}

	/**
	 * Returns a copy of this datetime after adding the specified number of
	 * months.
	 *
	 * This method will use the same day of the month whenever possible. For
	 * example, 2013-05-31 plus one month would result in an invalid date of
	 * 2013-06-31, so it is adjusted to 2013-06-30. In contrast, 2013-05-31 plus
	 * two months results in 2013-07-31, as it is a valid date without need for
	 * adjustment.
	 *
	 * Returns $this if $months is 0.
	 *
	 * @param int $months the number of months to add
	 *
	 * @throws InvalidArgumentException if $months is not an int
	 *
	 * @return \Bronto\Date\DateTime a copy of this datetime after adding the specified
	 * number of months
	 */
	public function plusMonths($months) {
		$value = \Bronto\Util\Preconditions::requireInt($months, '$months');
		$day = $this->getDayOfMonth();
		if ($day > 28) {
			// PHP's \DateTime implementation behaves oddly when dates exceed
			// logical boundaries. 2013-01-29 plus one month turns into
			// 2013-03-01. We need to adjust the date manually to get the
			// desired result of 2013-02-28.

			// the number of months beyond Jan of this datetime's year
			$monthsSinceThisYear = $this->getMonthOfYear() - 1 + $months;
			// the number of years to change
			$yearDelta = intval(floor($monthsSinceThisYear / 12));
			// the year of the target date
			$targetYear = $this->getYear() + $yearDelta;
			// the month of the target date
			$targetMonth = $monthsSinceThisYear - ($yearDelta * 12) + 1;

			$maxDay = cal_days_in_month(CAL_GREGORIAN, $targetMonth, $targetYear);

			if ($day > $maxDay) {
				$offset = $maxDay - $day;
				$monthString = sprintf('%+d', $months);
				$offsetString = sprintf('%+d', $offset);
				return $this->modify("$monthString months $offsetString days");
			}
		}

		return $this->plusInterval($months, 'months');
	}

	/**
	 * Returns a copy of this datetime after subtracting the specified number of
	 * months.
	 *
	 * This method will use the same day of the month whenever possible. For
	 * example, 2013-05-31 minus one month would result in an invalid date of
	 * 2013-04-31, so it is adjusted to 2013-04-30. In contrast, 2013-05-31
	 * minus two months results in 2013-03-31, as it is a valid date without
	 * need for adjustment.
	 *
	 * Returns $this if $months is 0.
	 *
	 * @param int $months the number of months to subtract
	 *
	 * @throws InvalidArgumentException if $months is not an int
	 *
	 * @return \Bronto\Date\DateTime a copy of this datetime after substracting the specified
	 * number of months
	 */
	public function minusMonths($months) {
		$value = \Bronto\Util\Preconditions::requireInt($months, '$months');
		return $this->plusMonths(-$months);
	}

	/**
	 * Returns a copy of this datetime after adding the specified number of
	 * days.
	 *
	 * This method will use the same time fields whenever possible. In some
	 * cases -- typically due to a daylight savings time transition -- the
	 * resulting datetime is invalid and needs to be adjusted. For example,
	 * the time zone America/Los_Angeles transitions into DST on 2013-03-10 at
	 * 02:00. Therefore, '2013-03-09 02:30 America/Los_Angeles' plus one day
	 * would result in a non-existent datetime of '2013-03-10 02:30-0800', and
	 * thus is adjusted to '2013-03-10 03:30-0700'.
	 *
	 * Returns $this if $days is 0.
	 *
	 * @param int $days the number of days to add
	 *
	 * @throws InvalidArgumentException if $days is not an int
	 *
	 * @returns \Bronto\Date\DateTime a copy of this datetime after adding the
	 * specified number of days
	 */
	public function plusDays($days) {
		$value = \Bronto\Util\Preconditions::requireInt($days, '$days');
		return $this->plusInterval($days, 'days');
	}

	/**
	 * Returns a copy of this datetime after subtracting the specified number of
	 * days.
	 *
	 * This method will use the same time fields whenever possible. In some
	 * cases -- typically due to a daylight savings time transition -- the
	 * resulting datetime is invalid and needs to be adjusted. For example,
	 * the time zone America/Los_Angeles transitions into DST on 2013-03-10 at
	 * 02:00. Therefore, '2013-03-11 02:30 America/Los_Angeles' minus one day
	 * would result in a non-existent datetime of '2013-03-10 02:30-0700', and
	 * thus is adjusted to '2013-03-10 03:30-0700'.
	 *
	 * Returns $this if $days is 0.
	 *
	 * @param int $days the number of days to add
	 *
	 * @throws InvalidArgumentException if $days is not an int
	 *
	 * @returns \Bronto\Date\DateTime a copy of this datetime after adding the
	 * specified number of days
	 */
	public function minusDays($days) {
		$value = \Bronto\Util\Preconditions::requireInt($days, '$days');
		return $this->plusDays(-$days);
	}

	/**
	 * Returns a copy of this datetime after adding the specified number of
	 * hours.
	 *
	 * The calculation will add a duration equivalent to the number of hours
	 * expressed in microseconds.
	 *
	 * For example, if a spring daylight savings cutover is from 01:59 to 03:00
	 * then adding one hour to 01:30 will result in 03:30. This is a duration of
	 * one hour later, even though the hour field value changed from 1 to 3.
	 *
	 * Returns $this if $hours is 0.
	 *
	 * @param int $hours the number of hours to add
	 *
	 * @throws InvalidArgumentException if $hours is not an int
	 *
	 * @returns \Bronto\Date\DateTime a copy of this datetime after adding the
	 * specified number of hours
	 */
	public function plusHours($hours) {
		$value = \Bronto\Util\Preconditions::requireInt($hours, '$hours');
		return $this->plusMicroseconds($hours * self::MICROS_PER_HOUR);
	}

	/**
	 * Returns a copy of this datetime after substracting the specified number of
	 * hours.
	 *
	 * The calculation will substract a duration equivalent to the number of hours
	 * expressed in microseconds.
	 *
	 * For example, if a spring daylight savings cutover is from 01:59 to 03:00
	 * then substracting one hour from 03:30 will result in 01:30. This is a duration of
	 * one hour earier, even though the hour field value changed from 3 to 1.
	 *
	 * Returns $this if $hours is 0.
	 *
	 * @param int $hours the number of hours to substract
	 *
	 * @throws InvalidArgumentException if $hours is not an int
	 *
	 * @returns \Bronto\Date\DateTime a copy of this datetime after substracting the
	 * specified number of hours
	 */
	public function minusHours($hours) {
		$value = \Bronto\Util\Preconditions::requireInt($hours, '$hours');
		return $this->plusHours(-$hours);
	}

	/**
	 * Returns a copy of this datetime after adding the specified number of
	 * minutes.
	 *
	 * The calculation will add a duration equivalent to the number of minutes
	 * expressed in microseconds.
	 *
	 * For example, if a spring daylight savings cutover is from 01:59 to 03:00
	 * then adding one minute to 01:59 will result in 03:00. This is a duration of
	 * one minute later, even though the hour field value changed from 1 to 3.
	 *
	 * Returns $this if $minutes is 0.
	 *
	 * @param int $minutes the number of minutes to add
	 *
	 * @throws InvalidArgumentException if $minutes is not an int
	 *
	 * @returns \Bronto\Date\DateTime a copy of this datetime after adding the
	 * specified number of minutes
	 */
	public function plusMinutes($minutes) {
		$value = \Bronto\Util\Preconditions::requireInt($minutes, '$minutes');
		return $this->plusMicroseconds($minutes * self::MICROS_PER_MINUTE);
	}

	/**
	 * Returns a copy of this datetime after substracting the specified number of
	 * minutes.
	 *
	 * The calculation will substract a duration equivalent to the number of minutes
	 * expressed in microseconds.
	 *
	 * For example, if a spring daylight savings cutover is from 01:59 to 03:00
	 * then substracting one minute from 03:00 will result in 01:59. This is a duration of
	 * one minute earier, even though the hour field value changed from 3 to 1.
	 *
	 * Returns $this if $minutes is 0.
	 *
	 * @param int $minutes the number of minutes to substract
	 *
	 * @throws InvalidArgumentException if $minutes is not an int
	 *
	 * @returns \Bronto\Date\DateTime a copy of this datetime after substracting the
	 * specified number of minutes
	 */
	public function minusMinutes($minutes) {
		$value = \Bronto\Util\Preconditions::requireInt($minutes, '$minutes');
		return $this->plusMinutes(-$minutes);
	}

	/**
	 * Returns a copy of this datetime after adding the specified number of
	 * seconds.
	 *
	 * The calculation will add a duration equivalent to the number of seconds
	 * expressed in microseconds.
	 *
	 * For example, if a spring daylight savings cutover is from 01:59 to 03:00
	 * then adding one second to 01:59:59 will result in 03:00:00. This
	 * is a duration of one second later, even though the hour field value
	 * changed from 1 to 3.
	 *
	 * Returns $this if $seconds is 0.
	 *
	 * @param int $seconds the number of seconds to add
	 *
	 * @throws InvalidArgumentException if $seconds is not an int
	 *
	 * @returns \Bronto\Date\DateTime a copy of this datetime after adding the
	 * specified number of seconds
	 */
	public function plusSeconds($seconds) {
		$value = \Bronto\Util\Preconditions::requireInt($seconds, '$seconds');
		return $this->plusMicroseconds($seconds * self::MICROS_PER_SECOND);
	}

	/**
	 * Returns a copy of this datetime after substracting the specified number of
	 * seconds.
	 *
	 * The calculation will substract a duration equivalent to the number of seconds
	 * expressed in microseconds.
	 *
	 * For example, if a spring daylight savings cutover is from 01:59 to 03:00
	 * then substracting one second from 03:00:00 will result in 01:59:59. This
	 * is a duration of one second earier, even though the hour field value
	 * changed from 3 to 1.
	 *
	 * Returns $this if $seconds is 0.
	 *
	 * @param int $seconds the number of seconds to substract
	 *
	 * @throws InvalidArgumentException if $seconds is not an int
	 *
	 * @returns \Bronto\Date\DateTime a copy of this datetime after substracting the
	 * specified number of seconds
	 */
	public function minusSeconds($seconds) {
		$value = \Bronto\Util\Preconditions::requireInt($seconds, '$seconds');
		return $this->plusSeconds(-$seconds);
	}

	/**
	 * Returns a copy of this datetime after adding the specified number of
	 * milliseconds.
	 *
	 * The calculation will add a duration equivalent to the number of milliseconds
	 * expressed in microseconds.
	 *
	 * For example, if a spring daylight savings cutover is from 01:59 to 03:00
	 * then adding one millisecond to 01:59:59 will result in 03:00:00. This
	 * is a duration of one millisecond later, even though the hour field value
	 * changed from 1 to 3.
	 *
	 * Returns $this if $millis is 0.
	 *
	 * @param int $millis the number of milliseconds to add
	 *
	 * @throws InvalidArgumentException if $millis is not an int
	 *
	 * @returns \Bronto\Date\DateTime a copy of this datetime after adding the
	 * specified number of milliseconds
	 */
	public function plusMilliseconds($millis) {
		$millis = \Bronto\Util\Preconditions::requireInt($millis, '$millis');
		return static::plusMicroseconds($millis * self::MICROS_PER_MILLI);
	}

	/**
	 * Returns a copy of this datetime after substracting the specified number of
	 * milliseconds.
	 *
	 * The calculation will substract a duration equivalent to the number of milliseconds
	 * expressed in microseconds.
	 *
	 * For example, if a spring daylight savings cutover is from 01:59 to 03:00
	 * then substracting one millisecond from 03:00:00.000 will result in
	 * 01:59:59.999. This is a duration of one millisecond earier, even though
	 * the hour field value changed from 3 to 1.
	 *
	 * Returns $this if $millis is 0.
	 *
	 * @param int $millis the number of milliseconds to substract
	 *
	 * @throws InvalidArgumentException if $millis is not an int
	 *
	 * @returns \Bronto\Date\DateTime a copy of this datetime after substracting the
	 * specified number of milliseconds
	 */
	public function minusMilliseconds($millis) {
		$millis = \Bronto\Util\Preconditions::requireInt($millis, '$millis');
		return $this->plusMilliseconds(-$millis);
	}

	/**
	 * Returns a copy of this datetime after adding the specified number of
	 * microseconds.
	 *
	 * The calculation will add a duration of microseconds.
	 *
	 * For example, if a spring daylight savings cutover is from 01:59 to 03:00
	 * then adding one microsecond to 01:59:59.999999 will result in
	 * 03:00:00.000000. This is a duration of one microsecond later, even though
	 * the hour field value changed from 1 to 3.
	 *
	 * Returns $this if $micros is 0.
	 *
	 * @param int $micros the number of microseconds to add
	 *
	 * @throws InvalidArgumentException if $micros is not an int
	 *
	 * @returns \Bronto\Date\DateTime a copy of this datetime after adding the
	 * specified number of microseconds
	 */
	public function plusMicroseconds($micros) {
		$micros = \Bronto\Util\Preconditions::requireInt($micros, '$micros');

		if ($micros == 0) {
			return $this;
		}

		// additional microseconds + current microseconds
		$micros = $micros + $this->getMicrosOfSecond();
		// calculate the number of whole seconds we need to adjust by
		$seconds = intval(floor($micros / self::MICROS_PER_SECOND));
		// calculate the remaining number of microseconds 
		$micros = $micros - ($seconds * self::MICROS_PER_SECOND);
		// current timestamp + additional seconds
		$timestamp = $this->getTimestamp() + $seconds;

		return static::fromMicrosTimestamp($timestamp, $micros, $this->getTimeZone());
	}

	/**
	 * Returns a copy of this datetime after substracting the specified number of
	 * microseconds.
	 *
	 * The calculation will substract a duration of microseconds.
	 *
	 * For example, if a spring daylight savings cutover is from 01:59 to 03:00
	 * then substracting one microsecond from 03:00:00.00000 will result in
	 * 01:59:59.999999. This is a duration of one microsecond earier, even though
	 * the hour field value changed from 3 to 1.
	 *
	 * Returns $this if $micros is 0.
	 *
	 * @param int $micros the number of microseconds to substract
	 *
	 * @throws InvalidArgumentException if $micros is not an int
	 *
	 * @returns \Bronto\Date\DateTime a copy of this datetime after substracting the
	 * specified number of microseconds
	 */
	public function minusMicroseconds($micros) {
		$micros = \Bronto\Util\Preconditions::requireInt($micros, '$micros');
		return $this->plusMicroseconds(-$micros);
	}

	/**
	 * Returns a copy of this datetime with the specified date, retaining the
	 * time fields when possible.
	 *
	 * It will do its best to only change the date fields retaining
	 * the same time of day. However, in certain circumstances, typically
	 * daylight savings cutover, it may be necessary to alter the time fields.
	 *
	 * In spring an hour is typically removed. If using a different date results
	 * in the time being within the cutover then the time is adjusted to be
	 * within summer time. For example, if the cutover is from 01:59 to 03:00 and the
	 * result of this method would have been 02:30, then the result will be
	 * adjusted to 03:30.
	 *
	 * If the date is already the date passed in, then $this is returned.
	 *
	 * @param int $year the year
	 * @param int $month the month of the year
	 * @param int $day the day of the month
	 *
	 * @throws InvalidArgumentException if $year, $month or $day are not each
	 * integers
	 * @throws InvalidArgumentException if $month is not within [1, 12]
	 * @throws InvalidArgumentException if $day is not valid given $year and
	 * $month
	 *
	 * @returns a copy of this datetime with the specified date
	 */
	public function withDate($year, $month, $day) {
		$year = \Bronto\Util\Preconditions::requireInt($year, '$year');
		$month = \Bronto\Util\Preconditions::requireInt($month, '$month');
		$day = \Bronto\Util\Preconditions::requireInt($day, '$day');

		static::validateMonth($month);
		static::validateDayOfMonth($year, $month, $day);

		if ($year == $this->getYear() &&
				$month == $this->getMonthOfYear() &&
				$day == $this->getDayOfMonth()) {
			return $this;
		}

		$tmpDate = clone $this->dateTime;
		$result = $tmpDate->setDate($year, $month, $day);
		
		if ($result === false) {
			throw new \InvalidArgumentException("Invalid date. \$year = $year ; \$month = $month ; \$day = $day");
		}

		$timestamp = Utils::extractTimestamp($tmpDate);
		return static::fromMicrosTimestamp($timestamp, $this->getMicrosOfSecond(), $this->getTimeZone());
	}

	/**
	 * Validate $month falls within the range [1,12].
	 *
	 * @param int $month the month of the year
	 *
	 * @throws InvalidArgumentException if $month does not within range [1,12]
	 */
	private static function validateMonth($month) {
		if ($month < 1 || $month > 12) {
			throw new \InvalidArgumentException("Invalid date. The month must be in the range [1,12]. \$month = $month");
		}
	}

	/**
	 * Validate $day is valid given $year and $month. For example, 2013-02-29
	 * would fail since 2013 is not a leap year.
	 *
	 * @param int $year the year
	 * @param int $month the month of the year
	 * @param int $day the day of the month
	 *
	 * @throws InvalidArgumentException if $day is not valid given $year and
	 * $month
	 */
	private static function validateDayOfMonth($year, $month, $day) {
		$maxDay = cal_days_in_month(CAL_GREGORIAN, $month, $year);
		if ($day < 1 || $day > $maxDay) {
			throw new \InvalidArgumentException("Invalid date. The day must be in the range [1,$maxDay]. \$year = $year, \$month = $month, \$day = $day");
		}
	}

	/**
	 * Returns a copy of this datetime with the specified year, retaining the
	 * month field. The day and time fields are retained when possible.
	 *
	 * It will do its best to only change the year field retaining the same day
	 * and time. However, in certain circumstances it may be necessary to alter
	 * these fields.
	 *
	 * For example, 2012-02-29 is a valid date, but if the year is changed to
	 * 2013, then it results in an invalid date as 2013 is not a leap year. The
	 * date in this case is adjusted to 2013-02-28.
	 *
	 * In theory daylight savings transition rules could also result in time
	 * field changes if the rules change from year to year for the given time
	 * zone.
	 *
	 * If the year is already the year passed in, then $this is returned.
	 *
	 * @param int $year the year
	 *
	 * @throws InvalidArgumentException if $year is not an int
	 */
	public function withYear($year) {
		$year = \Bronto\Util\Preconditions::requireInt($year, '$year');
		
		$thisYear = $this->getYear();
		if ($year == $thisYear) {
			return $this;
		}

		$delta = $year - $thisYear;
		return $this->plusYears($delta);
	}

	/**
	 * Returns a copy of this datetime with the specified month, retaining the
	 * year field. The day and time fields are retained when possible.
	 *
	 * It will do its best to only change the month field retaining the same day
	 * and time. However, in certain circumstances it may be necessary to alter
	 * these fields.
	 *
	 * For example, 2013-01-30 is a valid date, but if the month is changed to
	 * 02, then it results in an invalid date as February only has 28 days in
	 * 2013. The date in this case is adjusted to 2013-02-28.
	 *
	 * Daylight savings transition rules can result in the time fields changing. 
	 * In spring an hour is typically removed. If using a different month results
	 * in the time being within the cutover then the time is adjusted to be
	 * within summer time. For example, if the cutover is from 01:59 to 03:00 and the
	 * result of this method would have been 02:30, then the result will be
	 * adjusted to 03:30.
	 *
	 * If the month is already the month passed in, then $this is returned.
	 *
	 * @param int $month the month
	 *
	 * @throws InvalidArgumentException if $month is not an int
	 * @throws InvalidArgumentException if $month is not within range [1,12]
	 */
	public function withMonthOfYear($month) {
		$month = \Bronto\Util\Preconditions::requireInt($month, '$month');
		static::validateMonth($month);

		$thisMonthOfYear = $this->getMonthOfYear();
		if ($month == $thisMonthOfYear) {
			return $this;
		}

		$delta = $month - $thisMonthOfYear;
		return $this->plusMonths($delta);
	}

	/**
	 * Returns a copy of this datetime with the specified day, retaining the
	 * year and month fields. The time fields are retained when possible.
	 *
	 * It will do its best to only change the day field retaining the same time
	 * fields. However, in certain circumstances it may be necessary to alter
	 * these fields.
	 *
	 * Daylight savings transition rules can result in the time fields changing. 
	 * In spring an hour is typically removed. If using a different month results
	 * in the time being within the cutover then the time is adjusted to be
	 * within summer time. For example, if the cutover is from 01:59 to 03:00 and the
	 * result of this method would have been 02:30, then the result will be
	 * adjusted to 03:30.
	 *
	 * If the month is already the month passed in, then $this is returned.
	 *
	 * @param int $day the day
	 *
	 * @throws InvalidArgumentException if $day is not an int
	 * @throws InvalidArgumentException if $day is not valid given the $month
	 * and $year
	 */
	public function withDayOfMonth($day) {
		$day = \Bronto\Util\Preconditions::requireInt($day, '$day');
		static::validateDayOfMonth($this->getYear(), $this->getMonthOfYear(), $day);

		$thisDayOfMonth = $this->getDayOfMonth();
		if ($day == $thisDayOfMonth) {
			return $this;
		}

		$delta = $day - $thisDayOfMonth;
		return $this->plusDays($delta);
	}

	/**
	 * Returns a copy of this datetime with the specified time, retaining the
	 * date fields.
	 *
	 * Take note, some date and time combinations are invalid due to daylight
	 * savings transition rules. In spring an hour is typically removed. For
	 * example, if the cutover is from 01:59 to 03:00 and this method attempts
	 * to set the time to 02:30, then this method will throw an
	 * InvalidArgumentException.
	 *
	 * If the time matches the time passed in then $this is returned.
	 *
	 * @param int $hour the hour of the day; must be within range [0,23]
	 * @param int $minute the minute of the hour; must be within range [0,59]
	 * @param int $second the second of the minute; must be within range [0,59]
	 * @param int $microsecond the mircosecond of the second; must be within
	 * range [0,999999]
	 *
	 * @throws InvalidArgumentException if $hour, $minute, $second or
	 * $microsecond is not an int
	 * @throws InvalidArgumentException if $hour, $minute, $second or
	 * $microsecond falls outside its defined range
	 * @throws InvalidArgumentException if the time passed in results in an
	 * invalid datetime given the year, month and day
	 */
	public function withTime($hour, $minute, $second, $microsecond) {
		$hour = \Bronto\Util\Preconditions::requireInt($hour, '$hour');
		$minute = \Bronto\Util\Preconditions::requireInt($minute, '$minute');
		$second = \Bronto\Util\Preconditions::requireInt($second, '$second');
		$microsecond = \Bronto\Util\Preconditions::requireInt($microsecond, '$microsecond');

		if ($hour < 0 || $hour > 23) {
			throw new \InvalidArgumentException("Invalid time. The hour must be in range [0,23]. \$hour = $hour");
		}

		if ($minute < 0 || $minute > 59) {
			throw new \InvalidArgumentException("Invalid time. The minute must be in range [0,59]. \$minute = $minute");
		}

		if ($second < 0 || $second > 59) {
			throw new \InvalidArgumentException("Invalid time. The second must be in range [0,59]. \$second = $second");
		}

		if ($microsecond < 0 || $microsecond > 999999) {
			throw new \InvalidArgumentException("Invalid time. The microsecond must be in range [0,999999]. \$microsecond = $microsecond");
		}

		if ($hour == $this->getHourOfDay() &&
				$minute == $this->getMinuteOfHour() &&
				$second == $this->getSecondOfMinute() &&
				$microsecond == $this->getMicrosOfSecond()) {
			return $this;
		}

		$tmpDate = clone $this->dateTime;
		$result = $tmpDate->setTime($hour, $minute, $second);

		if ($result === false) {
			throw new \InvalidArgumentException("Invalid time. \$hour = $hour ; \$minute = $minute ; \$second = $second ; \$microseconds = $microseconds");
		}

		$timestamp = Utils::extractTimestamp($tmpDate);
		$dateTime = static::fromMicrosTimestamp($timestamp, $microsecond, $this->getTimeZone());
		// If the resulting time is different from the time passed in then we
		// know PHP has adjusted due to daylight savings transitions.
		if ($dateTime->getHourOfDay() !== $hour || $dateTime->getMinuteOfHour() !== $minute) {
			throw new \InvalidArgumentException("Illegal instant due to time zone offset transition ({$dateTime->getTimeZone()}). date = {$dateTime->toString(Format::MYSQL_DATE)} ; \$hour = $hour ; \$minute = $minute ; \$second = $second ; \$microseconds = $microsecond");
		}

		return $dateTime;
	}

	/**
	 * Returns a copy of this datetime with the specified hour of day, retaining
	 * the date, minute, second and microsecond fields.
	 *
	 * Take note, some date and time combinations are invalid due to daylight
	 * savings transition rules. In spring an hour is typically removed. For
	 * example, if the cutover is from 01:59 to 03:00 and this method attempts
	 * to set the time to 02:30, then this method will throw an
	 * InvalidArgumentException.
	 *
	 * If the hour matches the hour passed in then $this is returned.
	 *
	 * @param int $hour the hour of the day; must be within range [0,23]
	 *
	 * @throws InvalidArgumentException if $hour is not an int
	 * @throws InvalidArgumentException if $hour is not in range [0,23]
	 * @throws InvalidArgumentException if the time passed in results in an
	 * invalid datetime given the retained date and time fields
	 */
	public function withHourOfDay($hour) {
		return $this->withTime($hour, $this->getMinuteOfHour(), $this->getSecondOfMinute(), $this->getMicrosOfSecond());
	}

	/**
	 * Returns a copy of this datetime with the specified minute of the hour,
	 * retaining the date, hour, second and microsecond fields.
	 *
	 * Take note, some date and time combinations are invalid due to daylight
	 * savings transition rules. In spring an hour is typically removed. For
	 * example, if the cutover is from 01:59 to 03:00 and this method attempts
	 * to set the time to 02:30, then this method will throw an
	 * InvalidArgumentException.
	 *
	 * There do exist time zone offset transition rules that take effect at
	 * times other than on the hour. An InvalidArgumentException could be thrown
	 * as a result of manipulating the minute field of a datetime object on the
	 * boundry of one of these rules. For example, an hour is lost at
	 * '1919-03-30 23:30:00 America/Toronto' resulting in the immediate
	 * transition to '1919-03-31 00:30:00-04:00'. Therefore '1919-03-30 23:30:00
	 * America/Toronto' does not represent a valid instant in time and would
	 * result in an InvalidArgumentException. As of the writing of this
	 * documentation these sorts of daylight savings rules are very rare and
	 * typically (if not completely) only relevant historically.
	 *
	 * If the minute matches the minute passed in then $this is returned.
	 *
	 * @param int $minute the minute of the hour; must be within range [0,59]
	 *
	 * @throws InvalidArgumentException if $minute is not an int
	 * @throws InvalidArgumentException if $minute is not in range [0,59]
	 * @throws InvalidArgumentException if the time passed in results in an
	 * invalid datetime given the retained date and time fields
	 */
	public function withMinuteOfHour($minute) {
		return $this->withTime($this->getHourOfDay(), $minute, $this->getSecondOfMinute(), $this->getMicrosOfSecond());
	}

	/**
	 * Returns a copy of this datetime with the specified second of the minute,
	 * retaining the date, hour, minute and microsecond fields.
	 *
	 * If the second matches the second passed in then $this is returned.
	 *
	 * @param int $second the second of the minute; must be within range [0,59]
	 *
	 * @throws InvalidArgumentException if $second is not an int
	 * @throws InvalidArgumentException if $second is not in range [0,59]
	 */
	public function withSecondOfMinute($second) {
		return $this->withTime($this->getHourOfDay(), $this->getMinuteOfHour(), $second, $this->getMicrosOfSecond());
	}

	/**
	 * Returns a copy of this datetime with the specified microsecond of the
	 * second, retaining the date, hour, minute and second fields.
	 *
	 * If the microsecond matches the microsecond passed in then $this is returned.
	 *
	 * @param int $microsecond the microsecond of the second; must be within
	 * range [0,999999]
	 *
	 * @throws InvalidArgumentException if $micros is not an int
	 * @throws InvalidArgumentException if $micros is not in range [0,999999]
	 */
	public function withMicrosOfSecond($micros) {
		return $this->withTime($this->getHourOfDay(), $this->getMinuteOfHour(), $this->getSecondOfMinute(), $micros);
	}

	/**
	 * Returns a copy of this DateTime with the time zone updated. Returns the
	 * same object if this datetime's time zone matches the $timeZone argument.
	 *
	 * @param TimeZone|string $timeZone a TimeZone object or a string
	 * representing a time zone; either a time zone identifier or a UTC offset
	 *
	 * @throws \InvalidArgumentException if $timeZone is not a TimeZone object
	 * or a valid time zone string representation
	 *
	 * @return a copy of this DateTime with the time zone updated; or returns
	 * the same datetime object if the time zone already matches
	 */
	public function withTimeZone($timeZone) {
		$timeZone = Preconditions::requireTimeZone($timeZone, '$timeZone');

		if ($timeZone->equals($this->getTimeZone())) {
			return $this;
		}

		return static::fromMicrosTimestamp($this->getTimestamp(), $this->getMicrosOfSecond(), $timeZone);
	}

	/**
	 * Gets the seconds of the DateTime instant since epoch. This is the number of seconds
	 * since 1970-01-01 00:00:00 UTC.
	 *
	 * @return int this DateTime's instant represented as a unix timestamp
	 */
	public function getTimestamp() {
		return $this->timestamp;
	}


	/**
	 * Gets the milliseconds of the DateTime instant since epoch. This is the
	 * number of milliseconds since 1970-01-01 00:00:00 UTC.
	 *
	 * @return int the difference in milliseconds between Unix epoch and this
	 * DateTime's instant
	 */
	public function getMillisTimestamp() {
		return ($this->getTimestamp() * self::MILLIS_PER_SECOND) + $this->getMillisOfSecond();
	}

	/**
	 * Get the microsecond of the second.
	 *
	 * @return int the microsecond of the second
	 */
	public function getMicrosOfSecond() {
		return $this->micros;
	}

	/**
	 * Get the millisecond of the second.
	 *
	 * @return int the millisecond of the second
	 */
	public function getMillisOfSecond() {
		return intval(floor($this->micros / self::MICROS_PER_MILLI));
	}

	/**
	 * Get the second of the minute.
	 *
	 * @return int the second of the minute
	 */
	public function getSecondOfMinute() {
		return intval($this->toString('s'));
	}

	/**
	 * Get the minute of the hour.
	 *
	 * @return int the minute of the hour
	 */
	public function getMinuteOfHour() {
		return intval($this->toString('i'));
	}

	/**
	 * Get the hour of the day.
	 *
	 * @return int the hour of the day
	 */
	public function getHourOfDay() {
		return intval($this->toString('G'));
	}

	/**
	 * Get the day of the month.
	 *
	 * @return int the day of the month
	 */
	public function getDayOfMonth() {
		return intval($this->toString('d'));
	}

	/**
	 * Get the month of the year.
	 *
	 * @return int the month of the year
	 */
	public function getMonthOfYear() {
		return intval($this->toString('m'));
	}

	/**
	 * Get the year.
	 *
	 * @return int the year
	 */
	public function getYear() {
		return intval($this->toString('Y'));
	}

	/**
	 * Gets this DateTime's time zone.
	 *
	 * @return TimeZone a time zone
	 */
	public function getTimeZone() {
		return $this->timeZone;
	}

	/**
	 * Does this datetime's instant matches the provided datetime's?
	 *
	 * @param DateTime $date the datetime to be compared
	 *
	 * @throws InvalidArgumentException if $date is null
	 *
	 * @return boolean if this datetime's instant matches the provided
	 * datetime's.
	 */
	public function isInstantEqual(DateTime $date) {
		return $date->getTimestamp() == $this->getTimestamp() && $date->getMicrosOfSecond() == $this->getMicrosOfSecond();
	}

	/**
	 * Is this datetime after the provided datetime? This comparison is based
	 * solely on the datetimes' instant values.
	 *
	 * @param DateTime $date the datetime to be compared
	 *
	 * @throws InvalidArgumentException if $date is null
	 *
	 * @return boolean if this datetime is after the provided datetime
	 */
	public function isAfter(DateTime $date) {
		if ($this->getTimestamp() > $date->getTimestamp()) {
			return true;
		}

		if ($this->getTimestamp() < $date->getTimestamp()) {
			return false;
		}

		return $this->getMicrosOfSecond() > $date->getMicrosOfSecond();
	}

	/**
	 * Is this datetime after now?
	 *
	 * @return boolean if this datetime is after now
	 */
	public function isAfterNow() {
		return $this->isAfter(static::now());
	}

	/**
	 * Is this datetime before the provided datetime? This comparison is based
	 * solely on the datetimes' instant values.
	 *
	 * @param DateTime $date the datetime to be compared
	 *
	 * @throws InvalidArgumentException if $date is null
	 *
	 * @return boolean if this datetime is before the provided datetime
	 */
	public function isBefore(DateTime $date) {
		return $date->isAfter($this);
	}

	/**
	 * Is this datetime before now?
	 *
	 * @return boolean if this datetime is before now
	 */
	public function isBeforeNow() {
		return $this->isBefore(static::now());
	}

	/**
	 * Tests whether this datetime object matches $date. Both the timestamp
	 * instant values and associated time zones must match exactly.
	 *
	 * @param \Bronto\Date\DateTime $date a DateTime object
	 *
	 * @return boolean if both the timestamp instance values and time zones
	 * match
	 */
	public function equals(DateTime $date) {
		return $date->isInstantEqual($this) && $date->getTimeZone()->equals($this->getTimeZone());
	}

	/**
	 * Convert this DateTime object to another date/time object type. See
	 * \Bronto\Date\Type for supported conversion types.
	 *
	 * @param string $type the fully qualified class name of the date/time
	 * object type to which to convert
	 *
	 * @throw \InvalidArgumentException if $type is not a string
	 * @throws \InvalidArgumentException if $type is not a value defined in
	 * \Bronto\Date\Type
	 *
	 * @return mixed an object representing this DateTime object, type dictated
	 * by $type argument
	 */
	public function convertTo($type) {
		$type = \Bronto\Util\Preconditions::requireString($type, '$type');

		if ($type == Type::DATE_TIME) {
			return $this;
		}

		if ($type == Type::PHP_DATE_TIME) {
			return clone $this->dateTime;
		}

		throw new \InvalidArgumentException("The date type '$type' is not supported");
	}

	/**
	 * Convert this DateTime object to a string per the provided format string.
	 * See http://www.php.net/manual/en/function.date.php for formatting
	 * options.
	 *
	 * @param string $format the format string defines the format of the
	 * returned string
	 *
	 * @throws \InvalidArgumentException if $format is not a string
	 *
	 * @return string the DateTime object represented as a string per the given
	 * format string
	 */
	public function toString($format) {
		$format = \Bronto\Util\Preconditions::requireString($format, '$format');

		return $this->dateTime->format($format);
	}

	/**
	 * Convert this DateTime object to a human readable string, listing its
	 * timestamp, micros and timeZone properties.
	 * 
	 * @return string details about the DateTime object
	 */
	public function __toString() {
		return "DateTime [timestamp = {$this->timestamp} ; micros = {$this->micros} ; timeZone = {$this->timeZone}]";
	}

	/**
	 * Clone the DateTime object.
	 *
	 * @return \Bronto\Date\DateTime a copy of the object
	 */
	public function __clone() {
		$this->dateTime = clone $this->dateTime;
	}

}

