<?php

global $plugins;
$themes = array();
// Fill $themes array
if ($handle = opendir(DOC_ROOT.'/static/themes')) {
	while (false !== ($entry = readdir($handle))) {
		if ($entry != "." && $entry != ".." && is_file(DOC_ROOT."/static/themes/$entry/style.css")) {
			$themes[] = $entry;
		}
	}
	closedir($handle);
}

?>
<h1>Settings</h1>
<section id="settings">

	<div>
		<h3>Site Title</h3>
		<input id="title" type="text" value="<?php echo CI_TITLE ?>" />
	</div>

	<div>
		<h3>Look &amp; Feel</h3>
		<div>
			Theme
			<select id="theme">
			<?php
				foreach ($themes as $theme){
					echo "<option".(($theme == CI_THEME)?' selected':'').">$theme</option>";
				}
			?>
			</select>
		</div>
		<div>
			Show admin tab
			<input id="admintab" type="checkbox" <?php if (CI_ADMINTAB) echo "checked" ?>/>
		</div>
	</div>

	<div>
		<h3>API Key</h3>
		<input id="apikey" type="text" value="<?php echo CI_APIKEY ?>" />
	</div>

	<h3>Watermark Image</h3>

	<h3>Hotlinking</h3>

	<h3>Upload Limits</h3>

	<h3>Bandwidth Caps</h3>

	<h3>RSS Feed</h3>

	<h3>Social Networking</h3>

	<div>
		<h3>Plugins</h3>
		<form method="post">
			<ul id="plugins">
				<?php
				foreach ($plugins->data as $plugin => $data) {
					if ($plugins->is_enabled($plugin)) {
						echo "<li><button name=\"plugin_disable\" value=\"$plugin\">Disable</button> {$data['config']['name']}</li>";
					} else {
						echo "<li><button name=\"plugin_enable\" value=\"$plugin\">Enable</button> {$data['config']['name']}</li>";
					}
				}
				?>
			</ul>
		</form>
	</div>

	<div id="pluginsettings">
	<?php
		foreach($plugins->data as $plugin => $data) {
			if ($plugins->is_enabled($plugin)) {
				
				if (isset($data['config']['vars'])) {
					$vars = $data['config']['vars'];
					echo '<div class="plugin" data-plugin="'.$plugin.'">';
					echo "<h3>Plugin &dash; {$data['config']['name']}</h3>";
					include(DOC_ROOT."/plugins/$plugin/configwidget.php");
					echo "</div>";
				}
				
			}
		}
	?>
	</div>

</section>

<button id="btnSettingsSave">Save</button>