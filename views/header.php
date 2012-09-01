<!DOCTYPE html>
<html lang="en">
<head>
	<title><?php echo $this->prepare('title') ?></title>
	<link rel="alternate" type="application/rss+xml" title="Cruel Image Latest Feed" href="rss.xml" />
	<meta charset="utf-8" />
	<meta name="description" content="This is CruelImage" />
	<link rel="shortcut icon" type="image/png" href="/static/favicon.png">

	<?php
		$this->add('js', '/static/js/upload.js', TRUE);
		$this->add('js', '/static/js/cruelimage.js', TRUE);
		$this->add('js', '/static/js/jquery.poshytip.min.js', TRUE);
		$this->add('js', '/static/js/jquery.Jcrop.min.js', TRUE);
		$this->add('js', '/static/js/jquery.infinitescroll.min.js', TRUE);
		$this->add('js', '/static/js/slimbox2.js', TRUE);
		$this->add('js', '/static/js/jquery.blockUI.js', TRUE);
		$this->add('js', '/static/js/jquery.masonry.min.js', TRUE);
		$this->add('js', '/static/js/jquery.zclip.min.js', TRUE);
		$this->add('js', '/static/js/jquery.fileupload.js', TRUE);
		$this->add('js', 'https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.18/jquery-ui.min.js', TRUE);
		$this->add('js', 'https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js', TRUE);
		
		$this->add('css', '/static/themes/'.CI_THEME. '/style.css', TRUE);
		$this->add('css', '/static/css/jquery-ui-1.8.21.css', TRUE);
		$this->add('css', '/static/css/jquery.Jcrop.min.css', TRUE);
		$this->add('css', '/static/css/slimbox2.css', TRUE);
		$this->add('css', '/static/css/poshytip-twitter/tip-twitter.css', TRUE);
		$this->add('css', 'http://yui.yahooapis.com/3.6.0/build/cssreset/cssreset-min.css', TRUE);

		echo $this->place('css');
		echo $this->place('js');
		echo $this->place('rss');
	?>

</head>
<body>
<div id="wrap">
	<div id="top">
		<a href="<?php echo CI_BASEURL ?>"><img src="<?php echo '/static/themes/'.CI_THEME.'/img/logo.png' ?>" alt="<?php echo CI_TITLE ?>" /></a>
	</div>
	<div id="content">
