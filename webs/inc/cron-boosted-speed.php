<?php
   require_once("config.php") ;
   require_once('functions.php');
 $fetchCode = "SELECT `id`, `manager_id`, `platform`, `platform_name`, `website_name`, `website_url`, `shopify_url`, `shopify_preview_url`
 FROM `boost_website`
 WHERE `platform` IN ('Wix', 'Bigcommerce', 'Shopify','SquareSpace','Clickfunnels','Webflow','Saas','Custom Website','Wordpress','Woocommerce')
 order by id desc";
 $fetchResult = mysqli_query($conn,$fetchCode);
 $result = mysqli_fetch_all($fetchResult,MYSQLI_ASSOC);
 foreach($result as $row){
    $website_id = $row['id'];
    $platform = $row['platform'];
    $website_url = $row['website_url'];
    ///////old-speed////////////////////////
    $oldSpeed = getTableData( $conn , " pagespeed_report " , " website_id = '".$row["id"]."' and no_speedy = '1'  ", " order by id desc " , 1, " website_id,requestedUrl,mobile_categories,categories,no_speedy" ) ;
    
    $old_categories_m = unserialize($oldSpeed[0]["mobile_categories"]);
    $old_categories_d = unserialize($oldSpeed[0]["categories"]);
              
    $old_speed_mobile = round($old_categories_m["performance"]["score"] * 100, 2);
    $old_speed_desktop = round($old_categories_d["performance"]["score"] * 100, 2);
    
    // echo print_r($old_categories_d); die;
    ///////current-speed////////////////////////
    $currentSpeed = getTableData( $conn , " pagespeed_report " , " website_id = '".$row["id"]."' and no_speedy = '0'  ", " order by id desc " , 1, " website_id,requestedUrl,mobile_categories,categories,no_speedy" ) ;
   
    $cs_categories_m = unserialize($currentSpeed[0]["mobile_categories"]);
    $cs_categories_d = unserialize($currentSpeed[0]["categories"]);
    $current_speed_mobile = round($cs_categories_m["performance"]["score"] * 100, 2);
    $current_speed_desktop = round($cs_categories_d["performance"]["score"] * 100, 2);
  
  
    $selectDummyData = getTableData( $conn , " pagespeed_dummy_report " , " website_id = '".$row["id"]."'  ", " order by id desc " , 1, " website_id,platform,website_url" );
  
    if(isset($selectDummyData[0]['website_id'])){
    }else{
        if($current_speed_mobile>=70 &&$current_speed_desktop>=85 && $old_speed_mobile>=5 && $old_speed_desktop>=5 ){
            $sql = $conn->query("INSERT INTO `pagespeed_dummy_report`(`website_id`,`platform`, `website_url`, `old_mobile_speed`, `old_desktop_speed`, `current_mobile_speed`, `current_desktop_speed`) VALUES ('$website_id','$platform','$website_url','$old_speed_mobile','$old_speed_desktop','$current_speed_mobile','$current_speed_desktop')");
        }
    }
}

?>