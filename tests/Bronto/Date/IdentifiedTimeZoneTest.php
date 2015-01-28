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

