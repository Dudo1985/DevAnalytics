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

if ( ! defined('ABSPATH') ) {
    exit('You\'re not allowed to see this page');
} // Exit if accessed directly

//autload composer
require_once DEVANALYTICS_ABSOLUTE_PATH . '/vendor/autoload.php';

//load .env file
$dotenv = Dotenv\Dotenv::createImmutable(DEVANALYTICS_ABSOLUTE_PATH);
$dotenv->load();

$client = new Google_Client();
$client->setAuthConfig(__DIR__ .'/'. $_ENV['JSON_KEY_FILENAME']);
$client->addScope('https://www.googleapis.com/auth/analytics.readonly');
$client->useApplicationDefaultCredentials();
$client->fetchAccessTokenWithAssertion();
$accesstoken = $client->getAccessToken();
$accesstoken = $accesstoken['access_token'];

?>

<!--Load the library. -->
<script>
    (function(w,d,s,g,js,fs){
        g=w.gapi||(w.gapi={});g.analytics={q:[],ready:function(f){this.q.push(f);}};
        js=d.createElement(s);fs=d.getElementsByTagName(s)[0];
        js.src='https://apis.google.com/js/platform.js';
        fs.parentNode.insertBefore(js,fs);js.onload=function(){g.load('analytics');};
    }(window,document,'script'));
</script>

<h1><?php echo 'DevAnalytics'; ?></h1>

<!-- Creating the containing elements. -->
<div class="devanalytics-container-settings">
    <div id="embed-api-auth-container"></div>
    <div id="view-selector"></div>


    <h2><?php _e('Date Range', 'devanalytics');?></h2>
    <div id="reportrange">
        <i class="dashicons dashicons-calendar-alt"></i>&nbsp;
        <span></span> <i class="dashicons dashicons-arrow-down"></i>
    </div>

    <div class="devanalytics-container-center">
        <div><div class="devanalytics-container-title">
            <h2><?php _e('Users', 'devanalytics');?></h2>
        </div>
        <div id="unique-visitors-columns"></div>
    </div>

    <div class="devanalytics-flex-row">
        <div>
            <h3><?php _e('POST/PAGES', 'devanalytics');?></h3>
            <div id="top-pages"></div>
        </div>
        <div>
            <h3><?php _e('REFERALL TRAFFIC', 'devanalytics');?></h3>
            <div id="referral"></div>
        </div>
        <div>
            <h3><?php _e('ALL USERS', 'devanalytics');?></h3>
            <div id="unique-visitors"></div>
        </div>
    </div>

</div>

<script>

    jQuery(function() {
        var start = moment().subtract(7, 'days');
        var end = moment().subtract(1, 'days');

        function cb(start, end) {
            jQuery('#reportrange span').html(start.format('D MMMM, YYYY') + ' - ' + end.format('D MMMM, YYYY'));
        }

        jQuery('#reportrange').daterangepicker({
            startDate: start,
            endDate: end,
            "timePickerSeconds": false,
            "locale": {
                format: 'D MMMM, YYYY'
            },
            ranges: {
                'Today': [moment(), moment()],
                'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                'This Month': [moment().startOf('month'), moment().endOf('month')],
                'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
            }
        }, cb);

        cb(start, end);
    });

    gapi.analytics.ready(function() {

        const clientID = '<?php echo json_encode($_ENV['CLIENT_ID']); ?>'

        /**
         * Authorize the user immediately if the user has already granted access.
         * If no access has been created, render an authorize button inside the
         * element with the ID "embed-api-auth-container".
         */
        gapi.analytics.auth.authorize({
            container: 'embed-api-auth-container',
            clientid: clientID,
            serverAuth: {
                access_token: '<?php echo json_encode($accesstoken); ?>'
            }
        });

        var viewSelector = new gapi.analytics.ViewSelector({
            container: 'view-selector'
        });


        viewSelector.execute();

        /**
         * Create a new DataChart with active users.
         */
        var usersLines = new gapi.analytics.googleCharts.DataChart({
            query: {
                metrics:    'ga:users',
                dimensions: 'ga:date',
            },
            chart: {
                container: 'unique-visitors-columns',
                type:      'LINE',
                options: {
                    width: '80%'
                }
            }
        });


        /**
         * Create a new DataChart instance with the given query parameters
         * and Google chart options. It will be rendered inside an element
         * with the id "chart-container".
         */
        var pages = new gapi.analytics.googleCharts.DataChart({
            query: {
                dimensions:    'ga:pageTitle, ga:sourceMedium',
                metrics:       'ga:users',
                sort:          '-ga:users',
                'max-results': '15'
            },
            chart: {
                container: 'top-pages',
                type:      'TABLE',
                options: {
                    width: '100%'
                }
            }
        });


        /**
         * Create a new DataChart with active users.
         */
        var referall = new gapi.analytics.googleCharts.DataChart({
            query: {
                metrics:       'ga:users',
                dimensions:    'ga:source',
                sort:          '-ga:users',
                'max-results': '15'
            },
            chart: {
                container: 'referral',
                type:      'TABLE',
                options: {
                    width: '100%'
                }
            }
        });


        /**
         * Create a new DataChart with active users.
         */
        var users = new gapi.analytics.googleCharts.DataChart({
            query: {
                metrics:    'ga:users',
                sort:       '-ga:users',
            },
            chart: {
                container: 'unique-visitors',
                type:      'TABLE',
                options: {
                    width: '100%'
                }
            }
        });

        /**
         * Query params representing the first chart's date range.
         */
        var dateRange = {
            'start-date': '7daysAgo',
            'end-date': 'yesterday'
        };

        //On first load, newParam is dateRange
        executeGoogleChart(dateRange);

        //When date is changed
        jQuery('#reportrange').on('apply.daterangepicker', function(ev, picker) {
            var startDate = picker.startDate.format('YYYY-MM-DD');
            var endDate =   picker.endDate.format('YYYY-MM-DD');

            var dateRange = {
                'start-date': startDate,
                'end-date': endDate
            };
            executeGoogleChart(dateRange)
        });

        //On selecter change, pass the new id
        viewSelector.on('change', function(ids) {
            var newIds = {
                ids: ids
            }
            executeGoogleChart(newIds)
        });



        function executeGoogleChart(newParam) {
            usersLines.set({query: newParam});
            pages.set({query: newParam});
            referall.set({query: newParam});
            users.set({query: newParam});

            usersLines.execute();
            pages.execute();
            referall.execute();
            users.execute();
        }

    });
</script>

