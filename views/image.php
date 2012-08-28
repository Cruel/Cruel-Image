<?php

require_once('inc/init.php');
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
$file->output(TRUE);