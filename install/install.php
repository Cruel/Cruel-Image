<?php

$template = new fTemplating(DOC_ROOT.'/install/');
//fCore::enableDebugging(TRUE);
//fCore::enableErrorHandling('html');
//fCore::enableExceptionHandling('html');

function createConfiguration($db_create=FALSE){
	$db_type = fRequest::get('db_type', 'string', 'mysql');
	$db_name = fRequest::get('db_name', 'string');
	$db_user = fRequest::get('db_user', 'string');
	$db_pass = fRequest::get('db_pass', 'string');
	$db_host = fRequest::get('db_host');
	$db_port = fRequest::get('db_port');
	try {
		$db = new fDatabase($db_type, $db_name, $db_user, $db_pass, $db_host, $db_port);
		if ($db_create){
			// TODO: Check for exiting tables and DROP (optionally)
			$db->execute(file_get_contents(DOC_ROOT.'/install/install.sql'));
			fJSON::output(array('success'=>TRUE));
		} else {
			fORMDatabase::attach($db);
			$title = fRequest::get('title', 'string', 'Cruel Image Hosting');
			$path = fRequest::get('path', 'string', fURL::get());

			$user = new User();
			$user->setName(strtolower(fRequest::get('admin_name')));
			$user->setPassword($user->hashedPassword(fRequest::get('admin_pass', 'string', NULL, TRUE)));
			$user->setLevel('superadmin');
			$user->store();

			$settings = array(
				"('APIKEY', 'asdf')",
				"('THEME', 'chevereto')",
				"('TITLE', '$title')",
				"('ADMINTAB', '0')",
			);
			$sql = "INSERT INTO `settings` (`name`, `value`) VALUES ";
			$sql .= implode(',', $settings);
			$db->execute($sql);

			$var_arr = array('db_type', 'db_name', 'db_user', 'db_pass', 'db_host', 'db_port', 'title', 'path');
			$config = file_get_contents(DOC_ROOT.'/inc/config.dist.php');
			$config_file = DOC_ROOT.'/inc/config.php';
			foreach($var_arr as $var){
				$config = str_replace('$'.$var, var_export($$var, TRUE), $config);
			}
			if (file_put_contents($config_file, $config) === FALSE){
				fJSON::output(array(
					'success' => FALSE,
					'config_file' => $config_file,
					'config' => $config,
				));
			} else {
				fJSON::output(array(
					'success' => TRUE,
					'config_file' => $config_file,
					'config' => $config,
				));
			}
			// TODO: Detect files in /uploads/ and either delete them or automatically add them to the new database (if it's new)
		}
	} catch (Exception $e) {
		fJSON::output(array('success'=>FALSE, 'error'=>$e->getMessage()));
	}
}

if (fRequest::isPost()) {
	if (fRequest::get('db_create', NULL, FALSE)){
		createConfiguration(TRUE);
	} else {
		createConfiguration();
	}
} else {
	include('form.php');
}
