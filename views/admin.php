<?php

$authlevels = array(
	'superadmin' => 10,
	'admin'  => 9,
	'moderator' => 8,
	'guest' => 1
);
define("AUTH_LEVELS", serialize($authlevels));

fAuthorization::setLoginPage(URL_ROOT.'admin/login');
fAuthorization::setAuthLevels($authlevels);

$page = fRequest::get('p', 'string', 'dash');
if ($page != 'login')
	fAuthorization::requireAuthLevel('moderator');

$template->place('header');
$template->inject('admin/header.php');
$template->inject("admin/$page.php");
$template->inject('admin/footer.php');
$template->place('footer');