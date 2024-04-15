<?php

// ini_set('display_errors', 1); ini_set('display_errors_startup_errors', 1); error_reporting(E_ALL);

// echo '<br>paid<br>';
include('config.php');

echo  $user_id = '130'; // user_id OR manager_id
$id = 3254;
// die;
 $store_id = "";
 $id_web = "3254";
	$query_site = $conn->query(" SELECT * FROM `boost_website` WHERE manager_id='$user_id' and get_script = 0 and id= $id_web ") ;

    // ech " SELECT * FROM `boost_website` WHERE manager_id='$user_id' and get_script = 0 and id= $id_web " ;
// 		$site_data = $query_site->fetch_assoc() ;
print_r($query_site );

// echo '<pre>';
	// echo 'grn';
	// die;
while($site_data = $query_site->fetch_assoc() ){
// print_r($site_data );
 $store_id = $site_data['id'];
 $id = $store_id;
 $user_id = $site_data['manager_id'];

// print_r("aa");
// die;


   $query = $conn->query(" SELECT boost_website.*,user_subscriptions.plan_period_end FROM boost_website,user_subscriptions WHERE boost_website.id = $id and  boost_website.subscription_id = user_subscriptions.id and (user_subscriptions.status='succeeded' OR user_subscriptions.status='paid') and boost_website.plan_type <>'Free'") ;

   // print_r($query) ;

   if($data = $query->fetch_assoc() ) 
    {
    	
        $subscription_id = $data['subscription_id'];

        /****** 
        // $plan_period_end = $data['plan_period_end'];
        $plan_period_end = date('Y-m-d H:i:s',strtotime('+4 day'));
        *****/

        /***** For Fix of script expiry date *****/ 
        $extended_days = 14 ;
        $aus_query = $conn->query(" SELECT * FROM `user_subscriptions` WHERE `id` = '$subscription_id' ; ") ;
        if ( $aus_query->num_rows > 0 ) {
            $aus_data = $aus_query->fetch_assoc() ;

            // for extended script days
            
            $aus_paid_amount = (float) $aus_data["paid_amount"] ;
            if ( ($aus_data["stripe_subscription_id"] != "xxxxxxxxxxxx") && ($aus_data["stripe_payment_intent_id"] == "xxxxxxxxxxxx") && ( $aus_paid_amount > 0.00 ) ) {
                $extended_days = 14 ;
            }
            else {
                $extended_days = 60 ;
            }
        }

        $plan_period_end = $data['plan_period_end'];
        $plan_period_end = date('Y-m-d H:i:s', strtotime( '+'.$extended_days.' day' ,strtotime($plan_period_end) ) ) ;
        /***** END Fix of script expiry date *****/ 



// echo '<br>';
   	 $encSu = $data['website_url'];

   	 $encStore = parse_url($data['shopify_url'])["host"];

     $urlStore = parse_url($encSu)["host"];


     $urlStore1 = $urlStore;
     $urlStore2 = $urlStore;
      
      if (str_contains($urlStore, 'www.')) { 
             $urlStore1 = str_replace("www.","",$urlStore);
      }
      else{
             $urlStore2 = "www.".$urlStore; 
      }

    $extraUrl = " || Xzxs > f && '".$urlStore2."' == wxcs.lcvd.hostname"; 


     
    $urlprev = "";

     if($data['platform']=="Shopify"  || $data['platform']=="Bigcommerce"){

        if($data['shopify_preview_url']!="" || $data['shopify_preview_url']!=null )
        {
            $urlprev = $data['shopify_preview_url'];
            $urlStorePerv = parse_url($urlprev)["host"];
            $urlprev = "|| Xzxs > f && wxcs.lcvd.hostname == '".$urlStorePerv."'";
        }

     }


if($encStore == ""){

   	 $encSu = $data['website_url'];

   	 $encStore = parse_url($data['website_url'])["host"];	
}


	$encryptKey =  base64_encode(base64_encode(base64_encode(base64_encode($id))));
	// echo $encryptKey;
	$encryptSx = base64_encode($id); //site_id 
	$enuSuId = base64_encode($user_id);
	// echo $encryptSx;

	  $encFn = "ecmrx_$id";

	$Xzxs = "";
// date_default_timezone_set("Asia/Kolkata");

	$d = DateTime::createFromFormat('Y-m-d H:i:s', $plan_period_end);
	if ($d === false) {
	    die("Something went wrong");
	} else {

	     $Xzxs = $d->getTimestamp();
	     $Xzxs = $Xzxs*1000;
	}
// echo "eee";
 // die;

// die;
	$url = "/var/www/html/script/ecmrx/$encFn";
	if (!file_exists($url)) {
	    mkdir($url, 0777, true);
	} else{
	    mkdir($url, 0777, true);
		
	}

   $query_s = $conn->query("SELECT script,shopware,squirespace,squirespace_,wordpress_,shopify_empire from speed_script where id= 1") ;

   $data_s = $query_s->fetch_assoc();

   $plt = ""; 

     if(strtoupper($data['platform_name'])=="SHOPWARE 5" || strtoupper($data['platform_name'])=="SHOPWARE5"  ||  strtoupper($data['platform_name'])=="SHOPWARE 6" ||  strtoupper($data['platform_name'])=="SHOPWARE6"  ||  strtoupper($data['platform_name'])=="SHOPWARE" ){

       $script = $data_s['shopware'];
       $plt = "SHOPWARE";
     }
     elseif(strtoupper($data['platform_name'])=="SQUARESPACE" || strtoupper($data['platform'])=="SQUARESPACE" ||  strtoupper($data['platform'])=="SQUARE SPACE" ||  strtoupper($data['platform_name'])=="SSQUARE SPACE" ){
      $script = $data_s['squirespace'];
       $plt = "SquareSpace";

     }
     elseif(strtoupper($data['platform_name'])=="SQUARESPACE." || strtoupper($data['platform'])=="SQUARESPACE." ||  strtoupper($data['platform'])=="SQUARE SPACE." ||  strtoupper($data['platform_name'])=="SSQUARE SPACE." ){
      $script = $data_s['squirespace_'];
       $plt = "SquareSpace.";

     }  
     elseif(strtoupper($data['platform_name'])=="WORDPRESS." ){
      $script = $data_s['wordpress_'];
       $plt = "WORDPRESS.";

     } 
     elseif(strtoupper($data['platform_name'])=="SHOPIFY_EMPIRE" ){
      $script = $data_s['shopify_empire'];
       $plt = "SHOPIFY.";

     }  
     elseif(strtoupper($data['platform_name'])=="wixscript1" ){
      $script = $data_s['wixscript1'];
       $plt = "WIX.";

     }     
     elseif(strtolower($data['platform_name'])=="weblium" ){
      $script = $data_s['Weblium'];
       $plt = "Weblium.";

     }     
     else{

       $script = $data_s['script'];
      $plt = "Other";
     }
     
 
   $script = str_replace("{-Xzxsrdf_1-}",$urlStore1,$script);
   $script = str_replace("{-Xzxsrdf_2-}",$urlStore1,$script);
   $script = str_replace("{-Xzxsrdf_3-}",$urlStore1,$script);

   $script = str_replace("{-ExtraCondition_1-}",$extraUrl.$urlprev,$script);
   $script = str_replace("{-ExtraCondition_2-}",$extraUrl.$urlprev,$script);
   $script = str_replace("{-ExtraCondition_3-}",$extraUrl.$urlprev,$script);
   $script = str_replace("{-ExtraCondition_4-}",$extraUrl.$urlprev,$script);
   $script = str_replace("{-ExtraCondition_5-}",$extraUrl.$urlprev,$script);
   $script = str_replace("{-ExtraCondition_6-}",$extraUrl.$urlprev,$script);
   $script = str_replace("{-ExtraCondition_7-}",$extraUrl.$urlprev,$script);
   $script = str_replace("{-ExtraCondition_8-}",$extraUrl.$urlprev,$script);


   $script = str_replace("{-Xzxs-}",$Xzxs,$script);
   $script = str_replace("{-enuSuId-}",$enuSuId,$script);
   $script = str_replace("{-Xzxs_2-}",$Xzxs,$script);
   $script = str_replace("{-Xzxs_3-}",$Xzxs,$script);
   $script = str_replace("{-encryptKey-}",$encryptKey,$script);
   $script = str_replace("{-encryptSx-}",$encryptSx,$script);
   $script = str_replace("{-encFn-}",$encFn,$script);
   $script = str_replace("{-encSu-}",$encSu,$script);
   $script = str_replace("{%-store-url-%}",$encStore,$script);

   $scripts = explode("{%Split_New_File%}",$script);
// print_r($scripts);
//    echo $encStore;
// die;
   $script_1 = $scripts[0];
   $script_2 = $scripts[1];
   $script_3 = $scripts[2];
   $script_log = "|file-1|".$script_1."|file-2|".$script_2."|file-3|".$script_3;

   // print_r($script);
// die;

   $f = 1;
   $s = 2;
   $t = 3;

  require_once "obfuscator/original/secure.php";

$first = $encFn.'_'.$f;

$script_1 = "//** Copyright Disclaimer under Section 107 of the copyright act 1976 ".($plt)." \n ".$script_1 . "\n //** Copyright Disclaimer under Section 107 of the copyright act 1976";
$script_2 = "//** Copyright Disclaimer under Section 107 of the copyright act 1976 \n ".$script_2 . "\n //** Copyright Disclaimer under Section 107 of the copyright act 1976";
$script_3 = "//** Copyright Disclaimer under Section 107 of the copyright act 1976 \n ".$script_3 . "\n //** Copyright Disclaimer under Section 107 of the copyright act 1976";

	$url_F = "/var/www/html/script/ecmrx/$encFn/$first.js";
// echo  $url_F;
	$myfile = fopen($url_F, "w") or die("Unable to open file!");
	$txt = $script_1;
	fwrite($myfile, $txt); 
	fclose($myfile);	

$second = $encFn.'_'.$s;

	$url_F = "/var/www/html/script/ecmrx/$encFn/$second.js";
	$myfile = fopen($url_F, "w") or die("Unable to open file!");
	$txt = $script_2;
	fwrite($myfile, $txt); 
	fclose($myfile);	

$third = $encFn.'_'.$t;

	$url_F = "/var/www/html/script/ecmrx/$encFn/$third.js";
	$myfile = fopen($url_F, "w") or die("Unable to open file!");
	$txt = $script_3;
	fwrite($myfile, $txt); 
	fclose($myfile);	

$fourth = 'website';

 echo $url_F = "/var/www/html/script/ecmrx/$encFn/$fourth.txt";
  $myfile = fopen($url_F, "w") or die("Unable to open file!");
  $txt = $encSu;
  fwrite($myfile, $txt); 
  fclose($myfile);    
  
	



  $surl = "/script/ecmrx/$encFn/$first.js,/script/ecmrx/$encFn/$second.js,/script/ecmrx/$encFn/$third.js";
  $isbunny = 0;

 if($data['isbunny']==1){

 
      $count_site = $conn->query("SELECT id from script_log where site_id = $id") ;
      $count_site_num = $count_site->num_rows;


      require_once "bunnycdn/index-new.php";

    $bunny = "https://websitespeedycdn.b-cdn.net/speedyscripts";
    $surl = "$bunny/$encFn/$first.js,$bunny/$encFn/$second.js,$bunny/$encFn/$third.js";
    $isbunny = 1;

 } 

       
   $conn->query(" UPDATE boost_website SET get_script = 1 where id = $id") ;
   $conn->query(" DELETE FROM script_log where site_id = $id") ;
   $conn->query(" INSERT INTO script_log(site_id, url, bunny, expired_at, script_type, script_log) VALUES ('$id', '$surl' ,'$isbunny' ,'$plan_period_end', 'Paid', '".mysqli_real_escape_string($conn, $script_log)."') ") ;



	// $surl = "/script/ecmrx/$encFn/$first.js,/script/ecmrx/$encFn/$second.js,/script/ecmrx/$encFn/$third.js";
 //   $conn->query(" UPDATE boost_website SET get_script = 1 where id = $id") ;
 //  $conn->query(" UPDATE script_log SET status = 0 where site_id = $id") ;   
 //   $conn->query(" INSERT INTO script_log(site_id, url, expired_at,script_type) VALUES ('$id', '$surl', '$plan_period_end','Paid') ") ;
 //   $conn->query(" UPDATE user_subscriptions SET script_updated_on = now() where id = $subscription_id") ;

//  header("location: ".$_SERVER['HTTP_REFERER']);
 
// exit;

	}

}

?>