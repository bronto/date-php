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

use Bronto\Date\TimeZone;

/**
 * Preconditions tests
 *
 * @covers Bronto\Date\Preconditions
 */
class PreconditionsTest extends \PHPUnit_Framework_TestCase {

	/**
	 * Data provider for teestTimeZoneValid
	 */
	public function dataTimeZoneValid() {
		return array(
				array('America/New_York', 'America/New_York'),
				array('UTC', 'UTC'),
				array('+0400', '+04:00'),
				array(TimeZone::parse('America/Los_Angeles'), 'America/Los_Angeles'),
				array(TimeZone::parse('UTC'), 'UTC'),
				array(TimeZone::parse('+1200'), '+12:00'),
				);
	}

	/**
	 * Data provider for testTimeZoneInvalid
	 */
	public function dataTimeZoneInvalid() {
		return array(
				array('EST'),
				array(new \stdClass()),
				array(array(1)),
				array(null),
				array(1234),
				array('blah'),
				);
	}

	/**
	 * Tests requireTimeZone with valid input.
	 *
	 * @dataProvider dataTimeZoneValid
	 * @covers Bronto\Date\Preconditions::requireTimeZone
	 */
	public function testTimeZoneValid($value, $expected) {
		$this->assertEquals($expected, Preconditions::requireTimeZone($value, 'tz')->getName());
	}

	/**
	 * Tests requireTimeZone with invalid input.
	 *
	 * @dataProvider dataTimeZoneInvalid
	 * @expectedException \InvalidArgumentException
	 * @covers Bronto\Date\Preconditions::requireTimeZone
	 */
	public function testTimeZoneInvalid($value) {
		Preconditions::requireTimeZone($value, 'tz');
	}

}

