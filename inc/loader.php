<?php

define('DOC_ROOT', realpath(dirname(__FILE__) . '/../'));
define('URL_ROOT', substr(DOC_ROOT, strlen(realpath($_SERVER['DOCUMENT_ROOT']))) . '/');

require(DOC_ROOT."/vendor/autoload.php");

spl_autoload_register(function ($class) {
	$file = DOC_ROOT . "/models/$class.php";
	if (file_exists($file))
		return require $file;
	throw new Exception('The class ' . $class_name . ' could not be loaded');
});