<?php
require_once('inc/init.php');

$page = fRequest::get('action');

$page_arr = array(
	'image',
	'gallery',
	'home',
	'rss',
	'404',
	'admin'
);

if (in_array($page, $page_arr)){
	require DOC_ROOT."/views/$page.php";
} else {
	require DOC_ROOT."/views/404.php";
}