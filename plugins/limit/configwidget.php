<p>
	<input name="limit_type" type="radio" value="count" <?php if ($vars['type'] == "count") echo "checked"; ?>/>
	Limit to <input id="limit_count" type="number" value="<?php echo $vars['count']; ?>" /> image uploads.
</p><p>
	<input name="limit_type" type="radio" value="time" <?php if ($vars['type'] == "time") echo "checked"; ?>/>
	Limit image age to <input id="limit_time" type="number" value="<?php echo $vars['time']; ?>" /> hours.
</p><p>
	<input name="limit_type" type="radio" value="space" <?php if ($vars['type'] == "space") echo "checked"; ?>/>
	Limit disk usage to <input id="limit_space" type="number" value="<?php echo $vars['space']; ?>" /> Mb.
</p>

<script type="text/javascript">
function plugin_limit_save(){
	return {
		type: $('input:radio[name=limit_type]:checked').val(),
		count: $('#limit_count').val(),
		time: $('#limit_time').val(),
		space: $('#limit_space').val()
	};
}
</script>