<?php

class Plugins {
	public $data = array();
	public $config_filename;

	function __construct() {
		$this->config_filename = DOC_ROOT."/inc/plugin_config.php";
		if (include($this->config_filename))
			$this->data = $plugin_config;
	}

	function require_file($file) {
		foreach($this->enabled() as $plugin => $data) {
			if ($data['has_'.$file])
				require(DOC_ROOT."/plugins/$plugin/$file.php");
		}
	}

	function is_enabled($name) {
		return (isset($this->data[$name]) && $this->data[$name]['enabled']);
	}

	function enable($name) {
		if (!$this->is_enabled($name)) {
			// run install.sql
			$this->data[$name]['enabled'] = TRUE;
			$this->save_config();
		}
	}

	function disable($name) {
		if ($this->is_enabled($name)) {
			$this->data[$name]['enabled'] = FALSE;
			$this->save_config();
		}
	}

	function save_config() {
		file_put_contents($this->config_filename, '<?php $plugin_config='.var_export($this->data, TRUE).';');
	}

	function refresh_cache() {
		if ($handle = opendir(DOC_ROOT.'/plugins')) {
			while (false !== ($entry = readdir($handle))) {
				$path = DOC_ROOT."/plugins/$entry";
				if ($entry != "." && $entry != ".." && include("$path/config.php")) {
					if (!isset($this->data[$entry]))
						$this->data[$entry] = array(
							'enabled' => FALSE,
							'has_sql' => file_exists("$path/install.sql"),
							'has_page' => file_exists("$path/page.php"),
							'has_widget' => file_exists("$path/widget.php"),
							'has_preload' => file_exists("$path/preload.php"),
							'has_postload' => file_exists("$path/postload.php"),
							'config' => $config,
						);
				}
			}
			closedir($handle);
		}
		$this->save_config();
	}

	function enabled() {
		$ret = $this->data;
		foreach($this->data as $plugin => $data) {
			if (!$this->is_enabled($plugin))
				unset($ret[$plugin]);
		}
		return $ret;
	}
}

$plugins = new Plugins();

