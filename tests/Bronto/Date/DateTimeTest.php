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
 * DateTime tests
 *
 * @covers Bronto\Date\DateTime
 */
class DateTimeTest extends AbstractTest {

	/**
	 * Data provider for tests that need non string arguments.
	 */
	public static function dataNonStrings() {
		return array(
				array(123),
				array(new \stdClass()),
				array(DateTime::now()),
				array(array(1)),
			);
	}

	/**
	 * Data provider for testFromTimestampValid
	 */
	public static function dataFromTimestampValid() {
		$data = array();

		$data[] = array(1368283531, 'America/New_York', '2013-05-11T10:45:31-04:00');
		$data[] = array(1368283531, 'America/Los_Angeles', '2013-05-11T07:45:31-07:00');
		$data[] = array(1383469200, 'America/Los_Angeles', '2013-11-03T01:00:00-08:00');
		$data[] = array(-1368283531, 'America/New_York', '1926-08-23T05:14:29-04:00');
		$data[] = array(-1368283531, 'America/Los_Angeles', '1926-08-23T01:14:29-08:00');

		return $data;
	}

	/**
	 * Tests fromTimestamp with valid input.
	 *
	 * @covers DateTime::fromTimestamp
	 * @covers DateTime::getTimestamp
	 * @covers DateTime::getTimeZone
	 * @dataProvider dataFromTimestampValid
	 */
	public function testFromTimestampValid($timestamp, $tz, $expectedString) {
		$tz = TimeZone::parse($tz);
		$date = DateTime::fromTimestamp($timestamp, $tz);

		$this->assertEquals($timestamp, $date->getTimestamp());
		$this->assertEquals($tz, $date->getTimeZone());
		$this->assertEquals($expectedString, $date->toString('c'));
	}

	/**
	 * Tests fromTimestamp with input that should result in the default time
	 * zone of UTC.
	 *
	 * @covers DateTime::fromTimestamp
	 * @covers DateTime::getTimestamp
	 * @covers DateTime::getTimeZone
	 */
	public function testFromTimestampUTCDefault() {
		$date = DateTime::fromTimestamp(0);
		$this->assertEquals(0, $date->getTimestamp());
		$this->assertEquals('UTC', strval($date->getTimeZone()));

		$date = DateTime::fromTimestamp(0, null);
		$this->assertEquals(0, $date->getTimestamp());
		$this->assertEquals('UTC', strval($date->getTimeZone()));
	}

	/**
	 * Tests fromTimestamp with invalid arguments.
	 *
	 * @covers DateTime::fromTimestamp
	 */
	public function testFromTimestampInvalidArgs() {
		$this->assertIntArg(function($value) {
				DateTime::fromTimestamp($value);
			});

		$this->assertTimeZoneArg(function($value) {
				DateTime::fromTimestamp(0, $value);
			});
	}

	/**
	 * Data provider for testFromMillisTimestampValid
	 */
	public static function dataFromMillisTimestampValid() {
		$data = array();

		$data[] = array(1368283531000, 'America/New_York', 1368283531, 0);
		$data[] = array(1368283531000, 'America/Los_Angeles', 1368283531, 0);
		$data[] = array(1383469200000, 'America/Los_Angeles', 1383469200, 0);
		$data[] = array(1368283531123, 'America/New_York', 1368283531, 123000);
		$data[] = array(1368283531001, 'America/Los_Angeles', 1368283531, 1000);
		$data[] = array(1383469200921, 'America/Los_Angeles', 1383469200, 921000);
		$data[] = array(-1383469200921, 'America/Los_Angeles', -1383469201, 79000);
		$data[] = array(-1368283531000, 'America/New_York', -1368283531, 0);
		$data[] = array(-1368283531000, 'America/Los_Angeles', -1368283531, 0);
		$data[] = array(-1383469200000, 'America/Los_Angeles', -1383469200, 0);
		$data[] = array(-1368283531123, 'America/New_York', -1368283532, 877000);
		$data[] = array(-1368283531001, 'America/Los_Angeles', -1368283532, 999000);

		return $data;
	}

	/**
	 * Tests fromMillisTimestamp with valid input.
	 *
	 * @covers DateTime::fromMillisTimestamp
	 * @covers DateTime::getTimestamp
	 * @covers DateTime::getMicrosOfSecond
	 * @covers DateTime::getTimeZone
	 * @dataProvider dataFromMillisTimestampValid
	 */
	public function testFromMillisTimestampValid($millisTimestamp, $tz, $expectedTimestamp, $expectedMicros) {
		$tz = TimeZone::parse($tz);
		$date = DateTime::fromMillisTimestamp($millisTimestamp, $tz);

		$this->assertEquals($tz, $date->getTimeZone());
		$this->assertEquals($expectedTimestamp, $date->getTimestamp());
		$this->assertEquals($expectedMicros, $date->getMicrosOfSecond());
	}

	/**
	 * Tests fromMillsTimestamp with input that should result in the default
	 * time zone of UTC.
	 *
	 * @covers DateTime::fromMillisTimestamp
	 * @covers DateTime::getTimestamp
	 * @covers DateTime::getMicrosOfSecond
	 * @covers DateTime::getTimeZone
	 */
	public function testFromMillisTimestampUTCDefault() {
		$date = DateTime::fromMillisTimestamp(0);
		$this->assertEquals(0, $date->getTimestamp());
		$this->assertEquals(0, $date->getMicrosOfSecond());
		$this->assertEquals('UTC', strval($date->getTimeZone()));

		$date = DateTime::fromMillisTimestamp(0, null);
		$this->assertEquals(0, $date->getTimestamp());
		$this->assertEquals(0, $date->getMicrosOfSecond());
		$this->assertEquals('UTC', strval($date->getTimeZone()));
	}

	/**
	 * Tests fromMillisTimestamp with invalid arguments.
	 *
	 * @covers DateTime::fromMillisTimestamp
	 */
	public function testFromMillisTimestampInvalidArgs() {
		$this->assertIntArg(function($value) {
				DateTime::fromMillisTimestamp($value);
			});

		$this->assertTimeZoneArg(function($value) {
				DateTime::fromMillisTimestamp(0, $value);
			});
	}

	/**
	 * Data provider for testFromMicrosTimestampValid
	 */
	public static function dataFromMicrosTimestampValid() {
		return array(
				array(0, 0, 'UTC'),
				array(1234, 999999, '+00:00'),
				array(1383469200, 0, 'America/Los_Angeles'),
				array(-100, 123, 'UTC'),
				);
	}

	/**
	 * Tests fromMicrosTimestamp with valid arguments.
	 *
	 * @dataProvider dataFromMicrosTimestampValid
	 * @covers DateTime::fromMicrosTimestamp
	 * @covers DateTime::getTimestamp
	 * @covers DateTime::getMicrosOfSecond
	 * @covers DateTime::getTimeZone
	 */
	public function testFromMicrosTimestampValid($timestamp, $micros, $timeZone) {
		$dateTime = DateTime::fromMicrosTimestamp($timestamp, $micros, $timeZone);
		$this->assertEquals($timestamp, $dateTime->getTimestamp());
		$this->assertEquals($micros, $dateTime->getMicrosOfSecond());
		$this->assertEquals($timeZone, $dateTime->getTimeZone()->getName());

		$timeZoneObject = TimeZone::parse($timeZone);
		$this->assertEquals($timestamp, $dateTime->getTimestamp());
		$this->assertEquals($micros, $dateTime->getMicrosOfSecond());
		$this->assertEquals($timeZone, $dateTime->getTimeZone()->getName());
	}

	/**
	 * Data provider for testFromMicrosTimestampInvalid.
	 */
	public static function dataFromMicrosTimestampInvalid() {
		return array(
				array(0, -1, 'UTC'),
				array(0, 1000000, 'UTC'),
				);
	}

	/**
	 * Tests fromMicrosTimestamp with invalid arguments.
	 *
	 * @dataProvider dataFromMicrosTimestampInvalid
	 * @expectedException InvalidArgumentException
	 * @covers DateTime::fromMicrosTimestamp
	 */
	public function testFromMicrosTimestampInvalid($timestamp, $micros, $timeZone) {
		DateTime::fromMicrosTimestamp($timestamp, $micros, $timeZone);
	}

	/**
	 * Tests fromMicrosTimestamp's argument validation.
	 *
	 * @overs DateTime::fromMicrosTimestamp
	 * @covers DateTime::getTimeZone
	 */
	public function testFromMicrosTimestampArgs() {
		$this->assertIntArg(function ($value) {
				DateTime::fromMicrosTimestamp($value, 0, 'UTC');
				});

		$this->assertIntArg(function ($value) {
				DateTime::fromMicrosTimestamp(0, $value, 'UTC');
				});

		$this->assertTimeZoneArg(function ($value) {
				DateTime::fromMicrosTimestamp(0, 0, $value);
				});

		$dateTime = DateTime::fromMicrosTimestamp(0, 0);
		$this->assertEquals('UTC', $dateTime->getTimeZone()->getName());
		$dateTime = DateTime::fromMicrosTimestamp(0, 0, null);
		$this->assertEquals('UTC', $dateTime->getTimeZone()->getName());
	}

	/**
	 * Data provider for testParseValid.
	 */
	public static function dataParseValid() {
		return array(
				array('2013-05-11 14:45:31 +0000', 'Y-m-d G:i:s O', DateTime::fromTimestamp(1368283531, '+00:00')),
				array('2013-05-11 15:45:31 +0100', 'Y-m-d G:i:s O', DateTime::fromTimestamp(1368283531, '+01:00')),
				array('2013-05-11 10:45:31 America/New_York', 'Y-m-d G:i:s O', DateTime::fromTimestamp(1368283531, 'America/New_York')),
				array('2013-05-11 14:45:31 UTC', 'Y-m-d G:i:s O', DateTime::fromTimestamp(1368283531, 'UTC')),
				array('2013-05-11 14:45:31.123456 UTC', 'Y-m-d G:i:s.u O', DateTime::fromMicrosTimestamp(1368283531, 123456, 'UTC')),
				array('2012', 'Y', DateTime::fromMicrosTimestamp(1325376000, 0, 'UTC')),
				array('2012-02', 'Y-m', DateTime::fromMicrosTimestamp(1328054400, 0, 'UTC')),
				array('2012-02-03', 'Y-m-d', DateTime::fromMicrosTimestamp(1328227200, 0, 'UTC')),
				array('02:2012:03', 'm:Y:d', DateTime::fromMicrosTimestamp(1328227200, 0, 'UTC')),
				array('2013-05-11 12:00:00', 'Y-m-d G:i:s', DateTime::fromTimestamp(1368273600, 'UTC')),
				array('2013-05-11 12:00:00-09:00', 'Y-m-d G:i:sP', DateTime::fromTimestamp(1368306000, '-0900')),
				array('2012-02-29', 'Y-m-d', DateTime::fromTimestamp(1330473600, 'UTC')),
				array('2013-03-10 03:30 America/Los_Angeles', 'Y-m-d H:i e', DateTime::fromTimestamp(1362911400, 'America/Los_Angeles')),
				array('2013-03-10 03:30 1234 America/Los_Angeles', 'Y-m-d H:i u e', DateTime::fromMicrosTimestamp(1362911400, 123400, 'America/Los_Angeles')),
				);
	}

