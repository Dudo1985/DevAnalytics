Dev Analytcs
==========

![Dev-Analytics-1](https://user-images.githubusercontent.com/5710734/85045011-ae8a6800-b18e-11ea-829d-d7d770e2aec2.png)

[![License: GPL v2](https://img.shields.io/badge/License-GPL%20v2-blue.svg)](https://www.gnu.org/licenses/old-licenses/gpl-2.0.en.html)

## What is Dev Analytics?
Dev Analytics is a WordPress plugin that integrate the 
[analytics embed API](https://developers.google.com/analytics/devguides/reporting/embed/v1) 
in your WordPress Dashboard.

### To whom it is addressed?
Developers, mostly.

### Installation
1) Install composer dependencies
2) [Create a client id](https://developers.google.com/analytics/devguides/reporting/embed/v1/getting-started#client-id) 
3) Create a service account and download the JSON key ([follow step 1 and 2](https://ga-dev-tools.appspot.com/embed-api/server-side-authorization/))
4) Insert the JSON key in /admin/analytics folder.
**Remember to disallow browser access to the file!!** (.htaccess provided)
5) Paste both your client id and json filename in the .env.example file (remember to rename it .env)
6) Enable [Analytics API](https://console.developers.google.com/apis/library/analytics.googleapis.com) for your project 

You're Set!!

##### Additional Info
External Libraries:
[daterangepicker](https://github.com/dangrossman/daterangepicker)