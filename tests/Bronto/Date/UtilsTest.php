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

use \Bronto\Date\Utils as Utils;

/**
 * Date Utils tests
 *
 * @covers \Bronto\Date\Utils
 */
class UtilsTest extends \Bronto\Date\AbstractTest {

	/**
	 * Data provider for testExtractTimestamp
	 */
	public static function dataExtractTimestamp() {
		return array(
				array(1373462199, 'America/New_York', '2013-07-10T09:16:39-04:00'),
				array(1383458400, 'America/New_York', '2013-11-03T01:00:00-05:00'),
				array(1383458399, 'America/New_York', '2013-11-03T01:59:59-04:00'),
				);
	}

	/**
	 * Tests extractTimestamp
	 *
	 * @covers \Bronto\Date\Utils::extractTimestamp
	 * @dataProvider dataExtractTimestamp
	 */
	public function testExtractTimestamp($ts, $tz, $expectedFormat) {
		$date = new \DateTime('now', new \DateTimeZone('UTC'));
		$date->setTimestamp($ts);
		$date->setTimezone(new \DateTimeZone($tz));

		$stamp = Utils::extractTimestamp($date);
		$this->assertEquals($ts, $stamp);
		$this->assertEquals($expectedFormat, $date->format('c'));

		// Check again to make sure the $date didn't change. See
		// \Bronto\Date\DateTime's docblock section [1.2 Timestamp corruption] for
		// more details.
		$stamp = Utils::extractTimestamp($date);
		$this->assertEquals($ts, $stamp);
		$this->assertEquals($expectedFormat, $date->format('c'));
	}

	/**
	 * Data provider for testExtractMicroseconds
	 */
	public static function dataExtractMicroseconds() {
		return array(
				array(1373462199, 123456, 'America/New_York', '2013-07-10T09:16:39-04:00'),
				array(1373462199, 0, 'America/New_York', '2013-07-10T09:16:39-04:00'),
				array(1383458400, 234500,'America/New_York', '2013-11-03T01:00:00-05:00'),
				array(1383458399, 343400,'America/New_York', '2013-11-03T01:59:59-04:00'),
				);
	}

	/**
	 * Test extractMicroseconds
	 *
	 * @covers \Bronto\Date\Utils::extractMicroseconds
	 * @dataProvider dataExtractMicroseconds
	 */
	public function testExtractMicroseconds($ts, $micros, $tz, $expectedFormat) {
		$date = \DateTime::createFromFormat('u', $micros, new \DateTimeZone('UTC'));
		$date->setTimestamp($ts);
		$date->setTimezone(new \DateTimeZone($tz));

		$resultingMicros = Utils::extractMicroseconds($date);
		$this->assertEquals($micros, $resultingMicros);
		$this->assertEquals($expectedFormat, $date->format('c'));

		// Check again to make sure the $date didn't change. See
		// \Bronto\Date\DateTime's docblock section [1.2 Timestamp corruption] for
		// more details.
		$resultingMicros = Utils::extractMicroseconds($date);
		$this->assertEquals($micros, $resultingMicros);
		$this->assertEquals($expectedFormat, $date->format('c'));
	}

}

