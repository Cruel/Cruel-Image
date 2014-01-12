<?php

$plugins->refresh_cache();
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

function renderPage($page, $opts = array()){
	global $template;
	$template->set('title', 'Admin - '.CI_TITLE);
	$template->set($opts);
	$template->add('js_extra', URL_ROOT.'static/js/admin.js');
	$template->place('header');
	$template->inject('admin/header.php');
	$template->inject("admin/$page.php");
	$template->inject('admin/footer.php');
	$template->place('footer');
}

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
				renderPage($page, array('error'=>$e->getMessage()));
			}
			break;

		case "users":
//			if (fRequest::isDelete()) {
//				try {
//					$user = new User('test');
//					$user->delete();
//				} catch (fValidationException $e) {
//					echo $e->printMessage();
//				}
//				echo "Deleted user: test";
//			}
			try {
				fRequest::validateCSRFToken(fRequest::get('request_token'));
				$user = new User();
				$user->setName(strtolower(fRequest::get('name')));
				$user->setPassword($user->hashedPassword(fRequest::get('pass', 'string', NULL, TRUE)));
				$user->setLevel(fRequest::get('level'));
				$user->store();
				renderPage($page, array('message'=>"Created user: ".$user->getName()));
			} catch (fExpectedException $e) {
				renderPage($page, array('message'=>$e->getMessage()));
			}
			break;

		case "settings":
			// TODO: Clear cache/min folder for when theme is changed?
			// Save settings
			$settings = fRequest::get('settings');
			$plugin_settings = json_decode(fRequest::get('plugins'), true);
			if ($settings) {
				
				foreach ($plugin_settings as $key => $value) {
					$plugins->data[$key]['config']['vars'] = $value;
				}
				$plugins->save_config();
				
				foreach ($settings as $key => $value){
					$db->execute("UPDATE settings SET value = %s WHERE name = %s", $value, strtoupper($key));
					echo "Saved - $key:$value<br />";
				}
				
			} else {
				$p = fRequest::get('plugin_enable');
				if ($p) $plugins->enable($p);
				$p = fRequest::get('plugin_disable');
				if ($p) $plugins->disable($p);
				renderPage($page);
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
	renderPage($page);
}
