<?php

require_once("adminpannel/config.php") ;
require_once("adminpannel/inc/functions.php");
	
$website_url = "https://ahika.in/" ;
$desktop_page_insight = google_page_speed_insight($website_url,"desktop") ;

echo "<pre>";
print_r($desktop_page_insight) ;