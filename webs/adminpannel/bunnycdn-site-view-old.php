<?php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
ini_set('memory_limit', '-1');
error_reporting(E_ALL);

ini_set('max_execution_time', '30000');

require_once "config.php" ;
 
// $log_date = "07-17-23" ;
$log_date = date("m-d-y") ;
echo "log_date : ".$log_date."<hr>" ;

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'https://logging.bunnycdn.com/'.$log_date.'/1394947.log');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 0);
curl_setopt($ch, CURLOPT_FAILONERROR, 0);

curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    "AccessKey: af7051be-9560-4951-8043-1015c55bc095e3dde32b-4cf9-4688-8b75-f973271c7476",
));

curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
$output = curl_exec($ch);
// print_r(var_dump($output)) ; echo "<hr>";
$curlError = curl_errno($ch);
$responseCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

// echo '<pre>';
// print_r(explode('|', $output));

$log_array = [] ;

$houtput =  explode('HIT|', $output) ;

foreach ($houtput as $key => $value) {

  if ( ! empty($value) ) {

      if ( strpos($value, "MISS|") !== FALSE ) {
        $moutput =  explode('MISS|', $value) ;

        foreach ($moutput as $mkey => $mvalue) {
          // print_r($mvalue); echo "<hr>" ;
          $log_array[] = explode('|', $mvalue) ;
        }
      }
      else {
        // print_r($value); echo "<hr>" ;
        $log_array[] = explode('|', $value) ;
      }

      // var_dump( ) ;
      
  }

}




$unique_urls = array_unique(array_column($log_array,'5')) ;
$temp_array = [] ;
foreach ($unique_urls as $key => $value) {

  if ( filter_var($value, FILTER_VALIDATE_URL) !== false) {

    $full_url = parse_url($value);
    $url = $full_url["scheme"]."://".$full_url["host"];

    if ( ! in_array($url, $temp_array) ) {
      $temp_array[] = $url ;
    }

  } 


}

$unique_urls = $temp_array ;


$filtered_output = [] ;

if ( count($unique_urls) > 0 ) {

  foreach ($unique_urls as $key => $url) {


    // $url = "https://wemy.in/" ;
    // print_r($url); echo "<hr>" ;

    $full_url = parse_url($url);
    $url = $full_url["scheme"]."://".$full_url["host"];

    $view_count = 0 ;
    $same_ip = '' ;
    foreach ($log_array as $log_key => $log_value) {
      
      if ( strpos($log_value[5], $url ) !== FALSE ) {

        // print_r($log_value); echo "<hr>" ;

        if ( empty($same_ip) || ( $same_ip != $log_value[4] ) ) {
          $view_count++ ;
          $same_ip = $log_value[4] ;
        }
        
        // break ;
      }


    }




    $filtered_output[] = array('site' => $url , 'count' => $view_count );


    $query = $conn->query("SELECT * FROM `site_visit_count` WHERE site_url LIKE '$url' ") ;
    if ( $query->num_rows > 0  ) {

      $svc_data = $query->fetch_assoc() ;

      $flag = 0 ;
      if ( $svc_data["date"] != $log_date ) {
        $view_count = $view_count + (int)$svc_data["view_count"] ;
        $flag = 1 ;
      }
      elseif ( ($view_count > $svc_data["view_count"]) && ($svc_data["date"] == $log_date) ) {
        $flag = 1 ;
      }


      if ( $flag == 1 ) {

          $id = $svc_data["id"] ; 

          $conn->query(" UPDATE `site_visit_count` SET `view_count`='$view_count',`date`='$log_date' WHERE id = $id ; ") ;
      }


    }
    else {
      $conn->query(" INSERT INTO `site_visit_count`( `site_url`, `view_count`, `date`) VALUES ( '$url' , '$view_count' , '$log_date' ) ; ") ;
    }

  }
}

// echo "<hr><hr>";
print_r($filtered_output); echo "<hr><hr>";
// print_r($unique_urls); echo "<hr><hr>";
// print_r($log_array) ;




die;