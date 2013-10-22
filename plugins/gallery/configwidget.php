<p>Images per page: <input id="gallery_imagecount" type="number" value="<?php echo $vars['imagecount']; ?>" />
<p>Thumb width: <input id="gallery_imagewidth" type="number" value="<?php echo $vars['imagewidth']; ?>" /></p>
<p>Spacing: <input id="gallery_spacing" type="number" value="<?php echo $vars['spacing']; ?>" /></p>
<p>
	Pagination style:
	<select id="gallery_pagination">
		<option value="numbered" <?php if ($vars['pagination']=="numbered") echo "selected"; ?>>Numbered</option>
		<option value="infinitescroll" <?php if ($vars['pagination']=="infinitescroll") echo "selected"; ?>>Infinite Scroll</option>
	</select>
</p>

<script type="text/javascript">
function plugin_gallery_save(){
	return {
		imagecount: $('#gallery_imagecount').val(),
		imagewidth: $('#gallery_imagewidth').val(),
		spacing: $('#gallery_spacing').val(),
		pagination: $('#gallery_pagination').val()
	};
}
</script>