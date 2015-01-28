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

