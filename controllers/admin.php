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

if (fRequest::isPost()){
	switch ($page){

		case "login":
			try {
				fRequest::validateCSRFToken(fRequest::get('token'));
				$user = new User(fRequest::get('name'));
				if ($user->checkPassword(fRequest::get('pass'))) {
					fAuthorization::setUserAuthLevel($user->getLevel());
//					fAuthorization::setUserToken($user->getName());
					fURL::redirect(fAuthorization::getRequestedURL(TRUE, URL_ROOT));
				} else {
					throw new fExpectedException('Incorrect password.');
				}
			} catch (fException $e) {
				$e->printMessage();
			}
			break;

		case "settings":
			// Save settings
			$settings = fRequest::get('settings');
//			$db = $this->get('db');
			foreach ($settings as $key => $value){
				$db->execute("UPDATE settings SET value = %s WHERE name = %s", $value, strtoupper($key));
				echo "Saved - $key:$value<br />";
			}
			break;

		case "images":
			$json = array('images'=>array());
			$image_arr = fRequest::get('delete');
			$images = fRecordSet::build('Image',
				array('id=' => $image_arr)
			);
			foreach ($images as $image){
				$id = $image->getId();
				try {
					$image->delete();
					$json['images'][$id] = array(
						'success' => TRUE,
					);
				} catch (fException $e) {
					$json['images'][$id] = array(
						'success' => FALSE,
						'message' => $e->getMessage(),
					);
				}
			}
			fJSON::output($json);
			break;

	}
} else {
	$template->set('title', 'Admin - '.CI_TITLE);
	$template->add('js_extra', URL_ROOT.'static/js/admin.js');
	$template->place('header');
	$template->inject('admin/header.php');
	$template->inject("admin/$page.php");
	$template->inject('admin/footer.php');
	$template->place('footer');
}