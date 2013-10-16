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
    'MIT License',
    'http://www.opensource.org/licenses/mit-license.html'
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

