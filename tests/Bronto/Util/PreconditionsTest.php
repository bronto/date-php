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

