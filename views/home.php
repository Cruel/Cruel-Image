<?php

$template->set('title', CI_TITLE);
//$template->add('rss', array('path' => '/sup/rss/blog.rss', 'title' => 'Blog Posts'));
$template->place('header');
?>

<!-- 	<form id="upload" action="">
		<div id="selector">
			<ul>
				<li><a id="select-local" class="active">Local</a></li>
				<li><a id="select-local">Local multi</a></li>
				<li><a id="select-remote">Remote</a></li>
			</ul>
		</div>
		<div id="upload-tools">
			<a id="preferences">Upload Preferences</a>
			<div id="upload-params">JPG PNG BMP GIF <span>MAX. 2 MB</span></div>
		</div>
		<div id="upload-container">
			<div id="preferences-box">
				<div><input type="checkbox" id="pref-shorturl" /> <label for="pref-shorturl">Create short URL's using TinyURL</label></div>
			</div>
			<div id="input-container">
				<div class="upload show_upload" id="upload-local">
					<h1>browse for images you would like to upload from your computer</h1>
					<div id="fileQueue"></div>
					<div><input style="display: none;" id="uploadify" name="uploadify" type="file" /></div>
				</div>
				<div class="upload hide_upload" id="upload-remote">
					<h1>enter the url of the image you would like to upload</h1>
					<div id="remote-parser"><input type="text" id="url" name="url" /><span id="add-url"></span></div>
					<div id="remoteQueue"></div>
				</div>
			</div>
			<div id="resizing">
				<div id="resizing-switch"><div><a><span>image resizing</span></a> automatically resize your image</div></div>
				<div id="resizing-box">
					<div id="resizing-it">
						<div id="resize-width">Desired width <span>in pixels</span></div>
						<input type="text" id="resize" name="resize"/><div id="resize-keep">*proportions kept</div>
					</div>
				</div>
			</div>
			<div id="upload-action"><a id="upload-button"><span>Upload</span></a><a id="cancel-upload">cancel</a></div>
		</div>
	</form> -->

	<div id="imagescroller">
		<div id="imageprogressbar"></div>
		<ul></ul>
	</div>

	<button id="btnAdd">Add Image</button>
	<button id="btnClear">Clear</button>
	<button id="btnUpload">Upload</button>

	<div id="results"></div>

	<form id="uploadform" action="/upload" method="POST" enctype="multipart/form-data">
		<fieldset>
			<legend>Image Upload</legend>
			<p>
				<label for="title">Title<em>*</em></label>
				<input id="title" type="text" name="title" placeholder="Image Title" />
			</p>
			<p>
				<label for="files">Image<em>*</em></label>
				<input id="files" type="file" name="files[]" multiple accept="image/*" />
			</p>
		</fieldset>
		<p>
			<input type="submit" value="Upload" />
			<span class="required"><em>*</em> Required field</span>
			<input type="hidden" id="token" value="<?php echo fRequest::generateCSRFToken() ?>" />
		</p>
	</form> 

<?php
$template->place('footer');
