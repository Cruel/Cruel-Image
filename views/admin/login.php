<?php

if (fRequest::isPost()) {
	try {
		fRequest::validateCSRFToken(fRequest::get('token'));
		$user = new User(fRequest::get('name'));
		if ($user->checkPassword(fRequest::get('pass'))) {
			fAuthorization::setUserAuthLevel($user->getLevel());
//			fAuthorization::setUserToken($user->getName());
			fURL::redirect(fAuthorization::getRequestedURL(TRUE, URL_ROOT));
		} else {
			throw new fExpectedException('Incorrect password.');
		}
	} catch (fException $e) {
		$e->printMessage();
	}
}

?>

<form method="POST">
	<input name="name" type="text" placeholder="Username" />
	<input name="pass" type="password" placeholder="Password" />
	<input type="hidden" name="token" value="<?php echo fRequest::generateCSRFToken() ?>" />
	<button>Login</button>
</form>