<?php

$page = fRequest::get('page', 'integer', 1);
$time = fRequest::get('time', 'integer', time());

$images = fRecordSet::build('Image', array('time<'=>$time), array('time'=>'desc'), 25, $page);
$count = $images->count(TRUE);

?>

<nav id="admin-gallery-nav">
	<?php
		for ($i = 1; $i <= ceil($count/25); $i++) {
			if ($i == $page) {
				echo $i;
			} else {
				echo '<a href="'.URL_ROOT."admin/images?page=$i\">$i</a>";
			}
		}
	?>
</nav>

<div class="admin-gallery">
	<?php
	foreach ($images as $image){
		$file = $image->getId().'.'.$image->getType();
		?>
			<img data-imageid="<?php echo $image->getId() ?>" data-imageurl="<?php echo URL_ROOT.$file ?>" src="<?php echo URL_ROOT.'t/'.$file ?>" />
		<?php } ?>
</div>
<div id="infinite-loading"></div>
<nav id="page-nav">
	<a href="<?php echo URL_ROOT.'admin/images?page='.($page + 1).'&time='.$time ?>"></a>
</nav>
