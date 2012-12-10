<div id="wrap">
	<div id="top">
		<a href="<?php echo URL_ROOT ?>"><img src="<?php echo URL_ROOT.'static/themes/'.CI_THEME.'/img/logo.png' ?>" alt="<?php echo CI_TITLE ?>" /></a>
		<?php if (fAuthorization::checkAuthLevel('moderator')): ?>
			<div id="menu">
				<ul>
					<li><a href="<?php echo URL_ROOT.'admin' ?>">Dashboard<span class="elbow-left"></span><span class="elbow-right"></span></a></li>
					<li><a href="<?php echo URL_ROOT.'admin/images' ?>">Images<span class="elbow-left"></span><span class="elbow-right"></span></a></li>

					<?php if (fAuthorization::checkAuthLevel('admin')): ?>
						<li><a href="<?php echo URL_ROOT.'admin/users' ?>">Users<span class="elbow-left"></span><span class="elbow-right"></span></a></li>
					<?php endif ?>

					<li><a href="<?php echo URL_ROOT.'admin/settings' ?>">Settings<span class="elbow-left"></span><span class="elbow-right"></span></a></li>
					<li><a href="<?php echo URL_ROOT.'admin/logout' ?>">Logout<span class="elbow-left"></span><span class="elbow-right"></span></a></li>
				</ul>
			</div>
		<?php endif ?>
	</div>
	<div id="content">