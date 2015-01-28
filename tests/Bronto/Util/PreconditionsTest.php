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

namespace Bronto\Util;

use Bronto\Util\Preconditions;

/**
 * Preconditions tests
 *
 * @covers Bronto\Util\Preconditions
 */
class PreconditionsTest extends \PHPUnit_Framework_TestCase {

	/**
	 * Data provider for testIntValid
	 */
	public function dataIntValid() {
		return array(
				array(1, 1),
				array('3', 3),
				array('4332', 4332),
				array(-5, -5),
				array('-3', -3),
				array(0, 0),
				array('0', 0),
				);
	}

	/**
	 * Data provider for testIntInvalid
	 */
	public function dataIntInvalid() {
		return array(
				array(1.5),
				array(array(1)),
				array(new \stdClass()),
				array('0.1'),
				array('-2.5'),
				array('5.1'),
				array(null),
				array('2.0'),
				array('a4'),
				array('4a'),
				array('-006'),
				array('006'),
				);
	}

	/**
	 * Data provider for testStringValid
	 */
	public function dataStringValid() {
		return array(
				array('laskdjf'),
				array(''),
				array('123'),
				);
	}

	/**
	 * Data provider for testStringInvalid
	 */
	public function dataStringInvalid() {
		return array(
				array(null),
				array(new \stdClass()),
				array(123),
				array(1.5),
				array(array(1)),
				);
	}

	/**
	 * Tests requireInt with valid input.
	 *
	 * @dataProvider dataIntValid
	 * @covers Bronto\Util\Preconditions::requireInt
	 */
	public function testIntValid($value, $expected) {
		$this->assertEquals($expected, Preconditions::requireInt($value, '$value'));
	}

	/**
	 * Tests requireInt with invalid input.
	 *
	 * @dataProvider dataIntInvalid
	 * @expectedException \InvalidArgumentException
	 * @covers Bronto\Util\Preconditions::requireInt
	 */
	public function testIntInvalid($value) {
		Preconditions::requireInt($value, '$value');
	}

	/**
	 * Tests requireString with valid input.
	 *
	 * @dataProvider dataStringValid
	 * @covers Bronto\Util\Preconditions::requireString
	 */
	public function testStringValid($value) {
		$this->assertEquals($value, Preconditions::requireString($value, '$value'));
	}

	/**
	 * Tests requireString with invalid input.
	 *
	 * @dataProvider dataStringInvalid
	 * @expectedException \InvalidArgumentException
	 * @covers Bronto\Util\Preconditions::requireString
	 */
	public function testStringInvalid($value) {
		Preconditions::requireString($value, '$value');
	}

}

