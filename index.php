<?php
require_once('inc/init.php');

$page = fRequest::get('action');
$plugin = fRequest::get('plugin');

$page_arr = array(
	'upload',
	'image',
	'home',
	'rss',
	'404',
	'admin'
);

if (in_array($page, $page_arr)){
	$plugins->require_file('preload');
	require(DOC_ROOT."/controllers/$page.php");
	$plugins->require_file('postload');
} else if ($page == "plugin") {
	$p = fRequest::get('plugin');
	foreach($plugins->enabled() as $plugin => $data) {
		if ($p == $data['config']['page_url']) {
			require(DOC_ROOT."/plugins/$plugin/page.php");
			die();
		}
	}
	require DOC_ROOT."/views/404.php";
} else {
	require DOC_ROOT."/views/404.php";
}