	/**
	 * Tests parse with valid input.
	 *
	 * @dataProvider dataParseValid
	 * @covers DateTime::parse
	 * @covers DateTime::getTimestamp
	 * @covers DateTime::getMicrosOfSecond
	 * @covers DateTime::getTimeZone
	 */
	public function testParseValid($date, $format, DateTime $expected) {
		$dateTime = DateTime::parse($date, $format);
		$this->assertEquals($expected->getTimestamp(), $dateTime->getTimestamp());
		$this->assertEquals($expected->getMicrosOfSecond(), $dateTime->getMicrosOfSecond());
		$this->assertEquals($expected->getTimeZone()->getName(), $dateTime->getTimeZone()->getName());
	}

	/**
	 * Data provider for testParseInvalid.
	 */
	public static function dataParseInvalid() {
		$data = array(
				array('2013-05-11 14:45:31 UTC', 'blarg', null),
				array('2013-05-11 14:45:31', 'Y-m-d G:i:s O', null),
				array('2013-05-11 14:45:31 EST', 'Y-m-d G:i:s O', null),
				array('2013-05-11 14:45:31 EDT', 'Y-m-d G:i:s O', null),
				array('2012-01-32', 'Y-m-d', null),
				array('2012-02-30', 'Y-m-d', null),
				array('2012-02-15 00:62:00', 'Y-m-d H:i:s', null),
				array('America/New_York', 'e', 'UTC'),
				array('2013-05-06 01:23:45.123432 Europe/Paris', 'Y-m-d G:i:s.u e', 'America/New_York'),
				array('2013-05-06 01:23:45.123432 +01:00', 'Y-m-d G:i:s.u e', 'America/New_York'),
				array('2013-05-06 01:23:45.123432 -04:00', 'Y-m-d G:i:s.u e', 'America/New_York'),
				array('2013-05-06 01:23:45.123432 Europe/Paris', 'Y-m-d G:i:s.u e', '+01:00'),

				// Years divisible by 100 by not by 400 are NOT leap years
				array('1900-02-29 01:01:01 UTC', 'Y-m-d G:i:s e', 'UTC'),
				array('2100-02-29 01:01:01 UTC', 'Y-m-d G:i:s e', 'UTC'),
				array('2013-03-10 02:30 America/Los_Angeles', 'Y-m-d H:i e', null),
				);

		foreach (self::dataNonStrings() as $nonString) {
			$data[] = array($nonString, 'Y-m-d G:i:s O', null);
			$data[] = array('2013-05-11 14:45:31 UTC', $nonString, null);
			$data[] = array('2013-05-11 14:45:31 UTC', 'Y-m-d G:i:s O', $nonString);
		}

		return $data;
	}

	/**
	 * Tests parse with invalid input.
	 *
	 * @dataProvider dataParseInvalid
	 * @covers DateTime::parse
	 * @expectedException \InvalidArgumentException
	 */
	public function testParseInvalid($date, $format, $zone) {
		$timeZone = null;
		if ($zone) {
			$timeZone = TimeZone::parse($zone);
		}

		DateTime::parse($date, $format, $timeZone);
	}

	/**
	 * Data provider for testParseWithTimeZone
	 */
	public static function dataParseWithTimeZone() {
		return array(
				array('2013-05-06 00:00:00', 'Y-m-d G:i:s', 'America/New_York', 1367812800),
				array('2013-05-06 00:00:00', 'Y-m-d G:i:s', 'America/Los_Angeles', 1367823600),
				array('2013-03-10 01:30', 'Y-m-d G:i', 'America/Los_Angeles', 1362907800),
				array('2013-05-06 00:00:00 America/New_York', 'Y-m-d G:i:s e', 'America/New_York', 1367812800),
				array('2013-05-06 00:00:00 America/Los_Angeles', 'Y-m-d G:i:s e', 'America/Los_Angeles', 1367823600),
				array('2013-03-10 01:30 America/Los_Angeles', 'Y-m-d G:i e', 'America/Los_Angeles', 1362907800),
				array('UTC', 'e', 'UTC', 0),
				);
	}

	/**
	 * Tests the parse method with TimeZone arguments.
	 *
	 * @covers DateTime::parse
	 * @dataProvider dataParseWithTimeZone
	 * @covers DateTime::getTimestamp
	 */
	public function testParseWithTimeZone($date, $format, $zone, $expected) {
		$timeZone = TimeZone::parse($zone);
		$dateTime = DateTime::parse($date, $format, $timeZone);
		$this->assertEquals($expected, $dateTime->getTimestamp());
	}

	/**
	 * Data provider for testCreatedFromValid.
	 */
	public static function dataCreateFromValid() {
		$data = array();

		$dateTime = new \DateTime();
		$dateTime->setTimestamp(1368283531);
		$dateTime->setTimezone(new \DateTimeZone('America/Los_Angeles'));
		$data[] = array($dateTime, DateTime::fromTimestamp(1368283531, 'America/Los_Angeles'));

		$dateTime = new \DateTime();
		$dateTime->setTimestamp(1368283531);
		$dateTime->setTimezone(new \DateTimeZone('America/New_York'));
		$data[] = array($dateTime, DateTime::fromTimestamp(1368283531, 'America/New_York'));

		$dateTime = new \DateTime('2013-05-11 7:45:31 America/Los_Angeles');
		$data[] = array($dateTime, DateTime::fromTimestamp(1368283531, 'America/Los_Angeles'));

		$dateTime = new \DateTime('2013-05-11 7:45:31.234567 America/Los_Angeles');
		$data[] = array($dateTime, DateTime::fromMicrosTimestamp(1368283531, 234567, 'America/Los_Angeles'));

		$dateTime = DateTime::fromTimestamp(1368283531, 'America/Los_Angeles');
		$data[] = array($dateTime, $dateTime);

		return $data;
	}

	/**
	 * Tests createFrom with valid input.
	 *
	 * @covers DateTime::createFrom
	 * @dataProvider dataCreateFromValid
	 * @covers DateTime::getTimestamp
	 * @covers DateTime::getTimeZone
	 */
	public function testCreateFromValid($object, $expected) {
		$date = DateTime::createFrom($object);
		$this->assertEquals($expected->getTimestamp(), $date->getTimestamp());
		$this->assertEquals($expected->getTimeZone()->getName(), $date->getTimeZone()->getName());
	}

	/**
	 * Tests the exact same object is returned when a Bronto\Date\DateTime object
	 * is passed into createFrom.
	 *
	 * @covers DateTime::createFrom
	 */
	public function testCreateFromIdentity() {
		$date = DateTime::fromTimestamp(1368283531, 'America/Los_Angeles');
		$this->assertTrue($date === DateTime::createFrom($date));
	}

	/**
	 * Invalid data for testCreateFromInvalid.
	 */
	public static function dataCreateFromInvalid() {
		return array(
				array(1),
				array(true),
				array(null),
				array(new \stdClass()),
				array(array(1)),
				array('1970'),
				);
	}

	/**
	 * Tests createFrom with invalid input.
	 *
	 * @covers DateTime::createFrom
	 * @expectedException InvalidArgumentException
	 * @dataProvider dataCreateFromInvalid
	 */
	public function testCreateFromInvalid($object) {
		DateTime::createFrom($object);
	}

	/**
	 * Tests the now method with no args.
	 *
	 * @covers DateTime::now
	 * @covers DateTime::getTimestamp
	 * @covers DateTime::getTimeZone
	 */
	public function testNow() {
		$timestamp = time();
		$date = DateTime::now();
		$this->assertEquals('UTC', $date->getTimeZone()->getName());
		$this->assertLessThanOrEqual($timestamp, $date->getTimestamp());
		$this->assertGreaterThan($date->getTimestamp(), $timestamp + 10);

		$this->assertTimeZoneArg(function($value) {
			DateTime::now($value);		
		});
	}

	/**
	 * Valid data for testNowTZ
	 */
	public static function dataNowTZ() {
		return array(
				array('+0000', '+00:00'),
				array('America/New_York', 'America/New_York'),
				array('UTC', 'UTC'),
				array(null, 'UTC'),
				array(TimeZone::parse('+1234'), '+12:34'),
				array(TimeZone::parse('America/Los_Angeles'), 'America/Los_Angeles'),
				);
	}

	/**
	 * Tests now with a variety of time zone inputs.
	 *
	 * @covers DateTime::now
	 * @covers DateTime::getTimestamp
	 * @covers DateTime::getTimeZone
	 * @dataProvider dataNowTZ
	 */
	public function testNowTZ($timeZone, $expected) {
		$timestamp = time();
		$date = DateTime::now($timeZone);
		$this->assertEquals($expected, $date->getTimeZone()->getName());
		$this->assertLessThanOrEqual($date->getTimestamp(), $timestamp);
		$this->assertGreaterThan($date->getTimestamp(), $timestamp + 10);
	}

