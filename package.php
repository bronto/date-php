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

date_default_timezone_set('America/New_York');

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

error_reporting(E_ALL & ~E_DEPRECATED);
PEAR::setErrorHandling(PEAR_ERROR_DIE);

$release_version = '1.0.1';
$release_state   = 'stable';
$release_notes   = 'Initial release';
$description = 'bronto/Date provides an intuitive and easy-to-use API for date/datetime work in PHP.';

$package = new PEAR_PackageFileManager2();

$package->setOptions(
    array(
        'filelistgenerator'       => 'file',
        'simpleoutput'            => true,
        'baseinstalldir'          => '/',
        'packagedirectory'        => './',
        'dir_roles'               => array(
            'Bronto'              => 'php',
            'Bronto/Date'         => 'php',
            'Bronto/Util'         => 'php',
            'tests'               => 'test'
        ),
        'ignore'                  => array(
            'package.php',
            '*.tgz',
        )
    )
);

$package->setPackage('Date');
$package->setSummary('Alternative Datetime API');
$package->setDescription($description);
$package->setChannel('bronto.github.com/pear');
$package->setPackageType('php');
$package->setLicense(
    'Apache License, Version 2.0',
    'http://www.apache.org/licenses/LICENSE-2.0'
);

$package->setNotes($release_notes);
$package->setReleaseVersion($release_version);
$package->setReleaseStability($release_state);
$package->setAPIVersion($release_version);
$package->setAPIStability($release_state);

$package->addMaintainer(
    'lead',
    'scudellari',
    'Ryan Scudellari',
    'ryan@scudellari.com'
);

$package->setPhpDep('5.3.0');
$package->addPackageDepWithChannel('optional', 'Mockery', 'pear.survivethedeepend.com');
$package->setPearInstallerDep('1.9.4');
$package->generateContents();
$package->addRelease();

if (isset($_SERVER['argv']) && @$_SERVER['argv'][1] == 'debug') {
    $package->debugPackageFile();
} else {
    $package->writePackageFile();
}

