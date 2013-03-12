<?php

global $page;

if ($page != "image"){
	global $template;
	$template->add('body_extra', dirname(__FILE__).'/script.php');
}
