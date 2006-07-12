<?php

/**
 * This file creates a package.xml which describes the pear package to be built.
 * 
 * It provides the package.xml from which you can run pear package and create a 
 * distributable Package.tgz installable through the PEAR installer.
 * 
 * <code>
 * php makepackage.php make && pear package && pear install _______.tgz
 * </code>
 */

ini_set('display_errors',true);
require_once 'PEAR/PackageFileManager2.php';
require_once 'PEAR/PackageFileManager/File.php';
require_once 'PEAR/Task/Postinstallscript/rw.php';
require_once 'PEAR/Config.php';
require_once 'PEAR/Frontend.php';

/**
 * @var PEAR_PackageFileManager
 */
PEAR::setErrorHandling(PEAR_ERROR_DIE);
chdir(dirname(__FILE__));
//$pfm = PEAR_PackageFileManager2::importOptions('package.xml', array(
$pfm = new PEAR_PackageFileManager2();
$pfm->setOptions(array(
	'packagedirectory' => dirname(__FILE__),
	'baseinstalldir' => 'UNL/UCBCN',
	'filelistgenerator' => 'file',
	'ignore' => array(	'package.xml',
						'.project',
						'*.tgz',
						'makepackage.php',
						'*CVS/*',
						'.cache',
						'install.sh'),
	'simpleoutput' => true,
	'roles'=>array('php'=>'data'	),
	'exceptions'=>array('UNL_UCBCN_Frontend_setup.php'=>'php',
						'Frontend.php'=>'php',
						'Frontend/Month.php'=>'php',
						'Frontend/Day.php'=>'php',
						'Frontend/MonthWidget.php'=>'php',
						'Frontend/Year.php'=>'php')
));
$pfm->setPackage('UNL_UCBCN_Frontend');
$pfm->setPackageType('php'); // this is a PEAR-style php script package
$pfm->setSummary('This package provides a viewing frontend to the calendar data.');
$pfm->setDescription('This class extends the UNL UCBerkeley Calendar backend system to create
			a client frontend. It allows users to view the calendar in a list view, thirty
			day view, subscribable feeds, downloadable .ics files etc.');
$pfm->setChannel('pear.unl.edu');
$pfm->setAPIStability('alpha');
$pfm->setReleaseStability('alpha');
$pfm->setAPIVersion('0.0.1');
$pfm->setReleaseVersion('0.0.1');
$pfm->setNotes('Initial Release... this is really bare-bones.');

$pfm->addMaintainer('lead','saltybeagle','Brett Bieber','brett.bieber@gmail.com');
$pfm->setLicense('PHP License', 'http://www.php.net/license');
$pfm->clearDeps();
$pfm->setPhpDep('5.0.0');
$pfm->setPearinstallerDep('1.4.3');
$pfm->addPackageDepWithChannel('required', 'UNL_UCBCN', 'pear.unl.edu', '0.0.1');
$pfm->addPackageDepWithChannel('required', 'Calendar', 'pear.php.net', '0.5.3');
foreach (array('Frontend.php','UNL_UCBCN_Frontend_setup.php','index.php') as $file) {
	$pfm->addReplacement($file, 'pear-config', '@PHP_BIN@', 'php_bin');
	$pfm->addReplacement($file, 'pear-config', '@PHP_DIR@', 'php_dir');
	$pfm->addReplacement($file, 'pear-config', '@DATA_DIR@', 'data_dir');
	$pfm->addReplacement($file, 'pear-config', '@DOC_DIR@', 'doc_dir');
}

$config = PEAR_Config::singleton();
$log = PEAR_Frontend::singleton();
$task = new PEAR_Task_Postinstallscript_rw($pfm, $config, $log,
    array('name' => 'UNL_UCBCN_Frontend_setup.php', 'role' => 'php'));
$task->addParamGroup('questionCreate', array(
	$task->getParam('createtemplate',	'Create/Upgrade default templates?', 'string', 'yes'),
	$task->getParam('createindex',	'Create/Upgrade sample index page?', 'string', 'yes'),
	));
$task->addParamGroup('fileSetup', array(
	$task->getParam('docroot',		'Path to root of webserver', 'string', '/Library/WebServer/Documents/events'),
	$task->getParam('template',	'Template style to use', 'string', 'default')
    ));

$pfm->addPostinstallTask($task, 'UNL_UCBCN_Frontend_setup.php');
$pfm->generateContents();
if (isset($_SERVER['argv']) && $_SERVER['argv'][1] == 'make') {
    $pfm->writePackageFile();
} else {
    $pfm->debugPackageFile();
}
?>