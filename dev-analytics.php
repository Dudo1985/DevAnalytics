<?php
/*
Plugin Name: Dev-analytics
Plugin URI: https://dariocurvino.it
Description: Reporting API analytics in WordPress dashboard
Version: 1.0
Author: Dario Curvino
Author URI: https://dariocurvino.it
License: GPL2
*/

if ( ! defined( 'ABSPATH' ) ) {
    exit( 'You\'re not allowed to see this page' );
} // Exit if accessed directly

//DEFINE
define("DEVANALYTICS_ABSOLUTE_PATH", __DIR__);

//Plugin RELATIVE PATH without slashes (just the directory's name)
define("DEVANALYTICS_RELATIVE_PATH", dirname(plugin_basename(__FILE__)));

//CSS directory absolute
define("DEVANALYTICS_CSS_DIR", plugins_url() . '/' . DEVANALYTICS_RELATIVE_PATH . '/css/');

define('DEVANALYTICS_VERSION_NUM', '1.0.0');

//require
require DEVANALYTICS_ABSOLUTE_PATH . '/admin/init.php';