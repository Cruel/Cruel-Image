<?php

global $page, $split_url;

function trackPage() {
	require_once "PiwikTracker.php";
	global $filename, $lastModified;
	$modified = (isset($_SERVER['HTTP_IF_MODIFIED_SINCE']) && $_SERVER['HTTP_IF_MODIFIED_SINCE'] == $lastModified);
	$t = new PiwikTracker( $idSite = 1, 'http://'.$_SERVER['SERVER_NAME'].'/piwik/');
	$t->setCustomVariable( 1, "cache_modified", $modified );
	//$t->setUrl( $url = '/'.fURL::get() );
	$t->doTrackPageView($filename);
}

if ($page == "image"){
	if ($split_url[count($split_url)-2] != 't')
		trackPage();
}