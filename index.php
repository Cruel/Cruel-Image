<?php
require_once('inc/init.php');

$type = fRequest::get('action');

switch ($type){
	case "image":
	case "gallery":
	case "home":
	case "rss":
	case "404":
		require DOC_ROOT."/views/$type.php";
		break;
}