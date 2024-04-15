<?php

include('config.php');
require_once('inc/functions.php') ;

echo "<pre>";

$kk = microtime() ;
echo "kk : ".$kk."<hr>" ;

$data = google_page_speed_insight("https://www.paisleyandgray.com/","desktop") ;

print_r($data) ;
echo "<hr>" ;

$kk = microtime() ;
echo "kk : ".$kk."<hr>" ;
