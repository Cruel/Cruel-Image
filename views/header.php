<!DOCTYPE html>
<html lang="en">
<head>
	<title><?php echo $this->prepare('title') ?></title>
	<link rel="alternate" type="application/rss+xml" title="Cruel Image Latest Feed" href="rss.xml" />
	<meta charset="utf-8" />
	<meta name="description" content="This is CruelImage" />
	<link rel="shortcut icon" type="image/png" href="/static/favicon.png">

	<?php
		$this->add('js', URL_ROOT.'static/js/upload.js', TRUE);
		$this->add('js', URL_ROOT.'static/js/cruelimage.js', TRUE);
		$this->add('js', URL_ROOT.'static/js/jquery.poshytip.min.js', TRUE);
		$this->add('js', URL_ROOT.'static/js/jquery.Jcrop.min.js', TRUE);
		$this->add('js', URL_ROOT.'static/js/jquery.infinitescroll.min.js', TRUE);
		$this->add('js', URL_ROOT.'static/js/slimbox2.js', TRUE);
		$this->add('js', URL_ROOT.'static/js/canvas-to-blob.min.js', TRUE);
		$this->add('js', URL_ROOT.'static/js/jquery.blockUI.js', TRUE);
		$this->add('js', URL_ROOT.'static/js/jquery.masonry.min.js', TRUE);
		$this->add('js', URL_ROOT.'static/js/jquery.zclip.min.js', TRUE);
		$this->add('js', URL_ROOT.'static/js/jquery.fileupload.js', TRUE);
		$this->add('js', 'https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.18/jquery-ui.min.js', TRUE);
		$this->add('js', 'https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js', TRUE);
		
		$this->add('css', URL_ROOT.'static/themes/'.CI_THEME.'/style.css', TRUE);
		$this->add('css', URL_ROOT.'static/css/jquery-ui-1.8.21.css', TRUE);
		$this->add('css', URL_ROOT.'static/css/jquery.Jcrop.min.css', TRUE);
		$this->add('css', URL_ROOT.'static/css/slimbox2.css', TRUE);
		$this->add('css', URL_ROOT.'static/css/poshytip-twitter/tip-twitter.css', TRUE);
		$this->add('css', 'http://yui.yahooapis.com/3.6.0/build/cssreset/cssreset-min.css', TRUE);

		echo $this->place('css');
		echo $this->place('css_extra');
		echo $this->place('js');
		echo $this->place('js_extra');
		echo $this->place('rss');
	?>

</head>
<body>
<div id="wrap">
	<div id="top">
		<a href="<?php echo URL_ROOT ?>"><img src="<?php echo URL_ROOT.'static/themes/'.CI_THEME.'/img/logo.png' ?>" alt="<?php echo CI_TITLE ?>" /></a>
	</div>
	<div id="content">
