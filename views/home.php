<?php

$template->set('title', CI_TITLE);
//$template->add('rss', array('path' => '/sup/rss/blog.rss', 'title' => 'Blog Posts'));
$template->place('header');
?>

	<div id="imagescroller">
		<div id="imageprogressbar"></div>
		<ul></ul>
		<div id="scrollercaption">Click or Drag Images Here</div>
	</div>

	<div id="uploadbuttons">
		<button id="btnAdd">+ Add Image</button>
		<button id="btnClear">Clear</button>
		<button id="btnUpload">Upload</button>
	</div>

	<form id="uploadform" action="/upload" method="POST" enctype="multipart/form-data">
<!--		<label for="title">Title</label>-->
<!--		<input id="title" type="text" name="title" placeholder="Optional" />-->

		<input type="hidden" id="token" value="<?php echo fRequest::generateCSRFToken() ?>" />
		<input id="files" type="file" name="files[]" multiple accept="image/*" />
	</form>

	<div id="results">	</div>

<?php
$template->place('footer');