	/**
	 * Data provider for testChangeTimestampValid.
	 */
	public static function dataChangeTimestampValid() {
		return array(
				array('plusMicroseconds', 1, 60, 123456, 60, 123457),
				array('plusMicroseconds', 1, 60, 0, 60, 1),
				array('plusMicroseconds', 1000001, 60, 123456, 61, 123457),
				array('plusMicroseconds', 61000005, 60, 123456, 121, 123461),
				array('plusMicroseconds', -1, 60, 123456, 60, 123455),
				array('plusMicroseconds', -123456, 60, 123456, 60, 0),
				array('plusMicroseconds', -123457, 60, 123456, 59, 999999),
				array('plusMicroseconds', -5123457, 6, 123456, 0, 999999),

				array('minusMicroseconds', 1, 60, 123456, 60, 123455),
				array('minusMicroseconds', 1, 60, 0, 59, 999999),
				array('minusMicroseconds', 1000001, 60, 0, 58, 999999),
				array('minusMicroseconds', 123456, 60, 123456, 60, 0),
				array('minusMicroseconds', -1, 60, 123456, 60, 123457),
				array('minusMicroseconds', -1, 60, 999999, 61, 0),
				array('minusMicroseconds', -1000001, 60, 0, 61, 1),
				array('minusMicroseconds', -123456, 60, 0, 60, 123456),

				array('plusMilliseconds', 1, 60, 123456, 60, 124456),
				array('plusMilliseconds', 1000, 60, 123456, 61, 123456),
				array('plusMilliseconds', -1, 60, 123456, 60, 122456),
				array('plusMilliseconds', -1000, 60, 123456, 59, 123456),

				array('minusMilliseconds', 1, 60, 123456, 60, 122456),
				array('minusMilliseconds', 1000, 60, 123456, 59, 123456),
				array('minusMilliseconds', -1, 60, 123456, 60, 124456),
				array('minusMilliseconds', -1000, 60, 123456, 61, 123456),

				array('plusSeconds', 1, 60, 123456, 61, 123456),
				array('plusSeconds', 1000, 60, 123456, 1060, 123456),
				array('plusSeconds', -1, 60, 123456, 59, 123456),
				array('plusSeconds', -1000, 60, 123456, -940, 123456),

				array('minusSeconds', 1, 60, 123456, 59, 123456),
				array('minusSeconds', 1000, 60, 123456, -940, 123456),
				array('minusSeconds', -1, 60, 123456, 61, 123456),
				array('minusSeconds', -1000, 60, 123456, 1060, 123456),

				array('plusMinutes', 1, 0, 5, 60, 5),
				array('plusMinutes', 1, 60, 5, 120, 5),
				array('plusMinutes', -1, 60, 5, 0, 5),
				array('plusMinutes', -5, 60, 5, -240, 5),

				array('minusMinutes', -1, 0, 5, 60, 5),
				array('minusMinutes', -1, 60, 5, 120, 5),
				array('minusMinutes', 1, 60, 5, 0, 5),
				array('minusMinutes', 5, 60, 5, -240, 5),
		);
	}

	/**
	 * Tests plusMicroseconds with valid input
	 *
	 * @covers DateTime::plusMicroseconds
	 * @covers DateTime::minusMicroseconds
	 * @covers DateTime::plusMilliseconds
	 * @covers DateTime::minusMilliseconds
	 * @covers DateTime::plusMicroseconds
	 * @covers DateTime::minusMicroseconds
	 * @covers DateTime::plusMilliseconds
	 * @covers DateTime::minusMilliseconds
	 * @covers DateTime::plusSeconds
	 * @covers DateTime::minusSeconds
	 * @covers DateTime::plusMinutes
	 * @covers DateTime::minusMinutes
	 * @dataProvider dataChangeTimestampValid
	 */
	public function testChangeTimestampValid($methodName, $value, $beforeTimestamp, $beforeMicros, $afterTimestamp, $afterMicros) {
		$after = DateTime::fromMicrosTimestamp($afterTimestamp, $afterMicros);
		$before = DateTime::fromMicrosTimestamp($beforeTimestamp, $beforeMicros);
		$beforeString = $before->toString('c');
		$this->assertEquals($after->toString('U.u'), $before->$methodName($value)->toString('U.u'));
		$this->assertEquals($beforeString, $before->toString('c'));
	}

	/**
	 * Data provider for any tests that need plus* and minus* method names.
	 */
	public static function dataChangeTimestampMethods() {
		return array(
				array('plusMicroseconds'),
				array('minusMicroseconds'),
				array('plusMilliseconds'),
				array('minusMilliseconds'),
				array('plusSeconds'),
				array('minusSeconds'),
				array('plusMinutes'),
				array('minusMinutes'),
				array('plusHours'),
				array('minusHours'),
				array('plusDays'),
				array('minusDays'),
				array('plusMonths'),
				array('minusMonths'),
				array('plusYears'),
				array('minusYears'),
				);
	}

	/**
	 * Test that any plus* or minus* methods return the exact same object when
	 * zero is passed in.
	 *
	 * @covers DateTime::plusMicroseconds
	 * @covers DateTime::minusMicroseconds
	 * @covers DateTime::plusMilliseconds
	 * @covers DateTime::minusMilliseconds
	 * @covers DateTime::plusMicroseconds
	 * @covers DateTime::minusMicroseconds
	 * @covers DateTime::plusMilliseconds
	 * @covers DateTime::minusMilliseconds
	 * @covers DateTime::plusSeconds
	 * @covers DateTime::minusSeconds
	 * @covers DateTime::plusMinutes
	 * @covers DateTime::minusMinutes
	 * @covers DateTime::plusHours
	 * @covers DateTime::minusHours
	 * @covers DateTime::plusDays
	 * @covers DateTime::minusDays
	 * @covers DateTime::plusMonths
	 * @covers DateTime::minusMonths
	 * @covers DateTime::plusYears
	 * @covers DateTime::minusYears
	 * @dataProvider dataChangeTimestampMethods
	 */
	public function testChangeTimestampZero($methodName) {
		$now = DateTime::now();
		$this->assertTrue($now === $now->$methodName(0));
	}

	/**
	 * Data provider for testChangeTimestampUpperValid
	 */
	public static function dataChangeTimestampUpperValid() {
		return array(
				array('plusHours', 1, '1970-01-01 00', '1970-01-01 01'),
				array('plusHours', 10, '1970-01-01 00', '1970-01-01 10'),
				array('plusHours', 24, '1970-01-01 00', '1970-01-02 00'),
				array('plusHours', -1, '1970-01-01 00', '1969-12-31 23'),
				array('plusHours', -10, '1970-01-01 00', '1969-12-31 14'),

				array('minusHours', -1, '1970-01-01 00', '1970-01-01 01'),
				array('minusHours', -10, '1970-01-01 00', '1970-01-01 10'),
				array('minusHours', -24, '1970-01-01 00', '1970-01-02 00'),
				array('minusHours', 1, '1970-01-01 00', '1969-12-31 23'),
				array('minusHours', 10, '1970-01-01 00', '1969-12-31 14'),

				array('plusDays', 1, '1970-01-01 00', '1970-01-02 00'),
				array('plusDays', 10, '1970-01-01 00', '1970-01-11 00'),
				array('plusDays', 31, '1970-01-01 00', '1970-02-01 00'),
				array('plusDays', -1, '1970-01-01 00', '1969-12-31 00'),

				array('minusDays', -1, '1970-01-01 00', '1970-01-02 00'),
				array('minusDays', -10, '1970-01-01 00', '1970-01-11 00'),
				array('minusDays', -31, '1970-01-01 00', '1970-02-01 00'),
				array('minusDays', 1, '1970-01-01 00', '1969-12-31 00'),

				array('plusMonths', 1, '1970-01-01 00', '1970-02-01 00'),
				array('plusMonths', 10, '1970-01-01 00', '1970-11-01 00'),
				array('plusMonths', 12, '1970-01-01 00', '1971-01-01 00'),
				array('plusMonths', -1, '1970-01-01 00', '1969-12-01 00'),

				array('minusMonths', -1, '1970-01-01 00', '1970-02-01 00'),
				array('minusMonths', -10, '1970-01-01 00', '1970-11-01 00'),
				array('minusMonths', -12, '1970-01-01 00', '1971-01-01 00'),
				array('minusMonths', 1, '1970-01-01 00', '1969-12-01 00'),

				array('plusYears', 1, '1970-01-01 00', '1971-01-01 00'),
				array('plusYears', 10, '1970-01-01 00', '1980-01-01 00'),
				array('plusYears', 30, '1970-01-01 00', '2000-01-01 00'),
				array('plusYears', -1, '1970-01-01 00', '1969-01-01 00'),

				array('minusYears', -1, '1970-01-01 00', '1971-01-01 00'),
				array('minusYears', -10, '1970-01-01 00', '1980-01-01 00'),
				array('minusYears', -30, '1970-01-01 00', '2000-01-01 00'),
				array('minusYears', 1, '1970-01-01 00', '1969-01-01 00'),
				);
	}

	/**
	 * Tests methods that change the timestamp, from hours on up.
	 *
	 * @covers DateTime::plusHours
	 * @covers DateTime::minusHours
	 * @covers DateTime::plusDays
	 * @covers DateTime::minusDays
	 * @covers DateTime::plusMonths
	 * @covers DateTime::minusMonths
	 * @covers DateTime::plusYears
	 * @covers DateTime::minusYears
	 * @dataProvider dataChangeTimestampUpperValid
	 */
	public function testChangeTimestampUpperValid($methodName, $value, $before, $after) {
		$before = DateTime::parse($before . ':12:34.567890', 'Y-m-d H:i:s.u');
		$after = DateTime::parse($after . ':12:34.567890', 'Y-m-d H:i:s.u');
		$this->assertEquals($before->$methodName($value)->toString('U.u'), $after->toString('U.u'));
	}

