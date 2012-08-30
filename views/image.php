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
	$file = new fFile(DOC_ROOT.'/uploads/thumb/'.$filename);
else
	$file = new fFile(DOC_ROOT.'/uploads/'.$filename);
header('Pragma: public');
header('Cache-Control: max-age=86400');
header('Expires: '. gmdate('D, d M Y H:i:s \G\M\T', time() + 86400));
header('Content-Length: ' . $file->getSize());
header('Content-Type: ' . $file->getMimeType());
header('Last-Modified: ' . $file->getMTime()->format('D, d M Y H:i:s'));
$file->output(FALSE);