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
 * OffsetTimeZone tests
 *
 * @covers Bronto\Date\OffsetTimeZone
 */
class OffsetTimeZoneTest extends TimeZoneTestBase {

	/**
	 * Data provider for testParseValid
	 */
	public function dataParseValid() {
		$data = array();

		foreach (static::$validOffsetZoneNames as $zone) {
			$date = new \DateTime($zone[0]);
			$data[] = array($zone[0], $date->getTimezone());
		}

		return $data;
	}

	/**
	 * Data provider for testParseInvalid
	 */
	public function dataParseInvalid() {
		$data = array();

		foreach (static::$validIdentifiedZoneNames as $zone) {
			$date = new \DateTime($zone);
			$data[] = array($zone);
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
	public function dataFromDateTimeZoneValid() {
		$data = array();

		foreach (static::$validOffsetZoneNames as $zone) {
			$date = new \DateTime($zone[0]);
			$data[] = array($date->getTimezone());
		}

		return $data;
	}

	/**
	 * Tests parse method with valid input.
	 *
	 * @dataProvider dataParseValid
	 * @covers Bronto\Date\OffsetTimeZone::parse
	 */
	public function testParseValid($zone, $expected) {
		$this->assertEquals($expected->getName(), OffsetTimeZone::parse($zone)->getName());
	}

	/**
	 * Tests the parse method with bad args.
	 *
	 * @dataProvider dataParseInvalid
	 * @expectedException \InvalidArgumentException
	 * @covers Bronto\Date\OffsetTimeZone::parse
	 */
	public function testParseInvalid($zone) {
		OffsetTimeZone::parse($zone);
	}

	/**
	 * Tests fromDateTimeZone with valid input. This method should always return
	 * a BadMethodCallException as it should only be used with
	 * TimeZone::fromDateTimeZone.
	 *
	 * @dataProvider dataFromDateTimeZoneValid
	 * @covers Bronto\Date\OffsetTimeZone::fromDateTimeZone
	 * @expectedException \BadMethodCallException
	 */
	public function testFromDateTimeZoneValid(\DateTimeZone $zone) {
		OffsetTimeZone::fromDateTimeZone($zone);
	}

	/**
	 * Tests a BadMethodCallException is thrown when calling utc().
	 *
	 * @covers Bronto\Date\OffsetTimeZone::utc
	 * @expectedException \BadMethodCallException
	 */
	public function testUtc() {
		OffsetTimeZone::utc();
	}

}