	/**
	 * Data provider for testTimeZoneShift.
	 */
	public static function dataTimeZoneShift() {
		return array(
				/******** plus* from DST to no DST *********/
				// 2013-11-03 01:59:59.999999 to 2013-11-03 01:00:00.000000
				array('plusMicroseconds', 1383469199, 999999, 1383469200, 0, '2013-11-03 01:00:00.000000 -08:00'),
				// 2013-11-03 01:59:59.999000 to 2013-11-03 01:00:00.000000
				array('plusMilliseconds', 1383469199, 999000, 1383469200, 0, '2013-11-03 01:00:00.000000 -08:00'),
				// 2013-11-03 01:59:59.000000 to 2013-11-03 01:00:00.000000
				array('plusSeconds', 1383469199, 0, 1383469200, 0, '2013-11-03 01:00:00.000000 -08:00'),
				// 2013-11-03 01:59:00.000000 to 2013-11-03 01:00:00.000000
				array('plusMinutes', 1383469140, 0, 1383469200, 0, '2013-11-03 01:00:00.000000 -08:00'),
				// 2013-11-03 01:00:00.000000 to 2013-11-03 01:00:00.000000
				array('plusHours', 1383465600, 0, 1383469200, 0, '2013-11-03 01:00:00.000000 -08:00'),
				// 2013-11-02 02:00:00.000000 to 2013-11-03 02:00:00.000000
				array('plusDays', 1383382800, 0, 1383472800, 0, '2013-11-03 02:00:00.000000 -08:00'),
				// 2013-10-03 02:00:00.000000 to 2013-11-03 02:00:00.000000
				array('plusMonths', 1380790800, 0, 1383472800, 0, '2013-11-03 02:00:00.000000 -08:00'),
				// 2013-10-03 02:00:00.000000 to 2013-11-03 02:00:00.000000
				array('plusYears', 1351933200, 0, 1383472800, 0, '2013-11-03 02:00:00.000000 -08:00'),

				/******** plus* from no DST to DST *********/
				// 2013-03-10 01:59:59.999999 to 2013-03-10 03:00:00.000000
				array('plusMicroseconds', 1362909599, 999999, 1362909600, 0, '2013-03-10 03:00:00.000000 -07:00'),
				// 2013-03-10 01:59:59.999000 to 2013-03-10 03:00:00.000000
				array('plusMilliseconds', 1362909599, 999000, 1362909600, 0, '2013-03-10 03:00:00.000000 -07:00'),
				// 2013-03-10 01:59:59.000000 to 2013-03-10 03:00:00.000000
				array('plusSeconds', 1362909599, 0, 1362909600, 0, '2013-03-10 03:00:00.000000 -07:00'),
				// 2013-03-10 01:59:00.000000 to 2013-03-10 03:00:00.000000
				array('plusMinutes', 1362909540, 0, 1362909600, 0, '2013-03-10 03:00:00.000000 -07:00'),
				// 2013-03-10 01:00:00.000000 to 2013-03-10 03:00:00.000000
				array('plusHours', 1362906000, 0, 1362909600, 0, '2013-03-10 03:00:00.000000 -07:00'),
				// 2013-03-09 02:00:00.000000 to 2013-03-10 03:00:00.000000
				array('plusDays', 1362823200, 0, 1362909600, 0, '2013-03-10 03:00:00.000000 -07:00'),
				// 2013-02-10 02:00:00.000000 to 2013-03-10 03:00:00.000000
				array('plusMonths', 1360490400, 0, 1362909600, 0, '2013-03-10 03:00:00.000000 -07:00'),
				// 2012-03-10 02:00:00.000000 to 2013-03-10 03:00:00.000000
				array('plusYears', 1331373600, 0, 1362909600, 0, '2013-03-10 03:00:00.000000 -07:00'),

				/******** minus* from no DST to DST *********/
				// 2013-11-03 01:00:00.000000 to 2013-11-03 01:59:59.999999
				array('minusMicroseconds', 1383469200, 0, 1383469199, 999999, '2013-11-03 01:59:59.999999 -07:00'),
				// 2013-11-03 01:00:00.000000 to 2013-11-03 01:59:59.999000
				array('minusMilliseconds', 1383469200, 0, 1383469199, 999000, '2013-11-03 01:59:59.999000 -07:00'),
				// 2013-11-03 01:00:00.000000 to 2013-11-03 01:59:59.000000
				array('minusSeconds', 1383469200, 0, 1383469199, 0, '2013-11-03 01:59:59.000000 -07:00'),
				// 2013-11-03 01:00:00.000000 to 2013-11-03 01:59:00.000000
				array('minusMinutes', 1383469200, 0, 1383469140, 0, '2013-11-03 01:59:00.000000 -07:00'),
				//  2013-11-03 01:00:00.000000 to 2013-11-03 01:00:00.000000
				array('minusHours', 1383469200, 0, 1383465600, 0, '2013-11-03 01:00:00.000000 -07:00'),
				// 2013-11-03 02:00:00.000000 to 2013-11-02 02:00:00.000000
				array('minusDays', 1383472800, 0, 1383382800, 0, '2013-11-02 02:00:00.000000 -07:00'),
				// 2013-11-03 02:00:00.000000 to 2013-10-03 02:00:00.000000
				array('minusMonths', 1383472800, 0, 1380790800, 0, '2013-10-03 02:00:00.000000 -07:00'),
				// 2013-11-03 02:00:00.000000 to 2013-10-03 02:00:00.000000
				array('minusYears', 1383472800, 0, 1351933200, 0, '2012-11-03 02:00:00.000000 -07:00'),
				
				/******** minus* from DST to no DST *********/
				// 2013-03-10 03:00:00.000000 to 2013-03-10 01:59:59.999999
				array('minusMicroseconds', 1362909600, 0, 1362909599, 999999, '2013-03-10 01:59:59.999999 -08:00'),
				// 2013-03-10 03:00:00.000000 to 2013-03-10 01:59:59.999000
				array('minusMilliseconds', 1362909600, 0, 1362909599, 999000, '2013-03-10 01:59:59.999000 -08:00'),
				// 2013-03-10 03:00:00.000000 to 2013-03-10 01:59:59.000000
				array('minusSeconds', 1362909600, 0, 1362909599, 0, '2013-03-10 01:59:59.000000 -08:00'),
				// 2013-03-10 03:00:00.000000 to 2013-03-10 01:59:00.000000
				array('minusMinutes', 1362909600, 0, 1362909540, 0, '2013-03-10 01:59:00.000000 -08:00'),
				// 2013-03-10 03:00:00.000000 to 2013-03-10 01:00:00.000000
				array('minusHours', 1362909600, 0, 1362906000, 0, '2013-03-10 01:00:00.000000 -08:00'),
				// 2013-03-10 03:00:00.000000 to 2013-03-09 03:00:00.000000
				array('minusDays', 1362909600, 0, 1362826800, 0, '2013-03-09 03:00:00.000000 -08:00'),
				// 2013-03-11 02:30:00.000000 to 2013-03-09 03:00:00.000000
				array('minusDays', 1362994200, 0, 1362911400, 0, '2013-03-10 03:30:00.000000 -07:00'),
				// 2013-03-10 03:00:00.000000 to 2013-02-10 03:00:00.000000
				array('minusMonths', 1362909600, 0, 1360494000, 0, '2013-02-10 03:00:00.000000 -08:00'),
				// 2013-03-10 03:00:00.000000 to 2012-03-10 03:00:00.000000
				array('minusYears', 1362909600, 0, 1331377200, 0, '2012-03-10 03:00:00.000000 -08:00'),
				);
	}

	/**
	 * Tests DateTimes behave correctly around daylight savings time boundaries.
	 *
	 * @covers DateTime::plusMicroseconds
	 * @covers DateTime::minusMicroseconds
	 * @covers DateTime::plusMilliseconds
	 * @covers DateTime::minusMilliseconds
	 * @covers DateTime::plusMicroseconds
	 * @covers DateTime::minusMicroseconds
	 * @covers DateTime::plusMilliseconds
	 * @covers DateTime::minusMilliseconds
	 * @covers DateTime::plusSeconds
	 * @covers DateTime::minusSeconds
	 * @covers DateTime::plusMinutes
	 * @covers DateTime::minusMinutes
	 * @covers DateTime::plusHours
	 * @covers DateTime::minusHours
	 * @covers DateTime::plusDays
	 * @covers DateTime::minusDays
	 * @covers DateTime::plusMonths
	 * @covers DateTime::minusMonths
	 * @covers DateTime::plusYears
	 * @covers DateTime::minusYears
	 * @covers DateTime::getTimestamp
	 * @covers DateTime::getMicrosOfSecond
	 * @dataProvider dataTimeZoneShift
	 */
	public function testTimeZoneShift($methodName, $beforeTimestamp, $beforeMicros, $expectedTimestamp, $expectedMicros, $expectedString) {
		$before = DateTime::fromMicrosTimestamp($beforeTimestamp, $beforeMicros, 'America/Los_Angeles');
		$after = $before->$methodName(1);
		$this->assertEquals($expectedTimestamp, $after->getTimestamp());
		$this->assertEquals($expectedMicros, $after->getMicrosOfSecond());
		$this->assertEquals($expectedString, $after->toString('Y-m-d H:i:s.u P'));
	}

	/**
	 * Tests each method that changes a timestamp that it properly validates its
	 * argument to be an int.
	 *
	 * @covers DateTime::plusMicroseconds
	 * @covers DateTime::minusMicroseconds
	 * @covers DateTime::plusMilliseconds
	 * @covers DateTime::minusMilliseconds
	 * @covers DateTime::plusMicroseconds
	 * @covers DateTime::minusMicroseconds
	 * @covers DateTime::plusMilliseconds
	 * @covers DateTime::minusMilliseconds
	 * @covers DateTime::plusSeconds
	 * @covers DateTime::minusSeconds
	 * @covers DateTime::plusMinutes
	 * @covers DateTime::minusMinutes
	 * @covers DateTime::plusHours
	 * @covers DateTime::minusHours
	 * @covers DateTime::plusDays
	 * @covers DateTime::minusDays
	 * @covers DateTime::plusMonths
	 * @covers DateTime::minusMonths
	 * @covers DateTime::plusYears
	 * @covers DateTime::minusYears
	 * @dataProvider dataChangeTimestampMethods
	 */
	public function testChangeTimestampArg($methodName) {
		$this->assertIntArg(function ($value) use ($methodName) {
				DateTime::now()->$methodName($value);
				});
	}

	/**
	 * Data provider for testEndOfMonth
	 */
	public static function dataEndOfMonth() {
		return array(
				array(1, '2013-05-31', '2013-06-30'),
				array(-1, '2013-05-31', '2013-04-30'),
				array(2, '2013-05-31', '2013-07-31'),
				array(-2, '2013-05-31', '2013-03-31'),
				array(26, '2013-05-31', '2015-07-31'),
				array(-22, '2013-05-31', '2011-07-31'),
				array(1, '2013-01-31', '2013-02-28'),

				array(1, '2012-01-29', '2012-02-29'),
				array(13, '2011-01-29', '2012-02-29'),
				array(25, '2010-01-29', '2012-02-29'),
				array(48, '2008-02-29', '2012-02-29'),
				array(36, '2008-02-29', '2011-02-28'),
				array(-1, '2012-03-29', '2012-02-29'),
				array(-13, '2013-03-29', '2012-02-29'),
				array(-25, '2014-03-29', '2012-02-29'),
				array(-48, '2016-02-29', '2012-02-29'),
				array(-36, '2016-02-29', '2013-02-28'),
				array(-1, '2012-01-31', '2011-12-31'),
				array(2, '2011-12-31', '2012-02-29'),
				);
	}

