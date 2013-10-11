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

