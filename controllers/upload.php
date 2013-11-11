<?php

fCore::enableDebugging(FALSE);

$jpeg_quality = 80;
$valid_mime = array(
	'image/gif',
	'image/jpeg',
	'image/pjpeg',
	'image/svg+xml',
	'image/png'
);

$json = array('success'=>TRUE, 'files'=>array(), 'errors'=>array());

function outputFail($messages=array("Failed to upload image.")){
	fJSON::output(array('success'=>FALSE, 'errors'=>$messages));
	die();
}

if (!fRequest::isPost()){
	outputFail(array("This is for uploading images. I'm not sure what you're trying to do..."));
}

function checkMime($filename){
	$finfo = new finfo(FILEINFO_MIME_TYPE);
	return $finfo->file($filename);
}

function addImage($file){
	global $json;
	$image = new Image();
	$image->setTitle('');
	$image->storeFile($file);
	$json['files'][] = array(
		'id'	=> $image->getId(),
		'domain' => fURL::getDomain(),
		'thumb_url'	=> URL_ROOT.'t/'.$image->getId().'.'.$image->getType(),
		'url' => URL_ROOT.$image->getId().'.'.$image->getType(),
	);
}

function curl_contents($url) {
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); //Set curl to return the data instead of printing it to the browser.
	curl_setopt($ch, CURLOPT_URL, $url);
	$data = curl_exec($ch);
	curl_close($ch);
	return $data;
}

function get_headers_curl($url){
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_HEADER, 1);
	curl_setopt($ch, CURLOPT_NOBODY, 1);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_exec($ch);
	return curl_getinfo($ch);
}

$urlorig = fRequest::get('url');
if ($urlorig) {
	$url = filter_var($urlorig, FILTER_VALIDATE_URL);
	if ($url) {
		$headers = get_headers_curl($url);
		if (!in_array($headers['content_type'], $valid_mime))
			outputFail(array("'$urlorig' is not a valid image."));
		if ($headers['download_content_length'] > 2000000)
			outputFail(array("'$urlorig' exceeds maximum filesize."));
		$tmpfile = tempnam(sys_get_temp_dir(), 'img');
		file_put_contents($tmpfile, curl_contents($url));
		$type = checkMime($tmpfile);
		if (!in_array($type, $valid_mime))
			outputFail(array("'$urlorig' is not a valid image."));
		$filename = CI_UPLOAD_DIR.fFilesystem::makeURLSafe($url);
		$filename = fFilesystem::makeUniqueName($filename);
		if ($type == "image/jpeg"){
            // TODO: use flourish image functions instead of GD-specific functions
			$im = imagecreatefromjpeg($tmpfile);
			if (!$im || !imagejpeg($im, $filename, $jpeg_quality)) outputFail();
		} else {
			if (!rename($tmpfile, $filename)) outputFail();
		}
		addImage(fFilesystem::createObject($filename));
	} else {
		outputFail(array("'$urlorig' is not a valid URL."));
	}
} else {
	$uploader = new fUpload();
    try {
        $uploader->setMaxSize(CI_MAX_FILESIZE);
    } catch (Exception $e) {
        outputFail(array($e->getMessage()));
    }
	$errors = array();
	$upload_count = fUpload::count('files');
	for ($i=0; $i < $upload_count; $i++) {
		$error = $uploader->validate('files', $i, TRUE);
		if ($error)
			$errors[$i] = $error;
	}
	if (count($errors) == $upload_count)
		outputFail($errors);
	for ($i=0; $i < $upload_count; $i++) {
		if (isset($errors[$i])){
			$json['errors'][] = $_FILES['files']['name'][$i].' - '.$errors[$i];
		} else {
			$type = checkMime($_FILES['files']['tmp_name'][$i]);
			if (!in_array($type, $valid_mime))
				outputFail(array($_FILES['files']['name'][$i]." (".$type.") is not a valid image."));
			$file = $uploader->move(CI_UPLOAD_DIR, 'files', $i);
			if ($type=="image/jpeg") {
				if (!($file instanceof fImage)){
					$filename = $file->getPath();
					$im = imagecreatefromjpeg($filename);
					if (!$im || !imagejpeg($im, $filename, $jpeg_quality)) outputFail();
					$file = new fImage($filename, TRUE);
				} else {
					// TODO: optimize jpeg with jpegtran
					$file->saveChanges('jpg', $jpeg_quality, TRUE);
				}
			}
			addImage($file);
		}
	}
}

fJSON::output($json);
