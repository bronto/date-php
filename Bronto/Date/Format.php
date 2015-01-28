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
 * Valid date formats to be used with \Bronto\Date\DateTime::toString.
 */
class Format {

    // Datetime formats
	const MYSQL_DATE_TIME = 'Y-m-d H:i:s';
	const ISO_8601_UTC  = 'Y-m-d\TH:i:s.u\Z';
	const SALESFORCE = 'Y-m-d\TH:i:s+0000';
	const ISO_8601 = 'Y-m-d\TH:i:sO';
	const COOKIE = 'l, d-M-y H:i:s T';

    // Date formats
	const MYSQL_DATE      = 'Y-m-d';
}
