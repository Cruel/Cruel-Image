<?php

require_once('loader.php');
if (!include('config.php')){
	if (!include('../install/install.php')){
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
$template->enableMinification('development', $_SERVER['DOCUMENT_ROOT'] . '/static/cache/min/');

$db  = new fDatabase(CI_DB_TYPE, CI_DB_NAME, CI_DB_USER, CI_DB_PASS, CI_DB_HOST, CI_DB_PORT);
fORMDatabase::attach($db);

// This prevents cross-site session transfer
//fSession::setPath(DOC_ROOT . '/storage/session/');
fSession::open();