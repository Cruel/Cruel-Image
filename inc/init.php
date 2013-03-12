<?php

require_once('loader.php');
if (!include('config.php')){
	if (!include(DOC_ROOT.'/install/install.php')){
		die('No installation files detected.');
	}
	die();
}
require_once('plugins.php');

fTimestamp::setDefaultTimezone(CI_TIMEZONE);
fTimestamp::defineFormat('default', CI_DATEFORMAT);

error_reporting(E_STRICT | E_ALL);
fCore::enableDebugging(FALSE);
fCore::enableErrorHandling('html');
fCore::enableExceptionHandling('html');

$db  = new fDatabase(CI_DB_TYPE, CI_DB_NAME, CI_DB_USER, CI_DB_PASS, CI_DB_HOST, CI_DB_PORT);
fORMDatabase::attach($db);

$template = new fTemplating(DOC_ROOT . '/views/');
$template->set('db', $db);
$template->set('header', 'header.php');
$template->set('footer', 'footer.php');
$template->enableMinification('production', DOC_ROOT.'/static/cache/min/');

//Load settings table into constants
$settings = $db->query("SELECT * FROM settings");
foreach ($settings as $row) {
	define('CI_'.$row['name'], $row['value']);
}

// This prevents cross-site session transfer
//fSession::setPath(DOC_ROOT . '/storage/session/');
fSession::open();