	/**
	 * Tests how plus/minusMonths deals with the fact that the last day of the
	 * month changes from month to month.
	 *
	 * @covers DateTime::plusMonths
	 * @covers DateTime::minusMonths
	 *
	 * @dataProvider dataEndOfMonth
	 */
	public function testEndOfMonth($increment, $before, $expected) {
		$before = DateTime::parse($before, 'Y-m-d');
		$after = $before->plusMonths($increment);
		$this->assertEquals($expected, $after->toString('Y-m-d'));
		
		$after = $before->minusMonths(-$increment);
		$this->assertEquals($expected, $after->toString('Y-m-d'));
	}

	/**
	 * Data provider for testWithDateValid
	 */
	public static function dataWithDateValid() {
		return array(
				// 2013-07-02 16:28:41 to 2012-03-15 16:28:41
				array(DateTime::fromTimestamp(1372796921, 'America/New_York'), 2012, 3, 15, 1331843321, 0),
				// 2013-07-02 02:30:00 to 2013-03-10 3:30:00
				array(DateTime::fromTimestamp(1372757400, 'America/Los_Angeles'), 2013, 3, 10, 1362911400, 0),
				);
	}

	/**
	 * Tests withDate with valid data.
	 *
	 * @dataProvider dataWithDateValid
	 * @covers DateTime::withDate
	 * @covers DateTime::getTimestamp
	 * @covers DateTime::getMicrosOfSecond
	 * @covers DateTime::getDayOfMonth
	 * @covers DateTime::getMonthOfYear
	 * @covers DateTime::getYear
	 */
	public function testWithDateValid($orig, $year, $month, $day, $expectedTimestamp, $expectedMicros) {
		$date = $orig->withDate($year, $month, $day);
		$this->assertEquals($year, $date->getYear());
		$this->assertEquals($month, $date->getMonthOfYear());
		$this->assertEquals($day, $date->getDayOfMonth());
		$this->assertEquals($expectedTimestamp, $date->getTimestamp());
		$this->assertEquals($expectedMicros, $date->getMicrosOfSecond());
	}

	/**
	 * Data provider for testWithDateInvalid
	 */
	public static function dataWithDateInvalid() {
		return array(
				array(2012, 02, 30),
				array(2013, 02, 29),
				array(2012, 0, 1),
				array(2012, 1, 0),
				array(2012, 13, 1),
				);
	}

	/**
	 * Tests withDate with invalid data.
	 *
	 * @dataProvider dataWithDateInvalid
	 * @covers DateTime::withDate
	 * @expectedException InvalidArgumentException
	 */
	public function testWithDateInvalid($year, $month, $day) {
		DateTime::now()->withDate($year, $month, $day);
	}

	/**
	 * Tests withDate returns the same instance when the date isn't actually
	 * changing.
	 *
	 * @covers DateTime::withDate
	 * @covers DateTime::getDayOfMonth
	 * @covers DateTime::getMonthOfYear
	 * @covers DateTime::getYear
	 */
	public function testWtihDateIdentity() {
		$date0 = DateTime::parse('2012-02-03', 'Y-m-d');
		$date1 = $date0->withDate(2012, 2, 3);
		$this->assertTrue($date0 === $date1);
		$this->assertEquals(2012, $date1->getYear());
		$this->assertEquals(2012, $date0->getYear());
		$this->assertEquals(2, $date1->getMonthOfYear());
		$this->assertEquals(2, $date0->getMonthOfYear());
		$this->assertEquals(3, $date1->getDayOfMonth());
		$this->assertEquals(3, $date0->getDayOfMonth());
	}

	/**
	 * Tests withDate's arg validation.
	 *
	 * @covers DateTime::withDate
	 */
	public function testWithDateArgs() {
		$this->assertIntArg(function ($value) {
				DateTime::now()->withDate($value, 1, 1);
		});

		$this->assertIntArg(function ($value) {
				DateTime::now()->withDate(2000, $value, 1);
		});

		$this->assertIntArg(function ($value) {
				DateTime::now()->withDate(2000, 1, $value);
		});
	}

	/**
	 * Data provider for testWithYearValid
	 */
	public static function dataWithYearValid() {
		return array(
				array('2012-01-01 21:44:31', 2013, '2013-01-01 21:44:31'),
				array('2012-02-29 04:01:01', 2013, '2013-02-28 04:01:01'),
				);
	}

	/**
	 * Tests withYear with valid data
	 *
	 * @covers DateTime::withYear
	 * @covers DateTime::getYear
	 * @dataProvider dataWithYearValid
	 */
	public function testWithYearValid($origString, $year, $expectedString) {
		$orig = DateTime::parse($origString, 'Y-m-d H:i:s');
		$origYear = $orig->getYear();
		$date = $orig->withYear($year);
		$this->assertEquals($year, $date->getYear());
		$this->assertEquals($expectedString, $date->toString('Y-m-d H:i:s'));
		$this->assertEquals($origYear, $orig->getYear());
	}

	/**
	 * Tests withYear's arg validation
	 *
	 * @covers DateTime::withYear
	 */
	public function testWithYearArgs() {
		$this->assertIntArg(function ($value) {
			DateTime::now()->withYear($value);
		});
	}

	/**
	 * Data provider for testWithMonthOfYearValid
	 */
	public static function dataWithMonthOfYearValid() {
		return array(
				array('2012-01-01 21:44:31 UTC', 12, '2012-12-01 21:44:31 +00:00'),
				array('2012-01-30 04:01:01 UTC', 2, '2012-02-29 04:01:01 +00:00'),
				array('2013-02-10 02:30:01 America/New_York', 3, '2013-03-10 03:30:01 -04:00'),
				);
	}

	/**
	 * Tests withMonthOfYear with valid data
	 *
	 * @covers DateTime::withMonthOfYear
	 * @covers DateTime::getMonthOfYear
	 * @dataProvider dataWithMonthOfYearValid
	 */
	public function testWithMonthOfYearValid($origString, $month, $expectedString) {
		$orig = DateTime::parse($origString, 'Y-m-d H:i:s e');
		$origMonthOfYear = $orig->getMonthOfYear();
		$date = $orig->withMonthOfYear($month);
		$this->assertEquals($month, $date->getMonthOfYear());
		$this->assertEquals($expectedString, $date->toString('Y-m-d H:i:s P'));
		$this->assertEquals($origMonthOfYear, $orig->getMonthOfYear());
	}

	/**
	 * Data provider for testWithMonthOfYearInvalid
	 */
	public static function dataWithMonthOfYearInvalid() {
		return array(
				array(0),
				array(13),
				);
	}

	/**
	 * Tests withMonthOfYear with invalid data
	 *
	 * @expectedException InvalidArgumentException
	 * @covers DateTime::withMonthOfYear
	 * @dataProvider dataWithMonthOfYearInvalid
	 */
	public function testWithMonthOfYearInvalid($month) {
		DateTime::now()->withMonthOfYear($month);
	}

	/**
	 * Tests the same instance is returned when nothing changes.
	 *
	 * @covers DateTime::withMonthOfYear
	 * @covers DateTime::getMonthOfYear
	 */
	public function testWithMonthOfYearIdentity() {
		$date = DateTime::now();
		$month = $date->getMonthOfYear();
		$date2 = $date->withMonthOfYear($month);
		$this->assertTrue($date === $date2);
		$this->assertEquals($month, $date->getMonthOfYear());
	}

	/**
	 * Tests withMonthOfYear's arg validation
	 *
	 * @covers DateTime::withMonthOfYear
	 */
	public function testWithMonthOfYearArgs() {
		$this->assertIntArg(function ($value) {
			DateTime::now()->withMonthOfYear($value);
		});
	}

	/**
	 * Data provider for testWithDayOfMonthValid
	 */
	public static function dataWithDayOfMonthValid() {
		return array(
				array('2012-01-01 21:44:31 UTC', 12, '2012-01-12 21:44:31 +00:00'),
				array('2012-02-10 04:01:01 UTC', 29, '2012-02-29 04:01:01 +00:00'),
				array('2013-03-11 02:30:01 America/New_York', 10, '2013-03-10 03:30:01 -04:00'),
				);
	}

	/**
	 * Tests withDayOfMonth with valid data
	 *
	 * @covers DateTime::withDayOfMonth
	 * @covers DateTime::getDayOfMonth
	 * @dataProvider dataWithDayOfMonthValid
	 */
	public function testWithDayOfMonthValid($origString, $day, $expectedString) {
		$orig = DateTime::parse($origString, 'Y-m-d H:i:s e');
		$origDay = $orig->getDayOfMonth();
		$date = $orig->withDayOfMonth($day);
		$this->assertEquals($day, $date->getDayOfMonth());
		$this->assertEquals($expectedString, $date->toString('Y-m-d H:i:s P'));
		$this->assertEquals($origDay, $orig->getDayOfMonth());
	}

	/**
	 * Data provider for testWithDayOfMonthInvalid
	 */
	public static function dataWithDayOfMonthInvalid() {
		return array(
				array('2012-01-01', 0),
				array('2012-01-01', 32),
				array('2012-02-01', 30),
				array('2013-02-01', 29),
				);
	}

	/**
	 * Tests withDayOfMonth with invalid data
	 *
	 * @expectedException InvalidArgumentException
	 * @covers DateTime::withDayOfMonth
	 * @dataProvider dataWithDayOfMonthInvalid
	 */
	public function testWithDayOfMonthInvalid($day) {
		DateTime::now()->withDayOfMonth($day);
	}

	/**
	 * Tests the same instance is returned when nothing changes.
	 *
	 * @covers DateTime::withDayOfMonth
	 * @covers DateTime::getDayOfMonth
	 */
	public function testWithDayOfMonthIdentity() {
		$date = DateTime::now();
		$day = $date->getDayOfMonth();
		$date2 = $date->withDayOfMonth($day);
		$this->assertTrue($date === $date2);
		$this->assertEquals($day, $date->getDayOfMonth());
	}

