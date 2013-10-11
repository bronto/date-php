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

/**
 * Base TimeZone test
 */
abstract class TimeZoneTestBase extends \Bronto\Date\AbstractTest {

	protected static $validAbbreviatedZoneNames = array(
			'EST',
			'EDT',
			'BOT',
			'AZOST',
			'MST',
			'YAKST',
		);
	
	protected static $validIdentifiedZoneNames = array(
			'America/New_York',
			'Pacific/Apia',
			'Pacific/Kosrae',
			'Indian/Mahe',
			'Europe/Isle_of_Man',
			'Australia/Lord_Howe',
			'Atlantic/Madeira',
			'Asia/Dhaka',
			'Arctic/Longyearbyen',
			'Antarctica/Palmer',
			'Africa/Djibouti',
			'UTC',
		);

	protected static $validOffsetZoneNames = array(
			array('+0400', '+04:00'),
			array('-0400', '-04:00'),
			array('+1234', '+12:34'),
			array('-1929', '-19:29'),
			array('+12:44', '+12:44'),
			array('-00:12',  '-00:12'),
			);

	protected static $invalidTimeZoneNames = array(
			123,
			'blah',
			null,
			array(1),
			'1970 bloop UTC',
			'',
			'2013 UTC',
			'2013-05-06 01:55:21 America/New_York',
			);

}

