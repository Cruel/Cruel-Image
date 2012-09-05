<?php

$template->set('title', 'Gallery - '.CI_TITLE);
$template->add('js_extra', URL_ROOT.'static/js/gallery.js');
$template->place('header');

$page = fRequest::get('page');
$time = fRequest::get('time', 'integer', time());

$images = fRecordSet::build('Image', array('time<'=>$time), array('time'=>'desc'), 25, $page);

?>

<div class="gallery">
	<?php
	foreach ($images as $image){
		$file = $image->getId().'.'.$image->getType();
	?>
		<a href="<?php echo URL_ROOT.$file ?>" rel="lightbox" title="<?php echo $image->encodeTitle() ?>">
			<img src="<?php echo URL_ROOT.'t/'.$file ?>" />
		</a>
	<?php } ?>
</div>
<div id="infinite-loading"></div>
<nav id="page-nav">
	<a href="<?php echo URL_ROOT.'gallery/'.($page + 1).'?time='.$time ?>"></a>
</nav>

<?php

$template->place('footer');