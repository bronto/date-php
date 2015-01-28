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
 * TimeZone tests
 *
 * @covers \Bronto\Date\TimeZone
 */
class TimeZoneTest extends TimeZoneTestBase {

	/**
	 * Data provider for testParseValid
	 */
	public static function dataParseValid() {
		$data = array();

		foreach (static::$validOffsetZoneNames as $zone) {
			$date = new \DateTime($zone[0]);
			$data[] = array($zone[0], $date->getTimezone());
		}

		foreach (static::$validIdentifiedZoneNames as $zone) {
			$data[] = array($zone, new \DateTimeZone($zone));
		}

		return $data;
	}

	/**
	 * Data provider for testParseWrongType
	 */
	public static function dataParseWrongType() {
		foreach (static::$invalidTimeZoneNames as $zone) {
			$data[] = array($zone);
		}

		foreach (static::$validAbbreviatedZoneNames as $zone) {
			$data[] = array($zone);
		}

		return $data;
	}

	/**
	 * Data provider for testInvalidZones
	 */
	public static function dataInvalidZones() {
		$data = array();

		foreach (static::$validAbbreviatedZoneNames as $zone) {
			$date = new \DateTime($zone);
			$data[] = array($date->getTimezone());
		}

		return $data;
	}

	/**
	 * Data provider for testFromDateTimeZoneValid
	 */
	public static function dataFromDateTimeZoneValid() {
		$data = array();

		foreach (static::$validOffsetZoneNames as $zone) {
			$date = new \DateTime($zone[0]);
			$data[] = array($date->getTimezone(), $zone[1]);
		}

		foreach (static::$validIdentifiedZoneNames as $zone) {
			$data[] = array(new \DateTimeZone($zone), $zone);
		}

		return $data;
	}

	/**
	 * Tests the parse method works with valid input.
	 *
	 * @dataProvider dataParseValid
	 * @covers \Bronto\Date\TimeZone::parse
	 */
	public function testParseValid($zone, $expected) {
		$this->assertEquals($expected->getName(), TimeZone::parse($zone)->getName());
	}

	/**
	 * Tests the parse method with invalid input.
	 *
	 * @dataProvider dataParseWrongType
	 * @expectedException \InvalidArgumentException
	 * @covers \Bronto\Date\TimeZone::parse
	 */
	public function testParseWrongType($zone) {
		TimeZone::parse($zone);
	}

	/**
	 * Tests the fromDateTimeZone method with valid input.
	 *
	 * @dataProvider dataFromDateTimeZoneValid
	 * @covers \Bronto\Date\TimeZone::fromDateTimeZone
	 */
	public function testFromDateTimeZoneValid(\DateTimeZone $zone, $expectedZoneName) {
		$this->assertEquals($expectedZoneName, TimeZone::fromDateTimeZone($zone)->getName());
	}

	/**
	 * Tests fromDateTimeZone method with invalid input.
	 *
	 * @dataProvider dataInvalidZones
	 * @expectedException \InvalidArgumentException
	 * @covers \Bronto\Date\TimeZone::fromDateTimeZone
	 */
	public function testFromDateTimeZoneInvalid(\DateTimeZone $zone) {
		TimeZone::fromDateTimeZone($zone);
	}

	/**
	 * Tests the parse method with edge case UTC inputs.
	 *
	 * @covers \Bronto\Date\TimeZone::parse
	 */
	public function testParseUTC() {
		$this->assertEquals('UTC', strval(TimeZone::parse('utc')));
		$this->assertTrue(TimeZone::parse('utc') === TimeZone::parse('UTC'));
	}

	/**
	 * Tests fromDateTimeZone method with edge case UTC inputs.
	 *
	 * @covers \Bronto\Date\TimeZone::fromDateTimeZone
	 */
	public function testFromDateTimeZoneUTC() {
		$date = new \DateTime('utc');
		$zone = $date->getTimezone();
		$timeZone = TimeZone::fromDateTimeZone($zone);
		$this->assertEquals('UTC', strval($timeZone));
	}

	/**
	 * Tests equals with valid input.
	 *
	 * @covers \Bronto\Date\TimeZone::equals
	 * @dataProvider dataParseValid
	 */
	public function testEqualsValid($tz) {
		$tz0 = TimeZone::parse($tz);
		$tz1 = TimeZone::parse($tz);
		$this->assertTrue($tz0->equals($tz1));
		$this->assertTrue($tz1->equals($tz0));

		$other = TimeZone::parse('Africa/Dakar');
		$this->assertFalse($tz0->equals($other));
		$this->assertFalse($other->equals($tz0));
	}

	/**
	 * Data provider for testGetUtcOffset
	 */
	public static function dataGetUtcOffset() {
		return array(
				// "2013-03-10 00:00:00 America/New_York", 0 hours
				array('UTC', 1362891600, 0),
				// "2013-03-10 03:00:00 America/New_York", 0 hours
				array('UTC', 1362898800, 0),
				// "2013-03-10 00:00:00 America/New_York", -5 hours
				array('America/New_York', 1362891600, -18000),
				// "2013-03-10 03:00:00 America/New_York", -4 hours
				array('America/New_York', 1362898800, -14400),
				);
	}

	/**
	 * Tests getUtcOffset
	 *
	 * @covers \Bronto\Date\TimeZone::getUtcOffset
	 * @dataProvider dataGetUtcOffset
	 */
	public function testGetUtcOffset($tz, $timestamp, $expectedOffset) {
		$tz = TimeZone::parse($tz);
		$offset = $tz->getUtcOffset($timestamp);
		$this->assertEquals($expectedOffset, $offset);
	}

}

