<?php

define('DOC_ROOT', realpath(dirname(__FILE__) . '/../'));
define('URL_ROOT', substr(DOC_ROOT, strlen(realpath($_SERVER['DOCUMENT_ROOT']))) . '/');

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