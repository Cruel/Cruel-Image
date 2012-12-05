<!DOCTYPE html>
<html lang="en">
<head>
	<title>Cruel Image Installation</title>
	<meta charset="utf-8" />
	<meta name="description" content="This is CruelImage" />
	<link rel="shortcut icon" type="image/png" href="static/favicon.png">
	<?php
	$template->add('js', 'https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js');
	$template->add('js', 'https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.18/jquery-ui.min.js');
	$template->add('js', 'static/js/jquery.poshytip.min.js');
	$template->add('js', 'static/js/slimbox2.js');
//	$template->add('js', 'static/js/cruelimage.js');

//	$template->add('css', 'http://yui.yahooapis.com/3.6.0/build/cssreset/cssreset-min.css');
//	$template->add('css', 'static/themes/'.CI_THEME.'/style.css');
	$template->add('css', 'static/css/jquery-ui-1.8.21.css');
	$template->add('css', 'static/css/slimbox2.css');
	$template->add('css', 'static/css/poshytip-twitter/tip-twitter.css');


	echo $template->place('css');
	echo $template->place('js');
	?>

	<style type="text/css">
		<?php include('style.css') ?>
	</style>
</head>
<body>
	<h1>Cruel Image Installation</h1>
	<form action="<?php echo fURL::get() ?>" method="POST">
		<input name="domain" type="hidden" value="<?php echo fURL::getDomain() ?>" />
		<dl>

			<?php
				$writable_dirs = array(
					'Upload Folder'=>'/uploads/',
					'Thumbnail Folder'=>'/uploads/thumb/',
					'Cache Folder'=>'/static/cache/',
					'Config File'=>'/inc/',
				);
				$required_exts = array('gd','iconv','exif','curl');
				$exts = get_loaded_extensions();
				$php_ini_filename = php_ini_loaded_file();
				$prelim_pass = TRUE;
			?>
			<fieldset id="preliminary">
				<legend>Preliminary Installation Check</legend>
<!--                <dt class="passed">Upload Folder Permissions</dt><dd>OK</dd>-->
				<noscript>
					<dt class="failed">Javascript Check</dt>
					<dd>You need to enable javascript for installation</dd>
				</noscript>
				<?php
					foreach($writable_dirs as $name => $dir) {
						if (is_writable(DOC_ROOT.$dir)){
							echo "<dt class=\"passed\">$name Permissions</dt><dd>OK</dd>";
						} else {
							$prelim_pass = FALSE;
							echo "<dt class=\"failed\">$name Permissions</dt>";
							echo "<dd>No write permissions for <strong>".DOC_ROOT."$dir</strong></dd>";
						}
					}
					foreach($required_exts as $ext) {
						if (in_array($ext, $exts)){
							echo "<dt class=\"passed\">PHP Extension: $ext</dt><dd>OK</dd>";
						} else {
							$prelim_pass = FALSE;
							echo "<dt class=\"failed\">PHP Extension: $ext</dt>";
							echo "<dd>You must install <strong>$ext</strong> and enable it in $php_ini_filename</dd>";
						}
					}
					if ($prelim_pass){
						echo '<button id="btnBegin">Begin Install</button>';
					} else {
						echo '<button class="btnRefresh">Retry</button>';
					}
				?>
			</fieldset>

			<fieldset id="db_fields">
				<legend>Database</legend>
				<dt>Type</dt>
				<dd>
					<select name="db_type" disabled>
						<option value="mysql">MySQL</option>
					</select>
				</dd>
				<dt>Database</dt>
				<dd><input name="db_name" type="text" /></dd>
				<dt>Username</dt>
				<dd><input name="db_user" type="text" /></dd>
				<dt>Password</dt>
				<dd><input name="db_pass" type="password" /></dd>
				<dt>Host</dt>
				<dd><input name="db_host" type="text" /> Leave blank for default host</dd>
				<dt>Port</dt>
				<dd><input name="db_port" type="number" /> Leave blank for default port</dd>
				<button id="btnCreateDatabase">Create Database</button>
				<div id="db_error"></div>
			</fieldset>

			<fieldset id="config_fields">
				<legend>Site Configuration</legend>
				<dt>Title</dt>
				<dd><input name="title" type="text" placeholder="Cruel Image Hosting" /></dd>
				<dt>Admin Username</dt>
				<dd><input name="admin_name" type="text" /></dd>
				<dt>Admin Password</dt>
				<dd><input id="adminpass1" name="admin_pass" type="password" /></dd>
				<dt>Retype Password</dt>
				<dd><input id="adminpass2" type="password" /></dd>
				<button id="btnInstall">Install</button>
				<div id="install_error"></div>
			</fieldset>
		</dl>

		<div id="install_message">
			<h3>Successfully Installed!</h3>
			<p>Your configuration file has been created at <em id="config_file"></em>.</p>
			<textarea></textarea>
			<p>To recreate the configuration file using this installer, just delete it and navigate to this page again.</p>
			<p>However it is recommended that you now <strong>delete or rename</strong> your <em>/install/</em> folder for security, in the case your configuration is accidentally deleted.</p>
			<p>To view your new site, simply <button class="btnRefresh">refresh</button></p>
		</div>
	</form>
	<script>
		<?php include('install.js') ?>
	</script>
</body>
</html>
