kayako-crowd-loginshare (KCLS)
=======================

Provides a [Kayako LoginShare API](http://wiki.kayako.com/display/DEV/LoginShare+For+Staff) endpoint to 
authenticate staff users against [Atlassian Crowd](http://www.atlassian.com/software/crowd/overview) 
using the [Crowd REST API](https://developer.atlassian.com/display/CROWDDEV/Crowd+REST+APIs).

# Configuration
KCLS is designed as a [12-Factor app](http://12factor.net/) and includes a procfile for easy deployment on compatible platforms such as Heroku or Deis. 
As such configuration is through environment variables and logging is to stdout.

You'll need to set the following configuration variables:
* CROWD_APPNAME - Crowd App Name (match setting in Crowd)
* CROWD_APPPASS - Crowd App Password (match setting in Crowd)
* CROWD_BASEURL - Crowd base URL, e.g. https://mycrowd.example.com/crowd/
* KAYAKO_FORCE_TEAM - Set this to the name of a team in Case to force authorised users to be part of that team.
* KAYAKO_ALLOWED_INTERFACES - set this to the interfaces that authenticated users are allowed to access, seperated by commas (and no spaces) if you want more than one. Valid interfaces are: staff, winapp, mobile, and staffapi.


