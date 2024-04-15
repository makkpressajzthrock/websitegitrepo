<?php
header('Content-type: text/html');
   header('Content-Disposition: attachment; filename="file.html"');

ini_set('display_errors', '1'); ini_set('display_startup_errors', '1'); ini_set('memory_limit', '-1');
error_reporting(E_ALL);

ini_set('max_execution_time', 0);
require_once "config.php" ;

/*** To update website_id ***/ 
/***  
$query = $conn->query("SELECT id , site_url FROM `site_visit_count` ORDER BY `site_visit_count`.`id` DESC") ;

if ( $query->num_rows > 0 ) {

    while ( $svc_data = $query->fetch_assoc() ) {
        
        $query1 = $conn->query(" SELECT id , manager_id , platform , platform_name , website_url , shopify_preview_url FROM `boost_website` WHERE `website_url` LIKE '%".$svc_data["site_url"]."%' OR shopify_preview_url LIKE '%".$svc_data["site_url"]."%' ; ") ;

        if ( $query1->num_rows > 0 ) {

            $bw_data = $query1->fetch_assoc() ;
            $conn->query(" UPDATE `site_visit_count` SET `website_id`='".$bw_data["id"]."' WHERE `id`='".$svc_data["id"]."' ; ") ;
        }
    }
}
***/

$log_date = date("m-d-y" , strtotime("-1 day")) ;
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

// echo $output ;

echo '<pre>';
print_r(explode('|', $output));