	/**
	 * Data provider for testWithTimeValid
	 */
	public static function dataWithTimeValid() {
		return array(
				array('2012-01-01 00:00:00.000000 UTC', 1, 2, 3, 4, '2012-01-01 01:02:03.000004 +00:00'),
				array('2012-01-01 01:02:03.000004 UTC', 0, 0, 0, 0, '2012-01-01 00:00:00.000000 +00:00'),
				array('2012-01-01 01:02:03.000004 UTC', 23, 59, 59, 999999, '2012-01-01 23:59:59.999999 +00:00'),
				);
	}

	/**
	 * Tests withTime with valid data
	 *
	 * @covers DateTime::withTime
	 * @dataProvider dataWithTimeValid
	 */
	public function testWithTimeValid($origString, $hour, $minute, $second, $microsecond, $expectedString) {
		$orig = DateTime::parse($origString, 'Y-m-d H:i:s.u e');
		$date = $orig->withTime($hour, $minute, $second, $microsecond);
		$this->assertEquals($expectedString, $date->toString('Y-m-d H:i:s.u P'));
	}

	/**
	 * Data provider for testWithTimeInvalid
	 */
	public static function dataWithTimeInvalid() {
		return array(
				array(-1, 0, 0, 0),
				array(24, 0, 0, 0),
				array(0, -1, 0, 0),
				array(0, 60, 0, 0),
				array(0, 0, -1, 0),
				array(0, 0, 60, 0),
				array(0, 0, 0, -1),
				array(0, 0, 0, 1000000),
				);
	}

	/**
	 * Tests withTime with invalid data
	 *
	 * @covers DateTime::withTime
	 * @dataProvider dataWithTimeInvalid
	 * @expectedException InvalidArgumentException
	 */
	public function testWithTimeInvalid($hour, $minute, $second, $microsecond) {
		DateTime::now()->withTime($hour, $minute, $second, $microsecond);
	}

	/**
	 * Data provider for testWithTimeInvalidDST
	 */
	public static function dataWithTimeInvalidDST() {
		return array(
				array('2013-03-10 01:01:01 America/New_York', 2),
				array('2013-03-10 01:01:01 America/Los_Angeles', 2),
				);
	}

	/**
	 * Tests what happens when a time is set that does not exist due to DST
	 * transitions.
	 *
	 * @covers DateTime::withTime
	 * @expectedException InvalidArgumentException
	 * @dataProvider dataWithTimeInvalidDST
	 */
	public function testWithTimeInvalidDST($origString, $hour) {
		DateTime::parse($origString, 'Y-m-d H:i:s e')->withTime($hour, 30, 0, 0);
	}

	/**
	 * Tests that the same instance is returned when nothing changes.
	 *
	 * @covers DateTime::withTime
	 * @covers DateTime::getMicrosOfSecond
	 * @covers DateTime::getSecondOfMinute
	 * @covers DateTime::getMinuteOfHour
	 * @covers DateTime::getHourOfDay
	 */
	public function testWithTimeIdentity() {
		$now = DateTime::now();
		$this->assertTrue($now === $now->withTime($now->getHourOfDay(), $now->getMinuteOfHour(), $now->getSecondOfMinute(), $now->getMicrosOfSecond()));
	}

	/**
	 * Tests withTime's arg validation
	 *
	 * @covers DateTime::withTime
	 */
	public function testWithTimeArgs() {
		$this->assertIntArg(function ($value) {
			DateTime::now()->withTime($value, 0, 0, 0);
		});

		$this->assertIntArg(function ($value) {
			DateTime::now()->withTime(0, $value, 0, 0);
		});

		$this->assertIntArg(function ($value) {
			DateTime::now()->withTime(0, 0, $value, 0);
		});

		$this->assertIntArg(function ($value) {
			DateTime::now()->withTime(0, 0, 0, $value);
		});
	}

	/**
	 * Data provider for testWithHourOfDayValid
	 */
	public static function dataWithHourOfDayValid() {
		return array(
				array('2012-01-01 00:00:00.000000 UTC', 1, '2012-01-01 01:00:00.000000 +00:00'),
				array('2012-01-01 01:01:02.000003 UTC', 0, '2012-01-01 00:01:02.000003 +00:00'),
				array('2012-01-01 01:59:59.999999 UTC', 23, '2012-01-01 23:59:59.999999 +00:00'),
				);
	}

	/**
	 * Tests withHourOfDay with valid data
	 *
	 * @covers DateTime::withHourOfDay
	 * @dataProvider dataWithHourOfDayValid
	 */
	public function testWithHourOfDayValid($origString, $hour, $expectedString) {
		$orig = DateTime::parse($origString, 'Y-m-d H:i:s.u e');
		$date = $orig->withHourOfDay($hour);
		$this->assertEquals($expectedString, $date->toString('Y-m-d H:i:s.u P'));
	}

	/**
	 * Data provider for testWithHourOfDayInvalid
	 */
	public static function dataWithHourOfDayInvalid() {
		return array(
				array(-1),
				array(24),
				);
	}

	/**
	 * Tests withHourOfDay with invalid data
	 *
	 * @covers DateTime::withHourOfDay
	 * @dataProvider dataWithHourOfDayInvalid
	 * @expectedException InvalidArgumentException
	 */
	public function testWithHourOfDayInvalid($hour) {
		DateTime::now()->withHourOfDay($hour);
	}

	/**
	 * Data provider for testWithTimeInvalidDST
	 */
	public static function dataWithHourOfDayInvalidDST() {
		return array(
				array('2013-03-10 01:01:01 America/New_York', 2),
				array('2013-03-10 01:01:01 America/Los_Angeles', 2),
				);
	}

	/**
	 * Tests what happens when a time is set that does not exist due to DST
	 * transitions.
	 *
	 * @covers DateTime::withHourOfDay
	 * @expectedException InvalidArgumentException
	 * @dataProvider dataWithHourOfDayInvalidDST
	 */
	public function testWithHourOfDayInvalidDST($origString, $hour) {
		DateTime::parse($origString, 'Y-m-d H:i:s e')->withHourOfDay($hour);
	}

	/**
	 * Tests that the same instance is returned when nothing changes.
	 *
	 * @covers DateTime::withHourOfDay
	 * @covers DateTime::getHourOfDay
	 */
	public function testWithHourOfDayIdentity() {
		$now = DateTime::now();
		$this->assertTrue($now === $now->withHourOfDay($now->getHourOfDay()));
	}

	/**
	 * Tests withHourOfDay's arg validation
	 *
	 * @covers DateTime::withHourOfDay
	 */
	public function testWithHourMinSecMicrosArgs() {
		$this->assertIntArg(function ($value) {
			DateTime::now()->withHourOfDay($value);
		});

		$this->assertIntArg(function ($value) {
			DateTime::now()->withMinuteOfHour($value);
		});

		$this->assertIntArg(function ($value) {
			DateTime::now()->withSecondOfMinute($value);
		});

		$this->assertIntArg(function ($value) {
			DateTime::now()->withMicrosOfSecond($value);
		});
	}

	/**
	 * Data provider for testWithMinuteOfHourValid
	 */
	public static function dataWithMinuteOfHourValid() {
		return array(
				array('2012-01-01 00:00:00.000000 UTC', 1, '2012-01-01 00:01:00.000000 +00:00'),
				array('2012-01-01 01:01:02.000003 UTC', 0, '2012-01-01 01:00:02.000003 +00:00'),
				array('2012-01-01 00:00:00.000000 UTC', 59, '2012-01-01 00:59:00.000000 +00:00'),
				);
	}

	/**
	 * Tests withMinuteOfHour with valid data
	 *
	 * @covers DateTime::withMinuteOfHour
	 * @dataProvider dataWithMinuteOfHourValid
	 */
	public function testWithMinuteOfHourValid($origString, $minute, $expectedString) {
		$orig = DateTime::parse($origString, 'Y-m-d H:i:s.u e');
		$date = $orig->withMinuteOfHour($minute);
		$this->assertEquals($expectedString, $date->toString('Y-m-d H:i:s.u P'));
	}

	/**
	 * Data provider for testWithMinuteOfHourInvalid
	 */
	public static function dataWithMinuteOfHourInvalid() {
		return array(
				array(-1),
				array(60),
				);
	}

	/**
	 * Tests withMinuteOfHour with invalid data
	 *
	 * @covers DateTime::withMinuteOfHour
	 * @dataProvider dataWithMinuteOfHourInvalid
	 * @expectedException InvalidArgumentException
	 */
	public function testWithMinuteOfHourInvalid($minute) {
		DateTime::now()->withMinuteOfHour($minute);
	}

	/**
	 * Tests what happens when a time is set that does not exist due to DST
	 * transitions.
	 *
	 * @covers DateTime::withMinuteOfHour
	 * @expectedException InvalidArgumentException
	 */
	public function testWithMinuteOfHourInvalidDST() {
		DateTime::parse('1919-03-30 23:29:00 America/Toronto', 'Y-m-d H:i:s e')->withMinuteOfHour(30);
	}

	/**
	 * Tests that the same instance is returned when nothing changes.
	 *
	 * @covers DateTime::withMinuteOfHour
	 * @covers DateTime::getMinuteOfHour
	 */
	public function testWithMinuteOfHourIdentity() {
		$now = DateTime::now();
		$this->assertTrue($now === $now->withMinuteOfHour($now->getMinuteOfHour()));
	}

	/**
	 * Data provider for testWithSecondOfMinuteValid
	 */
	public static function dataWithSecondOfMinuteValid() {
		return array(
				array('2012-01-01 00:00:00.000000 UTC', 1, '2012-01-01 00:00:01.000000 +00:00'),
				array('2012-01-01 01:01:02.000003 UTC', 0, '2012-01-01 01:01:00.000003 +00:00'),
				array('2012-01-01 00:00:00.000000 UTC', 59, '2012-01-01 00:00:59.000000 +00:00'),
				);
	}

	/**
	 * Tests withSecondOfMinute with valid data
	 *
	 * @covers DateTime::withSecondOfMinute
	 * @dataProvider dataWithSecondOfMinuteValid
	 */
	public function testWithSecondOfMinuteValid($origString, $second, $expectedString) {
		$orig = DateTime::parse($origString, 'Y-m-d H:i:s.u e');
		$date = $orig->withSecondOfMinute($second);
		$this->assertEquals($expectedString, $date->toString('Y-m-d H:i:s.u P'));
	}

	/**
	 * Data provider for testWithSecondOfMinuteInvalid
	 */
	public static function dataWithSecondOfMinuteInvalid() {
		return array(
				array(-1),
				array(60),
				);
	}

