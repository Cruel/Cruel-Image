
<h3>Dashboard</h3>

<?php

	global $plugins;
	foreach($plugins->enabled() as $plugin => $data) {
		if ($data['has_widget']) {
			include(DOC_ROOT."/plugins/$plugin/widget.php");
		}
	}

?>