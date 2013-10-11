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