	/**
	 * Tests withSecondOfMinute with invalid data
	 *
	 * @covers DateTime::withSecondOfMinute
	 * @dataProvider dataWithSecondOfMinuteInvalid
	 * @expectedException InvalidArgumentException
	 */
	public function testWithSecondOfMinuteInvalid($second) {
		DateTime::now()->withSecondOfMinute($second);
	}

	/**
	 * Tests that the same instance is returned when nothing changes.
	 *
	 * @covers DateTime::withSecondOfMinute
	 * @covers DateTime::getSecondOfMinute
	 */
	public function testWithSecondOfMinuteIdentity() {
		$now = DateTime::now();
		$this->assertTrue($now === $now->withSecondOfMinute($now->getSecondOfMinute()));
	}

	/**
	 * Data provider for testWithMicrosOfSecondValid
	 */
	public static function dataWithMicrosOfSecondValid() {
		return array(
				array('2012-01-01 00:00:00.000000 UTC', 1, '2012-01-01 00:00:00.000001 +00:00'),
				array('2012-01-01 01:01:02.000003 UTC', 0, '2012-01-01 01:01:02.000000 +00:00'),
				array('2012-01-01 00:00:00.000000 UTC', 999999, '2012-01-01 00:00:00.999999 +00:00'),
				);
	}

	/**
	 * Tests withMicrosOfSecond with valid data
	 *
	 * @covers DateTime::withMicrosOfSecond
	 * @dataProvider dataWithMicrosOfSecondValid
	 */
	public function testWithMicrosOfSecondValid($origString, $micros, $expectedString) {
		$orig = DateTime::parse($origString, 'Y-m-d H:i:s.u e');
		$date = $orig->withMicrosOfSecond($micros);
		$this->assertEquals($expectedString, $date->toString('Y-m-d H:i:s.u P'));
	}

	/**
	 * Data provider for testWithMicrosOfSecondInvalid
	 */
	public static function dataWithMicrosOfSecondInvalid() {
		return array(
				array(-1),
				array(1000000),
				);
	}

	/**
	 * Tests withMicrosOfSecond with invalid data
	 *
	 * @covers DateTime::withMicrosOfSecond
	 * @dataProvider dataWithMicrosOfSecondInvalid
	 * @expectedException InvalidArgumentException
	 */
	public function testWithMicrosOfSecondInvalid($micros) {
		DateTime::now()->withMicrosOfSecond($micros);
	}

	/**
	 * Tests that the same instance is returned when nothing changes.
	 *
	 * @covers DateTime::withMicrosOfSecond
	 * @covers DateTime::getMicrosOfSecond
	 */
	public function testWithMicrosOfSecondIdentity() {
		$now = DateTime::now();
		$this->assertTrue($now === $now->withMicrosOfSecond($now->getMicrosOfSecond()));
	}

	/**
	 * Data provider for testWithTimeZone2
	 */
	public static function dataWithTimeZoneValid2() {
		return array(
				array('2012-01-01 05:00:00 UTC', 'America/New_York', '2012-01-01 00:00:00 -05:00'),
				array('2013-03-10 07:30:00 UTC', 'America/New_York', '2013-03-10 03:30:00 -04:00'),
				array('2012-01-01 05:00:00 UTC', TimeZone::parse('America/New_York'), '2012-01-01 00:00:00 -05:00'),
				array('2013-03-10 07:30:00 UTC', TimeZone::parse('America/New_York'), '2013-03-10 03:30:00 -04:00'),
				array('2012-01-01 05:00:00 UTC', '-0500', '2012-01-01 00:00:00 -05:00'),
				array('2013-03-10 07:30:00 UTC', '-0500', '2013-03-10 02:30:00 -05:00'),
				array('2012-01-01 05:00:00 UTC', TimeZone::parse('-0500'), '2012-01-01 00:00:00 -05:00'),
				array('2013-03-10 07:30:00 UTC', TimeZone::parse('-0500'), '2013-03-10 02:30:00 -05:00'),
				array('2012-01-01 00:05:00 America/New_York', '-0500', '2012-01-01 00:05:00 -05:00'),
				array('2013-03-10 07:30:00 America/New_York', '-0400', '2013-03-10 07:30:00 -04:00'),
				array('2013-03-10 07:30:00 America/New_York', '-0400', '2013-03-10 07:30:00 -04:00'),
				array('2013-10-28 10:00:00 Europe/London', 'America/New_York', '2013-10-28 06:00:00 -04:00'),
				);
	}

	/**
	 * Tests withTimeZone with valid input.
	 *
	 * @covers DateTime::withTimeZone
	 * @dataProvider dataWithTimeZoneValid2
	 */
	public function testWithTimeZoneValid2($origString, $timeZone, $expectedString) {
		$orig = DateTime::parse($origString, 'Y-m-d H:i:s e');
		$date = $orig->withTimeZone($timeZone);
		$this->assertEquals($expectedString, $date->toString('Y-m-d H:i:s P'));
		$this->assertFalse($date === $orig);
	}

	/**
	 * Valid data for testWithTimeZoneValid
	 */
	public static function dataWithTimeZoneValid() {
		return array(
				array(0, 'America/Los_Angeles', 'Europe/Paris'),
				array(1234, 'America/New_York', 'UTC'),
				array(15553, TimeZone::parse('America/New_York'), 'UTC'),
				array(399922, TimeZone::parse('UTC'), 'America/Los_Angeles'),
				array(15553, 'America/New_York', TimeZone::parse('UTC')),
				array(399922, TimeZone::parse('UTC'), TimeZone::parse('America/Los_Angeles')),
				);
	}

	/**
	 * Tests withTimeZone with valid input, tested a little differently.
	 *
	 * @covers DateTime::withTimeZone
	 * @covers DateTime::getTimestamp
	 * @covers DateTime::getTimeZone
	 * @dataProvider dataWithTimeZoneValid
	 */
	public function testWithTimeZoneValid($timestamp, $timeZone0, $timeZone1) {
		$date0 = DateTime::fromTimestamp($timestamp, $timeZone0);
		$date1 = $date0->withTimeZone($timeZone1);

		$this->assertFalse($date0 === $date1);
		$this->assertEquals($date0->getTimestamp(), $date1->getTimestamp());

		if ($timeZone1 instanceof TimeZone) {
			$timeZone1 = $timeZone1->getName();
		}

		$this->assertEquals($date1->getTimeZone()->getName(), $timeZone1);
	}

	/**
	 * Tests withTimeZone returns the same exact object when the time zone
	 * already matches.
	 *
	 * @covers DateTime::withTimeZone
	 */
	public function testWithTimeZoneIdentity() {
		$date0 = DateTime::fromTimestamp(2344333, 'America/Los_Angeles');
		$date1 = $date0->withTimeZone('America/Los_Angeles');
		$this->assertTrue($date0 === $date1);

		$date1 = $date0->withTimeZone(TimeZone::parse('America/Los_Angeles'));
		$this->assertTrue($date0 === $date1);

		$date2 = $date1->withTimeZone('+0500');
		$date3 = $date2->withTimeZone('+05:00');
		$this->assertTrue($date2 === $date3);
	}

	/**
	 * Tests withTimeZone's $timeZone argument validation.
	 * 
	 * @covers DateTime::withTimeZone
	 */
	public function testWithTimeZoneArgument() {
		$this->assertTimeZoneArg(function ($value) {
			DateTime::now()->withTimeZone($value);
		});
	}

	/**
	 * Data provider for testGetMillisOfSecond
	 */
	public static function dataGetMillisOfSecond() {
		return array(
				array(123456, 123),
				array(0, 0),
				array(999999, 999),
				array(000001, 0),
				array(999000, 999),
				);
	}

	/**
	 * Tests getMillisOfSecond
	 *
	 * @covers DateTime::getMillisOfSecond
	 * @dataProvider dataGetMillisOfSecond
	 */
	public function testGetMillisOfSecond($micros, $expectedMillis) {
		$date = DateTime::now()->withMicrosOfSecond($micros);
		$this->assertEquals($expectedMillis, $date->getMillisOfSecond());
	}

	/**
	 * Data provider for testIsInstantEqualValid
	 */
	public static function dataIsInstantEqualValid() {
		return array(
				array('2012-01-01 01:01:01.010101 America/Los_Angeles', '2012-01-01 04:01:01.010101 America/New_York'),
				array('2012-01-01 01:01:01.010101 America/Los_Angeles', '2012-01-01 01:01:01.010101 America/Los_Angeles'),
				array('2012-01-01 01:01:01.010101 America/Los_Angeles', '2012-01-01 09:01:01.010101 UTC'),
				);
	}

	/**
	 * Tests isInstantEqual
	 *
	 * @covers DateTime::isInstantEqual
	 * @dataProvider dataIsInstantEqualValid
	 */
	public function testIsInstantEqualValid($first, $second) {
		$first = DateTime::parse($first, 'Y-m-d H:i:s.u e');
		$second = DateTime::parse($second, 'Y-m-d H:i:s.u e');
		$this->assertTrue($first->isInstantEqual($second));
		$this->assertFalse($first->plusMicroseconds(1)->isInstantEqual($second));
		$this->assertFalse($first->plusMilliseconds(1)->isInstantEqual($second));
		$this->assertFalse($first->plusSeconds(1)->isInstantEqual($second));
		$this->assertTrue($first->withTimeZone('UTC')->isInstantEqual($second));
	}

	/**
	 * Tests isAfter
	 *
	 * @covers DateTime::isAfter
	 */
	public function testIsAfter() {
		$date = DateTime::fromTimestamp(0);
		$this->assertTrue($date->isAfter($date->minusMicroseconds(1)));
		$this->assertTrue($date->isAfter($date->minusSeconds(1)));
		$this->assertFalse($date->isAfter($date->plusMicroseconds(1)));
		$this->assertFalse($date->isAfter($date->plusSeconds(1)));
		$this->assertFalse($date->isAfter($date));
	}

	/**
	 * Tests isAfterNow
	 *
	 * @covers DateTime::isAfterNow
	 */
	public function testIsAfterNow() {
		$date = DateTime::now();
		$this->assertTrue($date->plusSeconds(10)->isAfterNow());
		$this->assertFalse($date->minusMicroseconds(1)->isAfterNow());
		$this->assertFalse($date->minusSeconds(1)->isAfterNow());
		$this->assertFalse(DateTime::now()->isAfterNow());
	}

