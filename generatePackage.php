<?php

error_reporting(E_ALL & ~E_DEPRECATED);

require_once('PEAR/PackageFileManager2.php');

PEAR::setErrorHandling(PEAR_ERROR_DIE);

$packagexml = new PEAR_PackageFileManager2;

$packagexml->setOptions(array(
    'baseinstalldir'    => '/',
    'simpleoutput'      => true,
    'packagedirectory'  => './',
    'filelistgenerator' => 'file',
    'ignore'            => array('phpunit-bootstrap.php', 'phpunit.xml', 'generatePackage.php'),
    'dir_roles' => array(
        'tests'       => 'test',
        'public_html' => 'doc',
        'doc'         => 'doc'
    ),
));

$packagexml->setPackage('EZRP');
$packagexml->setSummary('PHP interface for handling OAuth, OpenID, and Facebook connections consistently');
$packagexml->setDescription('See ezrp.me for details');

$packagexml->setChannel('shupp.github.com/pirum');
$packagexml->setAPIVersion('0.1.0');
$packagexml->setReleaseVersion('0.1.0');

$packagexml->setReleaseStability('alpha');

$packagexml->setAPIStability('alpha');

$packagexml->setNotes('
* Fixed ezrpPath to be more flexible
* Updated dependencies to get other bug fixes
* Tagged 0.1.0 release
');
$packagexml->setPackageType('php');
$packagexml->addRelease();

$packagexml->detectDependencies();

$packagexml->addMaintainer('lead',
                           'shupp',
                           'Bill Shupp',
                           'shupp@php.net');
$packagexml->setLicense('New BSD License',
                        'http://www.opensource.org/licenses/bsd-license.php');

$packagexml->setPhpDep('5.1.2');
$packagexml->setPearinstallerDep('1.4.0b1');
$packagexml->addPackageDepWithChannel('required', 'HTTP_OAuth', 'pear.php.net', '0.2.2');
$packagexml->addPackageDepWithChannel('required', 'OpenID', 'pear.php.net', '0.3.2');
$packagexml->addPackageDepWithChannel('required', 'Net_URL2', 'pear.php.net', '0.2.0');
$packagexml->addPackageDepWithChannel('required', 'Cache_Lite', 'pear.php.net');

$packagexml->generateContents();
$packagexml->writePackageFile();

?>
