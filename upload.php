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
		$file = $uploader->move(CI_UPLOAD_DIR, 'files', $i);
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