1) Install composer dependencies
2) [Create a client id](https://developers.google.com/analytics/devguides/reporting/embed/v1/getting-started#client-id) 
3) Paste your client id as value of `DEVANALYTICS_CLIENT_ID` constant in dev-analytics.php
4) Create a service account and download the JSON key ( [follow step 1 and 2](https://ga-dev-tools.appspot.com/embed-api/server-side-authorization/))
5) Insert the JSON key in /admin/analytics folder.
**Remember to disallow browser access to the file!!** (.htaccess provided)
6) Insert the JSON key filename as value of `DEVANALYTICS_JSON_KEY_FILENAME` constant in dev-analytics.php
7) Enable [Analytics API](https://console.developers.google.com/apis/library/analytics.googleapis.com) for your project 


You're Set!!