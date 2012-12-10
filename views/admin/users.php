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

$users = fRecordSet::build('User');

?>

<form method="POST">
	<input name="name" type="text" placeholder="Username" />
	<input name="pass" type="password" placeholder="Password" />
	<select name="level">
		<?php
			foreach(unserialize(AUTH_LEVELS) as $key => $val){
				echo "<option value=\"$key\">$key</option>";
			}
		?>
	</select>
	<input type="hidden" name="request_token" value="<?php echo fRequest::generateCSRFToken() ?>" />
	<button>Add User</button>
</form>

<table>
	<thead>
		<tr>
			<td>Username</td>
			<td>Access Level</td>
			<td>Actions</td>
		</tr>
	</thead>
	<tbody>
		<?php
			foreach ($users as $user){
				echo '<tr><td>'.$user->getName().'</td><td>'.$user->getLevel().'</td><td>Delete</td></tr>';
			}
		?>
	</tbody>
</table>