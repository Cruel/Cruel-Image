<div id="error"><?php echo $this->get('error') ?></div>
<form method="POST">
	<input name="name" type="text" placeholder="Username" />
	<input name="pass" type="password" placeholder="Password" />
	<input type="hidden" name="token" value="<?php echo fRequest::generateCSRFToken() ?>" />
	<button>Login</button>
</form>