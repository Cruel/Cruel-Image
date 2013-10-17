<?php global $plugins; ?>

<div id="wrap">
	<div id="top">
		<a href="<?php echo URL_ROOT ?>"><img src="<?php echo URL_ROOT.'static/themes/'.CI_THEME.'/img/logo.png' ?>" alt="<?php echo CI_TITLE ?>" /></a>
		<div id="menu">
			<ul>
				<li><a id="linkupload" href="<?php echo URL_ROOT ?>">Upload<span class="elbow-left"></span><span class="elbow-right"></span></a></li>
				<?php
					foreach($plugins->enabled() as $plugin => $data) {
						echo '<li><a id="link" href="'.URL_ROOT.$data['config']['page_url'].'">'.$data['config']['name'].'<span class="elbow-left"></span><span class="elbow-right"></span></a></li>';
					}
				?>
			</ul>
		</div>
	</div>
	<div id="content">