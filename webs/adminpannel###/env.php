<?php

session_start();
session_regenerate_id();
ob_start();

ini_set( "session.gc_maxlifetime", 36000 );
//Set the cookie lifetime of the session
ini_set( "session.cookie_lifetime", 36000 ); 

error_reporting(E_ALL); error_reporting(-1); ini_set('error_reporting', E_ALL);

define("HOST_URL", "https://websitespeedy.com/");
define("HOST_HELP_URL", "https://help.websitespeedy.com/");
define("SMTP_USER", "audit@ecommerceseotools.com");
define("SMTP_PASSWARD", "fvfnuvesvucwgkdr");
define("SITE_RESTRICTION", "shopifyspeedy.com") ;
define("PAGE_INSIDE_KEY", "AIzaSyDw2nckjNQeVLGw_BxcfIvLTw3NYONCuRE");
define("RECAPTCHA_SITE_KEY", "6Leoz1gkAAAAAH_zR0uTCDhMlnWFnzGXFPWqvRXR");

/** wordpressspeedy **/ 
// define("RECAPTCHA_SITE_KEY", "6Le_YoEpAAAAAIZNaaEeJJpyDLKe602QD9LiN4rJ");
// define("RECAPTCHA_SECRET_KEY", "6Le_YoEpAAAAAN-5WTunMj9jsw9Di-bbufK2IbLs");