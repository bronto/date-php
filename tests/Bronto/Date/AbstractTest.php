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
require_once('Mockery/Loader.php');
require_once('Hamcrest/Hamcrest.php');

use \Mockery as m;

/**
 * The base class for Date unit tests.
 */
abstract class AbstractTest extends \PHPUnit_Framework_TestCase {

	/**
	 * This setup method is run before the test class is run.
	 */
	public static function setUpBeforeClass() {
		$loader = new \Mockery\Loader();
		$loader->register();
	}

	/**
	 * This setup method is run before each unit test.
	 */
	protected function setUp() { }
	
	/**
	 * This tear down method is run after each unit test.
	 */
	protected function tearDown() {
		m::close();
	}

	/**
	 * Test an argument is correctly validated as an int. The Closure argument
	 * must take a single argument of mixed type (not type hinted) and passed
	 * into the method to be tested.
	 *
	 * @param \Closure $callback a Closure that takes a single argument and
	 * passes it into the method being tested
	 */
	protected function assertIntArg(\Closure $callback) {
		$invalidValues = array(null, '123a', new \stdClass(), $callback, 12.3, array(1));

		foreach ($invalidValues as $value) {
			try {
				$callback($value);
				$this->fail('Expected an InvalidArgumentException but nothing was thrown when using $value = ' . $value);
			} catch (\InvalidArgumentException $e) {
				// Expected, pass!
			}
		}
	}

	/**
	 * Test an argument is correctly validated as a time zone. The Closure argument
	 * must take a single argument of mixed type (not type hinted) and passed
	 * into the method to be tested.
	 *
	 * @param \Closure $callback a Closure that takes a single argument and
	 * passes it into the method being tested
	 */
	protected function assertTimeZoneArg(\Closure $callback) {
		$invalidValues = array('123a', new \stdClass(), $callback, 12.3, array(1));

		foreach ($invalidValues as $value) {
			try {
				$callback($value);
				$this->fail('Expected an InvalidArgumentException but nothing was thrown when using $value = ' . $value);
			} catch (\InvalidArgumentException $e) {
				// Expected, pass!
			}
		}

		$validValues = array('America/New_York', 'America/Los_Angeles', \Bronto\Date\TimeZone::parse('America/New_York'));

		foreach ($validValues as $value) {
			try {
				$callback($value);
			} catch (\InvalidArgumentException $e) {
				$this->fail("An unexpected InvalidArgumentException was thrown: {$e->getMessage()}");
			}
		}
	}
}

