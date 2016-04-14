<?php 
if (file_exists(Pelican::$config["PLUGIN_ROOT"] . '/boforms/conf/local/BOForms.admin.ini.'.$_ENV["TYPE_ENVIRONNEMENT"].'.php')) {
	include_once(Pelican::$config["PLUGIN_ROOT"] . '/boforms/conf/local/BOForms.admin.ini.'.$_ENV["TYPE_ENVIRONNEMENT"].'.php');
}
