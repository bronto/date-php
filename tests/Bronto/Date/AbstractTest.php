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

