<?php
// ini_set('display_errors', '1'); ini_set('display_startup_errors', '1'); error_reporting(E_ALL);

ini_set('memory_limit', '-1');
ini_set('max_execution_time', 0);

require_once "config.php" ;

function convertToReadableSize($size){
    $base = log($size) / log(1024);
    $suffix = array("", "KB", "MB", "GB", "TB");
    $f_base = floor($base);
    return round(pow(1024, $base - floor($base)), 1) . $suffix[$f_base];
}

function convertToBytes(string $from): ?int {
    $units = ['B', 'KB', 'MB', 'GB', 'TB', 'PB'];
    $number = substr($from, 0, -2);
    $suffix = strtoupper(substr($from,-2));

    //B or no suffix
    if(is_numeric(substr($suffix, 0, 1))) {
        return preg_replace('/[^\d]/', '', $from);
    }

    $exponent = array_flip($units)[$suffix] ?? null;
    if($exponent === null) {
        return null;
    }

    return $number * (1024 ** $exponent);
}


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

    }

}

// unique script urls
$unique_script_urls = array_unique(array_column($log_array,'6')) ;
$temp_array = [] ;
foreach ($unique_script_urls as $key => $value) {

    if ( strpos($value,"ecmrx_") !== FALSE ) {

        $full_url = parse_url($value);
        $full_path = $full_url["path"] ;
        $full_path = explode("/",$full_path) ;

        $ecmrx_id = $full_path[2] ;

        if ( !empty($ecmrx_id) && !in_array($ecmrx_id, $temp_array) ) {
            $temp_array[] = $ecmrx_id ;
        }
    }
}

$unique_script_urls = $temp_array ;


$filtered_output = [] ;
if ( count($unique_script_urls) > 0 ) {

    foreach ($unique_script_urls as $key => $ecmrx_id) {

        // for bandwidth and view count
        $bandwidth = $view_count = 0 ;
        $same_ip = '' ;
        $url = '' ;

        foreach ($log_array as $log_key => $log_value) {
          
            if ( strpos($log_value[6], $ecmrx_id ) !== FALSE ) {

                $url = $log_value[5] ;

                // for bandwidth : add the file bytes
                $view_count++ ;
                $same_ip = $log_value[4] ;

                // for bandwidth : add the file bytes
                $file_byte = (int)$log_value[2] ;
                $bandwidth += $file_byte ;              
                // break ;
            }
        }

        $view_count = round($view_count / 3) ;

        // $bandwidth = $file_byte * $view_count * 3 ;
        $bandwidth = round($bandwidth / 3) ;
        $bandwidth = convertToReadableSize($bandwidth) ;

        $filtered_output[] = array( 'id' => $ecmrx_id , 'site' => $url , 'count' => $view_count , 'bandwidth' => $bandwidth );


        // $query0 = $conn->query(" SELECT id , manager_id , platform , platform_name , website_url , shopify_preview_url FROM `boost_website` WHERE `website_url` LIKE '%$url%' OR shopify_preview_url LIKE '%$url%' LIMIT 1 ; ") ;

        $bw_id = explode("_",$ecmrx_id) ;
        $bw_id = $bw_id[1] ;
        $query0 = $conn->query(" SELECT id , manager_id , platform , platform_name , website_url , shopify_preview_url FROM `boost_website` WHERE `id` = '".$bw_id."' LIMIT 1 ; ") ;

        if ( $query0->num_rows > 0 ) {

            $bw_data = $query0->fetch_assoc() ;

            // $query = $conn->query("SELECT * FROM `site_visit_count` WHERE site_url LIKE '$url' ") ;

            $query = $conn->query(" SELECT * FROM `site_visit_count` WHERE website_id = '".$bw_data["id"]."' ; ") ;
            
            if ( $query->num_rows > 0  ) {

                $update_arr = [] ;

                $update_arr[] = " `site_url`='$url' " ;

                $svc_data = $query->fetch_assoc() ;


                $svc_total_view_count = (float)$svc_data["total_view_count"] ; 
                // echo $svc_data["total_bandwidth"];
                $svc_total_bandwidth = convertToBytes($svc_data["total_bandwidth"]) ; 

                $svc_bandwidth = convertToBytes($svc_data["bandwidth"]) ; 
                $temp_bandwidth = convertToBytes($bandwidth) ; 

                $flag = 0 ;
                if ( $svc_data["date"] != $log_date ) {

                    $update_arr[] = " `date`='".$log_date."' " ;

                    // increment views
                    $view_count = $view_count + (int)$svc_data["view_count"] ; 
                    $update_arr[] = " `view_count`='".$view_count."' " ;

                    $svc_total_view_count = $svc_total_view_count + $view_count ;
                    $update_arr[] = " `total_view_count`='".$svc_total_view_count."' " ;


                    // increment bandwidth
                    $bandwidth = $svc_bandwidth + $temp_bandwidth ; 
                    $bandwidth = convertToReadableSize($bandwidth) ;
                    $update_arr[] = " `bandwidth`='".$bandwidth."' " ;

                    $svc_total_bandwidth = $svc_total_bandwidth + $temp_bandwidth ;
                    $svc_total_bandwidth = convertToReadableSize($svc_total_bandwidth) ;
                    $update_arr[] = " `total_bandwidth`='".$svc_total_bandwidth."' " ;
                    
                    $flag = 1 ;
                }
                elseif ( ($svc_data["date"] == $log_date) ) {

                    if ( $view_count > $svc_data["view_count"] ) {
                        $update_arr[] = " `view_count`='".$view_count."' " ;

                        $svc_total_view_count = ($svc_total_view_count - (int)$svc_data["view_count"]) + $view_count ;
                        $update_arr[] = " `total_view_count`='".$svc_total_view_count."' " ;

                        $flag = 1 ;
                    }

                    if ( $temp_bandwidth > $svc_bandwidth ) {
                        $bandwidth = convertToReadableSize($temp_bandwidth) ;
                        $update_arr[] = " `bandwidth`='".$bandwidth."' " ;

                        $svc_total_bandwidth = ( $svc_total_bandwidth - $svc_bandwidth ) + $temp_bandwidth ;
                        $svc_total_bandwidth = convertToReadableSize($svc_total_bandwidth) ;
                        $update_arr[] = " `total_bandwidth`='".$svc_total_bandwidth."' " ;

                        $flag = 1 ;
                    }
                    
                }

                if ( $flag == 1 ) {
                    $id = $svc_data["id"] ; 
                    $update_str = implode(" , ", $update_arr ) ;
                    $conn->query(" UPDATE `site_visit_count` SET ".$update_str." WHERE id = $id ; ") ;
                }

            }
            else {

                $conn->query(" INSERT INTO site_visit_count ( site_url, view_count, date , bandwidth , total_view_count , total_bandwidth , website_id ) VALUES ( '$url' , '$view_count' , '$log_date' , '$bandwidth' , '$view_count' , '$bandwidth' , '".$bw_data["id"]."' ) ; ") ;

            }


        }

    }
}




echo "<pre>" ;
// echo "log_array : "; print_r($log_array[0]); echo "<hr><hr>";
// echo "unique_urls : "; print_r($unique_script_urls); echo "<hr><hr>";
echo "filtered_output : "; print_r($filtered_output); echo "<hr><hr>";




die;