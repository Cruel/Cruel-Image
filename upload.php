<?php
require_once('inc/init.php');
fCore::enableDebugging(FALSE);

$json = array('success'=>TRUE, 'files'=>array(), 'errors'=>array());

function outputFail($messages){
	fJSON::output(array('success'=>FALSE, 'errors'=>$messages));
	die();
}

if (!fRequest::isPost()){
	outputFail(array("This is for uploading images. I'm not sure what you're trying to do..."));
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

$urlorig = fRequest::get('url');
if ($urlorig) {
	$url = filter_var($urlorig, FILTER_VALIDATE_URL);
	if ($url) {
		$tmpfile = tempnam(sys_get_temp_dir(), 'php');
		file_put_contents($tmpfile, curl_contents($url));
		$size = getimagesize($tmpfile);
		$_FILES = array("files"=>array(
			"name" => array($url),
			"type" => array(($size) ? $size['mime'] : 'image/jpeg'),
			"tmp_name" => array($tmpfile),
			"error" => array(0),
			"size" => array(filesize($tmpfile))
		));
	} else {
		outputFail(array("'$urlorig' is not a valid URL."));
	}
}

$uploader = new fUpload();
$uploader->setMaxSize(CI_MAX_FILESIZE);
$uploader->setMIMETypes(
	array(
		'image/gif',
		'image/jpeg',
		'image/pjpeg',
		'image/png'
	),
	'This file is not a valid image.'
);

$errors    = array();
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
		try {
			$file = $uploader->move(CI_UPLOAD_DIR, 'files', $i);
		} catch (fEnvironmentException $e) {
			if ($url) {
				$filename = CI_UPLOAD_DIR.fFilesystem::makeURLSafe($_FILES['files']['name'][$i]);
				$filename = fFilesystem::makeUniqueName($filename);
				if (!rename($_FILES['files']['tmp_name'][$i], $filename))
					throw $e;
				$file = fFilesystem::createObject($filename);
			} else {
				throw $e;
			}
		}
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
}

fJSON::output($json);