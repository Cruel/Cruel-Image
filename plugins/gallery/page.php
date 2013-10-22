<?php

$template->set('title', 'Gallery - '.CI_TITLE);
$template->add('js_extra', URL_ROOT.'static/js/gallery.js');
$template->add('css_extra', URL_ROOT.'static/css/gallery.css');
$template->place('header');
$template->inject('home/header.php');

$page = fRequest::get('p', 'integer', 1);
$time = fRequest::get('t', 'integer', time());

$vars = $plugins->data['gallery']['config']['vars'];

$images = fRecordSet::build('Image', array('time<'=>$time), array('time'=>'desc'), $vars['imagecount'], $page);

if ($vars['pagination'] == "numbered") {
	$pagination = new fPagination($images);
	$pagination->set('time', $time);
	fPagination::defineTemplate("gallery", "with_first_last", 4, array(
		'info'          => '<div class="paginator_info">Page {{ page }} of {{ total_records }} items</div>',
		'start'         => '<div class="paginator_list"><ul>',
		'prev'          => '<li class="page prev"><a href="?t={{ time }}&p={{ page }}">Prev</a></li>',
		'prev_disabled' => '<li class="page prev">Prev</li>',
		'page'          => '<li class="page {{ first }} {{ last }} {{ current }}"><a href="?t={{ time }}&p={{ page }}">{{ page }}</a></li>',
		'separator'     => '&hellip;',
		'next'          => '<li class="page next"><a href="?t={{ time }}&p={{ page }}">Next</a></li>',
		'next_disabled' => '<li class="page next">Next</li>',
		'end'           => '</ul></div>'
	));
}

?>

<style>
.gallery img {
	max-width: <?php echo $vars['imagewidth'] ?>px;
	margin-bottom: <?php echo $vars['spacing'] ?>px;
}
</style>

<?php if ($vars['pagination'] == "numbered") $pagination->showLinks("gallery"); ?>

<div class="gallery" data-imagewidth="<?php echo $vars['imagewidth'] ?>" data-spacing="<?php echo $vars['spacing'] ?>">
	<?php
	foreach ($images as $image){
		$file = $image->getId().'.'.$image->getType();
		echo '<a href="'.URL_ROOT.$file.'" rel="lightbox" title="'.$image->encodeTitle().'">';
		echo '<img src="'.URL_ROOT.'t/'.$file.'" />';
		echo '</a>';
	}
	?>
</div>

<?php if ($vars['pagination'] == "numbered") $pagination->showLinks("gallery"); ?>

<?php if ($vars['pagination'] == "infinitescroll"): ?>
	<div id="infinite-loading"></div>
	<nav id="page-nav">
		<a href="<?php echo URL_ROOT.'gallery?p='.($page + 1).'&t='.$time ?>"></a>
	</nav>
<?php endif; ?>

<?php
$template->inject('home/footer.php');
$template->place('footer');
