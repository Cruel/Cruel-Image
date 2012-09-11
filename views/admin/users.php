<?php

fAuthorization::requireAuthLevel('admin');

if (fRequest::isDelete()) {
	try {
		$user = new User('test');
		$user->delete();
	} catch (fValidationException $e) {
		echo $e->printMessage();
	}
	echo "Deleted user: test";
} elseif (fRequest::isPost()) {
	try {
		fRequest::validateCSRFToken(fRequest::get('request_token'));
		$user = new User();
		$user->setName(strtolower(fRequest::get('name')));
		$user->setPassword(fRequest::get('pass', 'string', NULL, TRUE));
		$user->setLevel(fRequest::get('level'));
		$user->store();
		echo "Created user: ".$user->getName();
	} catch (fExpectedException $e) {
		$e->printMessage();
	}
}

?>

<form method="POST">
	<input name="name" type="text" />
	<input name="pass" type="password" />
	<select name="level">
		<?php
			foreach(unserialize(AUTH_LEVELS) as $key => $val){
				echo "<option value=\"$key\">$key</option>";
			}
		?>
	</select>
	<input type="hidden" name="request_token" value="<?php echo fRequest::generateCSRFToken() ?>" />
	<button>Create</button>
</form>