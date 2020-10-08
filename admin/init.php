<?php
/*

Copyright 2020 Dario Curvino (email : d.curvino@tiscali.it)

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>
*/

if (!defined('ABSPATH')) {
    exit('You\'re not allowed to see this page');
} // Exit if accessed directly

if (!is_admin()) {
    return;
}

//admin absolute path
define(
    'DEVANALYTICS_ABSOLUTE_PATH_ADMIN', DEVANALYTICS_ABSOLUTE_PATH . '/admin'
);
define(
    'DEVANALYTICS_RELATIVE_PATH_ADMIN', DEVANALYTICS_RELATIVE_PATH . '/admin'
);


//e.g. http://localhost/plugin_development/wp-content/plugins/yet-another-stars-rating/admin/js/
define(
    'DEVANALYTICS_JS_DIR_ADMIN',
    plugins_url() . '/' . DEVANALYTICS_RELATIVE_PATH_ADMIN . '/js/'
);
//CSS directory absolute URL
define(
    'DEVANALYTICS_CSS_DIR_ADMIN',
    plugins_url() . '/' . DEVANALYTICS_RELATIVE_PATH_ADMIN . '/css/'
);

add_action('admin_enqueue_scripts', 'devanalytics_add_admin_scripts');

//$hook contain the current page in the admin side
function devanalytics_add_admin_scripts($hook) {

    if ($hook === 'toplevel_page_devanalytics_settings_page') {

        wp_enqueue_style(
            'devanalytics-css',
            DEVANALYTICS_CSS_DIR_ADMIN . 'devanalytics-admin.css',
            false,
            DEVANALYTICS_VERSION_NUM,
            'all'
        );

        wp_enqueue_style(
            'daterangepicker-css',
            'https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css',
            false,
            DEVANALYTICS_VERSION_NUM,
            'all'
        );

        wp_enqueue_script(
            'moment',
            'https://cdn.jsdelivr.net/momentjs/latest/moment.min.js',
            'jquery',
            DEVANALYTICS_VERSION_NUM,
            true
        );

        wp_enqueue_script(
            'daterangepicker',
            'https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js',
            ['jquery', 'moment'],
            DEVANALYTICS_VERSION_NUM,
            true
        );

    }

}

/**
 * Callback function for the spl_autoload_register above.
 *
 * @param $class
 */
function devanalytics_autoload_admin_classes($class) {
    /**
     * If the class being requested does not start with 'devanalytics_' prefix,
     * it's not in devanalytics_ Project
     */
    if (0 !== strpos($class, 'DevAnalytics_')) {
        return;
    }
    $file_name = DEVANALYTICS_ABSOLUTE_PATH_ADMIN . '/classes/' . $class
        . '.php';

    // check if file exists, just to be sure
    if (file_exists($file_name)) {
        require($file_name);
    }
}

//AutoLoad Yasr Shortcode Classes, only when a object is created
spl_autoload_register('devanalytics_autoload_admin_classes');

$dev_analytics = new DevAnalytics_AdminPage();
$dev_analytics->action();