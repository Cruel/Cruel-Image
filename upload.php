<?php
require_once('inc/init.php');
fCore::enableDebugging(FALSE);

$json = array('success'=>TRUE, 'files'=>array());

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
		'image/png'
	),
	'The file uploaded is not an image'
);

$errors    = array();
$upload_count = fUpload::count('files');
for ($i=0; $i < $upload_count; $i++) {
	$error = $uploader->validate('files', $i, TRUE);
	if ($error)
		$errors[] = $error;
}
if (count($errors) > 0)
	outputFail($errors);
for ($i=0; $i < $upload_count; $i++) {
	$file = $uploader->move(CI_UPLOAD_DIR, 'files', $i);
	$image = new Image();
	$image->setTitle('');
	$image->storeFile($file);
	$json['files'][] = array(
		'id'	=> $image->getId(),
		'domain' => CI_DOMAIN,
		'thumb_url'	=> CI_BASEURL.'t/'.$image->getId().'.'.$image->getType(),
		'url' => CI_BASEURL.$image->getId().'.'.$image->getType(),
	);
}

fJSON::output($json);