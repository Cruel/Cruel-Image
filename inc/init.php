<?php

define('DOC_ROOT', realpath(dirname(__FILE__) . '/../'));
define('URL_ROOT', substr(DOC_ROOT, strlen(realpath($_SERVER['DOCUMENT_ROOT']))) . '/');

if (!include(DOC_ROOT.'/inc/config.php')){
	if (!include(DOC_ROOT.'/install/install.php')){
		die('No installation files detected.');
	}
	IS_INSTALLED or die();
	die('installed');
}

fTimestamp::setDefaultTimezone(CI_TIMEZONE);
fTimestamp::defineFormat('default', CI_DATEFORMAT);

error_reporting(E_STRICT | E_ALL);
fCore::enableDebugging(FALSE);
fCore::enableErrorHandling('html');
fCore::enableExceptionHandling('html');

$template = new fTemplating(DOC_ROOT . '/views/');
$template->set('header', 'header.php');
$template->set('footer', 'footer.php');
//$template->enableMinification('development', $_SERVER['DOCUMENT_ROOT'] . '/static/cache/');

$db  = new fDatabase(CI_DB_TYPE, CI_DB_NAME, CI_DB_USER, CI_DB_PASS, CI_DB_HOST, CI_DB_PORT);
fORMDatabase::attach($db);

// This prevents cross-site session transfer
//fSession::setPath(DOC_ROOT . '/storage/session/');
fSession::open();

// http://php.net/manual/en/language.oop5.autoload.php
function __autoload($class) {
	$file = DOC_ROOT . "/inc/flourish/$class.php";
	if (file_exists($file))
		return require $file;
	$file = DOC_ROOT . "/models/$class.php";
	if (file_exists($file))
		return require $file;
	throw new Exception('The class ' . $class_name . ' could not be loaded');
}