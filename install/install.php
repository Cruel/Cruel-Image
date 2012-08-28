<?php

//$template = new fTemplating(DOC_ROOT . '/install/');
//echo 'Installing...';

if (fRequest::isPost()){
	echo fRequest::get('title');
	define('IS_INSTALLED', TRUE);
} else {
	include('form.php');
	define('IS_INSTALLED', FALSE);
}