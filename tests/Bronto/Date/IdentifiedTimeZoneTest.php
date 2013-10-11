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
 * IdentifiedTimeZone tests
 *
 * @covers Bronto\Date\IdentifiedTimeZone
 */
class IdentifiedTimeZoneTest extends TimeZoneTestBase {

	/**
	 * Data provider for testParseValid
	 */
	public static function dataParseValid() {
		$data = array();

		foreach (static::$validIdentifiedZoneNames as $zone) {
			$date = new \DateTime($zone);
			$data[] = array($zone, $date->getTimezone());
		}

		return $data;
	}

	/**
	 * Data provider for testParseInvalid
	 */
	public static function dataParseInvalid() {
		$data = array();

		foreach (static::$validOffsetZoneNames as $zone) {
			$date = new \DateTime($zone[0]);
			$data[] = array($zone[0]);
		}

		foreach (static::$validAbbreviatedZoneNames as $zone) {
			$date = new \DateTime($zone);
			$data[] = array($zone);
		}

		foreach (static::$invalidTimeZoneNames as $zone) {
			$data[] = array($zone);
		}

		return $data;
	}

	/**
	 * Data provider for testFromDateTimeZoneValid
	 */
	public static function dataFromDateTimeZoneValid() {
		$data = array();

		foreach (static::$validIdentifiedZoneNames as $zone) {
			$date = new \DateTime($zone);
			$data[] = array($date->getTimezone());
		}

		return $data;
	}

	/**
	 * Data provider for testFromIdValid
	 */
	public static function dataFromIdValid() {
		$data = array();

		foreach (static::$validIdentifiedZoneNames as $zone) {
			$data[] = array($zone);
		}

		return $data;
	}

	/**
	 * Data provider for testFromIdInvalid
	 */
	public static function dataFromIdInvalid() {
		$data = array();

		foreach (static::$validOffsetZoneNames as $zone) {
			$date = new \DateTime($zone[0]);
			$data[] = array($zone[0]);
		}

		foreach (static::$validAbbreviatedZoneNames as $zone) {
			$date = new \DateTime($zone);
			$data[] = array($zone);
		}

		foreach (static::$invalidTimeZoneNames as $zone) {
			$data[] = array($zone);
		}

		return $data;
	}

	/**
	 * Tests parse method with valid input.
	 *
	 * @dataProvider dataParseValid
	 * @covers Bronto\Date\IdentifiedTimeZone::parse
	 */
	public function testParseValid($zone, $expected) {
		$this->assertEquals($expected->getName(), IdentifiedTimeZone::parse($zone)->getName());
	}

	/**
	 * Tests the parse method with bad args.
	 *
	 * @dataProvider dataParseInvalid
	 * @expectedException \InvalidArgumentException
	 * @covers Bronto\Date\IdentifiedTimeZone::parse
	 */
	public function testParseInvalid($zone) {
		IdentifiedTimeZone::parse($zone);
	}

	/**
	 * Tests fromDateTimeZone with valid input. This method should always return
	 * a BadMethodCallException as it should only be used with
	 * TimeZone::fromDateTimeZone.
	 *
	 * @dataProvider dataFromDateTimeZoneValid
	 * @covers Bronto\Date\IdentifiedTimeZone::fromDateTimeZone
	 * @expectedException \BadMethodCallException
	 */
	public function testFromDateTimeZoneValid(\DateTimeZone $zone) {
		IdentifiedTimeZone::fromDateTimeZone($zone);
	}

	/**
	 * Tests fromId with valid input.
	 *
	 * @covers Bronto\Date\IdentifiedTimeZone::fromId
	 * @dataProvider dataFromIdValid
	 */
	public function testFromIdValid($id) {
		$this->assertEquals($id, IdentifiedTimeZone::fromId($id)->getName());
	}

	/**
	 * Tests fromId with invalid input.
	 *
	 * @covers Bronto\Date\IdentifiedTimeZone::fromId
	 * @dataProvider dataFromIdInvalid
	 * @expectedException \InvalidArgumentException
	 */
	public function testFromIdInvalid($id) {
		IdentifiedTimeZone::fromId($id);
	}

}

