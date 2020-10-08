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

if (!class_exists('DevAnalytics_AdminPage')) {
    class DevAnalytics_AdminPage {

        public function action() {
            add_action('admin_menu', array($this, 'devanalyticsAddMenuPage'));
        }

        public function devanalyticsAddMenuPage() {
            //Add Settings Page
            add_menu_page(
                __('Analytics', 'devanalytics'), //Page Title
                __('DevAnalytics', 'devanalytics'), //Menu Title
                'manage_options', //capability
                'devanalytics_settings_page', //menu slug
                [
                    'DevAnalytics_AdminPage',
                    'devanalytics_analytics_page_callback',
                ],
                //The function to be called to output the content for this page.
                'dashicons-chart-line'
            );
        }

        // Settings Page Content
        public static function devanalytics_analytics_page_callback() {
            if (!current_user_can('manage_options')) {
                wp_die(
                    __(
                        'you don\'t have enough privileges to see this page.',
                        'devanalytics'
                    )
                );
            }
            include(DEVANALYTICS_ABSOLUTE_PATH
                . '/admin/analytics/devanalytics_analytics.php');
        } //End yasr_settings_page_content

    }

}