<?php

fAuthorization::requireAuthLevel('admin');

$users = fRecordSet::build('User');

?>

<div id="message"><?php echo $this->get('message') ?></div>

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