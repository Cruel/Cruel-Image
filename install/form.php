<!DOCTYPE html>
<html lang="en">
<head>
	<title>Cruel Image Installation</title>
	<meta charset="utf-8" />
	<meta name="description" content="This is CruelImage" />
	<link rel="shortcut icon" type="image/png" href="static/favicon.png">
	<style type="text/css">
		<?php include('style.css') ?>
	</style>
</head>
<body>
	<h1>Cruel Image Installation</h1>
	<form action="<?php echo fURL::get() ?>" method="POST">
		<dl>
			<dt>Title</dt>
			<dd><input name="title" type="text" /></dd>

			<dt>Path</dt>
			<dd><input name="path" type="text" value="<?php echo fURL::get() ?>" /></dd>
		</dl>
	</form>
</body>
</html>
