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
 * @license     http://www.apache.org/licenses/LICENSE-2.0
 * @link        https://github.com/bronto/date-php
 */


date_default_timezone_set('America/New_York');

// set error reporting high
error_reporting(E_ALL | E_STRICT);

// make sure we see them
ini_set('display_errors', 'On');

// make sure current directory and class directories are on include path
// this is necessary for auto load to work
set_include_path(
	// distribution files (where the zip / tgz is unpacked)
	dirname(dirname(__FILE__)) . PATH_SEPARATOR .

	// test file directory "tests"
	dirname(__FILE__) . PATH_SEPARATOR .

	// current include path (for PHPUnit, etc.)
	get_include_path()
);

spl_autoload_register(
	function ($className)
	{
	    $className = ltrim($className, '\\');
	    $fileName  = '';
	    $namespace = '';
	    if ($lastNsPos = strripos($className, '\\')) {
	        $namespace = substr($className, 0, $lastNsPos);
	        $className = substr($className, $lastNsPos + 1);
	        $fileName  = str_replace('\\', DIRECTORY_SEPARATOR, $namespace) . DIRECTORY_SEPARATOR;
	    }
	    $fileName .= str_replace('_', DIRECTORY_SEPARATOR, $className) . '.php';
	
	    require $fileName;
	}
);