	/**
	 * Tests isBefore
	 *
	 * @covers DateTime::isBefore
	 */
	public function testIsBefore() {
		$date = DateTime::fromTimestamp(0);
		$this->assertTrue($date->isBefore($date->plusMicroseconds(1)));
		$this->assertTrue($date->isBefore($date->plusSeconds(1)));
		$this->assertFalse($date->isBefore($date->minusMicroseconds(1)));
		$this->assertFalse($date->isBefore($date->minusSeconds(1)));
		$this->assertFalse($date->isBefore($date));
	}

	/**
	 * Tests isBeforeNow
	 *
	 * @covers DateTime::isBeforeNow
	 */
	public function testIsBeforeNow() {
		$date = DateTime::now();
		$this->assertTrue($date->minusMicroseconds(1)->isBeforeNow());
		$this->assertTrue($date->minusSeconds(1)->isBeforeNow());
		$this->assertFalse($date->plusSeconds(10)->isBeforeNow());
	}

	/**
	 * Tests equals
	 *
	 * @covers DateTime::equals
	 */
	public function testEquals() {
		$date = DateTime::parse('2012-01-01 01:01:01.010101 America/Los_Angeles', 'Y-m-d H:i:s.u e');
		$this->assertTrue($date->equals($date));
		$second = DateTime::parse('2012-01-01 01:01:01.010101 America/Los_Angeles', 'Y-m-d H:i:s.u e');
		$this->assertTrue($date->equals($second));
		$this->assertTrue($second->equals($date));

		$this->assertFalse($date->equals($date->withTimeZone('-08:00')));
		$this->assertFalse($date->equals($date->withTimeZone('America/New_York')));
		$this->assertTrue($date->equals($date->withTimeZone('America/Los_Angeles')));
		$this->assertFalse($date->equals($date->plusMicroseconds(1)));
		$this->assertFalse($date->equals($date->plusSeconds(1)));
		$this->assertFalse($date->equals($date->minusMicroseconds(1)));
		$this->assertFalse($date->equals($date->minusSeconds(1)));
	}

	/**
	 * Data provider for testConvertToValid
	 */
	public static function dataConvertToValid() {
		return array(
				array(Type::PHP_DATE_TIME, '2012-01-01 01:01:01.010101', 'America/Los_Angeles'),
				array(Type::PHP_DATE_TIME, '2012-02-29 01:01:01.010101', 'America/Los_Angeles'),
				array(Type::PHP_DATE_TIME, '2012-02-29 01:01:01.010101', 'America/New_York'),
				array(Type::PHP_DATE_TIME, '2012-02-29 01:01:01.010101', 'UTC'),
				array(Type::PHP_DATE_TIME, '2012-02-29 01:01:01.010101', '+05:00'),
				array(Type::PHP_DATE_TIME, '2012-02-29 01:01:01.010101', '-01:00'),
				array(Type::PHP_DATE_TIME, '2012-02-29 01:01:01.010101', '+00:00'),
				);
	}

	/**
	 * Tests convertTo method
	 *
	 * @covers DateTime::convertTo
	 * @dataProvider dataConvertToValid
	 */
	public function testConvertToValid($type, $date, $tz) {
		$date = DateTime::parse($date, 'Y-m-d H:i:s.u', $tz);
		$converted = $date->convertTo($type);

		if ($type == Type::PHP_DATE_TIME) {
			$string = $converted->format('Y-m-d H:i:s ') . $converted->getTimezone()->getName();
		}

		$this->assertEquals($date->toString('Y-m-d H:i:s ') . $date->getTimeZone()->getName(), $string);
	}

	/**
	 * Data provider for testConvertToInvalid
	 */
	public static function dataConvertToInvalid() {
		return array(
				array('2012-02-29 01:01:01.010101', '+05:00'),
				array('2012-02-29 01:01:01.010101', '-01:00'),
				array('2012-02-29 01:01:01.010101', '+00:00'),
				);
	}

	/**
	 * Data provider for testConvertToInvalidArgs
	 */
	public static function dataConvertToInvalidArgs() {
		return array(
				array(null),
				array(array(1)),
				array('test'),
				array(''),
				array(new \stdclass()),
				);
	}

	/**
	 * Tests the type arg
	 *
	 * @expectedException InvalidArgumentException
	 * @covers DateTime::convertTo
	 * @dataProvider dataConvertToInvalidArgs
	 */
	public function testConvertToInvalidArgs($type) {
		DateTime::now()->convertTo($type);
	}

	/**
	 * Tests the same instance is returned with nothing changes.
	 *
	 * @covers DateTime::convertTo
	 */
	public function testConvertToIndentity() {
		$now = DateTime::now();
		$date = $now->convertTo(Type::DATE_TIME);
		$this->assertTrue($now === $date);
	}

	/**
	 * Data provider for testChangeYearsLeapDay
	 */
	public static function dataChangeYearsLeapDay() {
		return array(
				array('2012-02-29', '2013-02-28', '2011-02-28'),
				array('2000-02-29', '2001-02-28', '1999-02-28'),
				array('2400-02-29', '2401-02-28', '2399-02-28'),
			   );
	}

	/**
	 * Test leap years are handled correctly when adding or subtracting years.
	 *
	 * @covers DateTime::plusYears
	 * @covers DateTime::minusYears
	 * @dataProvider dataChangeYearsLeapDay
	 */
	public function testChangeYearsLeapDay($start, $plus, $minus) {
		$start = DateTime::parse($start, 'Y-m-d');
		$after = $start->plusYears(1);
		$this->assertEquals($plus, $after->toString('Y-m-d'));

		$after = $start->minusYears(1);
		$this->assertEquals($minus, $after->toString('Y-m-d'));
	}

	/**
	 * Tests toString with invalid input.
	 *
	 * @covers DateTime::toString
	 * @dataProvider dataNonStrings
	 * @expectedException \InvalidArgumentException
	 */
	public function testToStringInvalidFormat($nonString) {
		$date = DateTime::fromTimestamp(1368283531, 'America/Los_Angeles');
		$date->toString($nonString);
	}

	/**
	 * Data provider for testToStringValidFormat.
	 */
	public static function dataToStringValidFormat() {
		return array(
					array(DateTime::fromTimestamp(1368283531, 'America/New_York'), 'c', '2013-05-11T10:45:31-04:00'),
					array(DateTime::fromTimestamp(1368283531, 'America/New_York'), 'U', '1368283531'),
					array(DateTime::fromTimestamp(1368283531, 'America/Los_Angeles'), 'U', '1368283531'),
					array(DateTime::fromTimestamp(1368283531, 'Asia/Dubai'), 'r', 'Sat, 11 May 2013 18:45:31 +0400'),
					array(DateTime::fromTimestamp(1355202000, 'America/New_York'), 'c', '2012-12-11T00:00:00-05:00'),
					array(DateTime::fromTimestamp(1355202000, 'America/New_York'), 'U', '1355202000'),
					array(DateTime::fromTimestamp(1355202000, 'Asia/Dubai'), 'r', 'Tue, 11 Dec 2012 09:00:00 +0400'),
				);
	}

	/**
	 * Tests toString with valid input.
	 *
	 * @covers DateTime::toString
	 * @dataProvider dataToStringValidFormat
	 */
	public function testToStringValidFormat(DateTime $date, $format, $expected) {
		$this->assertEquals($expected, $date->toString($format));
	}

	/**
	 * Data provider for testToStringValid.
	 */
	public static function dataToStringValid() {
		return array(
				array(1368283531, 0, 'America/New_York'),
				array(1368283531, 5003, 'America/Los_Angeles'),
				array(1355202000, 1010, 'Asia/Dubai'),
				);
	}

	/**
	 * Tests __toString.
	 *
	 * @covers DateTime::__toString
	 * @dataProvider dataToStringValid
	 */
	public function testToStringValid($timestamp, $micros, $timeZone) {
		$date = DateTime::fromMicrosTimestamp($timestamp, $micros, $timeZone);
		$expected =  "DateTime [timestamp = {$timestamp} ; micros = {$micros} ; timeZone = {$timeZone}]";
		$this->assertEquals($expected, strval($date));
	}

	/**
	 * Tests the clone method.
	 */
	public function testClone() {
		$now = DateTime::now('America/Los_Angeles');
		$clone = clone $now;

		$this->assertFalse($now === $clone);
		$this->assertTrue($now->equals($clone));
	}

	/**
	 * Calling getTimestamp on a \DateTime object is dangerous. See item [1.2
	 * Timestamp corruption] in Bronto\Date\DateTime's documentation for details.
	 * This test verifies a \DateTime object is not corrupted when used with
	 * Bronto\Date\DateTime::createFrom.
	 *
	 * @covers DateTime::getTimestamp
	 * @covers DateTime::createFrom
	 */
	public function testDaylightSavingsBug() {
		$phpDateTime = new \DateTime('@1383469200');
		$phpDateTime->setTimezone(new \DateTimeZone('America/Los_Angeles'));
		$this->assertEquals(1383469200, Utils::extractTimestamp($phpDateTime));
		$dateTime = DateTime::createFrom($phpDateTime);
		$this->assertEquals(1383469200, Utils::extractTimestamp($phpDateTime));
		$this->assertEquals(1383469200, $dateTime->getTimestamp());
	}

	public function dataGetMillisTimestamp() {
		return array(
				array(1383469200123, DateTime::fromMillisTimestamp(1383469200123)),
				array(1383469200000, DateTime::fromMillisTimestamp(1383469200000)),
				array(0, DateTime::fromTimestamp(0)),
				array(-1383469200123, DateTime::fromMillisTimestamp(-1383469200123)),
				array(-1383469200000, DateTime::fromMillisTimestamp(-1383469200000)),
				array(1383469200123, DateTime::fromMicrosTimestamp(1383469200, 123000)),
				array(1383469200123, DateTime::fromMicrosTimestamp(1383469200, 123999)),
				array(-1383469200123, DateTime::fromMicrosTimestamp(-1383469201, 877000)),
				);
	}

	/**
	 * Basic tests for getMillisTimestamp
	 *
	 * @covers DateTime::getMillisTimestamp
	 * @dataProvider dataGetMillisTimestamp
	 */
	public function testGetMillisTimestamp($expected, DateTime $date) {
		$this->assertEquals($expected, $date->getMillisTimestamp());
	}

}

