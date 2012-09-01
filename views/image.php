<?php

require_once('inc/loader.php');
require_once('inc/config.php');
fCore::enableDebugging(FALSE);

function output404(){
	$file = new fFile(DOC_ROOT . CI_IMAGE_404);
	$file->output(TRUE);
	die();
}

$split_url = explode('/', fURL::get());
list($uuid, $ext) = explode('.', $split_url[count($split_url)-1]);

try {
	$img = new Image($uuid);
} catch (fNotFoundException $e) {
	output404();
}

$filename = $img->getFileName();

if ($split_url[count($split_url)-2] == 't')
	$file = new fFile(CI_UPLOAD_DIR.'thumb/'.$filename);
else
	$file = new fFile(CI_UPLOAD_DIR.$filename);

$lastModified = $file->getMTime()->format('D, d M Y H:i:s');
header('Pragma: public');
header('Cache-Control: max-age='.CI_IMAGE_EXPIRATION);
header('Expires: '. gmdate('D, d M Y H:i:s \G\M\T', time() + CI_IMAGE_EXPIRATION));
header('Content-Length: ' . $file->getSize());
header('Content-Type: ' . $file->getMimeType());
if(isset($_SERVER['HTTP_IF_MODIFIED_SINCE']) && $_SERVER['HTTP_IF_MODIFIED_SINCE'] == $lastModified){
	// Issue HTTP 304 for unchanged images to speed up the response
	header('Last-Modified: '.$_SERVER['HTTP_IF_MODIFIED_SINCE'], true, 304);
	die();
}
header('Last-Modified: ' . $lastModified);
$file->output(FALSE);