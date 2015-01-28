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

