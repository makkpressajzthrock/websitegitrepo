<?php
include('config.php');

$site_id = '3392';
 $store_id = $site_id;
 $id = $site_id;

// die;

echo "SELECT boost_website.*,user_subscriptions_free.plan_end_date FROM boost_website,user_subscriptions_free WHERE boost_website.id = $id and boost_website.plan_type = 'Free' and boost_website.subscription_id = user_subscriptions_free.id ";


   $query = $conn->query(" SELECT boost_website.*,user_subscriptions_free.plan_end_date FROM boost_website,user_subscriptions_free WHERE boost_website.id = $id and boost_website.plan_type = 'Free' and boost_website.subscription_id = user_subscriptions_free.id  ") ;

   if($data = $query->fetch_assoc() ) 
    {
    	print_r($data);
// echo "<br><br>";
    	
//      $subscription_id = $data['subscription_id'];
     
   	 $plan_period_end = $data['plan_end_date'];
     $plan_period_end = date('Y-m-d H:i:s', strtotime( '+14 day' ,strtotime($plan_period_end) ) ) ;

   	 $encSu = $data['website_url'];

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

     // die;
 
     $urlprev = "";

     if($data['platform']=="Shopify"){

        if($data['shopify_preview_url']!="" || $data['shopify_preview_url']!=null )
        {
            $urlprev = $data['shopify_preview_url'];
            $urlStorePerv = parse_url($urlprev)["host"];
            $urlprev = " || Xzxs > f && wxcs.lcvd.hostname == '".$urlStorePerv."'";
        }

     }



 
	$encryptKey =  base64_encode(base64_encode(base64_encode(base64_encode($id))));
	// echo $encryptKey;
	$encryptSx = base64_encode($site_id) ; //site_id 
	// echo $encryptSx;
    $enuSuId = base64_encode($user_id);


	$encFn = "ecmrx_$id";

	$Xzxs = "";

	$d = DateTime::createFromFormat('Y-m-d H:i:s', $plan_period_end);
	if ($d === false) {
	    die("Something went wrong");
	} else {
	    $Xzxs = $d->getTimestamp();
      $Xzxs = $Xzxs*1000;

	}

	$url = "/var/www/html/script/ecmrx/$encFn";
	if (!file_exists($url)) {
	    mkdir($url, 0777, true);
	} 

   $query_s = $conn->query("SELECT script,shopware from speed_script where id= 1") ;

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
     else{
       $script = $data_s['script'];

     }
     
   // print_r($script);
   // die;
   
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
// die;

   $script_1 = $scripts[0];
   $script_2 = $scripts[1];
   $script_3 = $scripts[2];

   $f = 1;
   $s = 2;
   $t = 3;

   require_once "obfuscator/original/secure.php";

$script_1 = "//** Copyright Disclaimer under Section 107 of the copyright act 1976 ".($plt)." \n ".$script_1 . "\n //** Copyright Disclaimer under Section 107 of the copyright act 1976";
$script_2 = "//** Copyright Disclaimer under Section 107 of the copyright act 1976 \n ".$script_2 . "\n //** Copyright Disclaimer under Section 107 of the copyright act 1976";
$script_3 = "//** Copyright Disclaimer under Section 107 of the copyright act 1976 \n ".$script_3 . "\n //** Copyright Disclaimer under Section 107 of the copyright act 1976";

$first = $encFn.'_'.$f;

  $url_F = "/var/www/html/script/ecmrx/$encFn/$first.js";
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
   $conn->query(" UPDATE boost_website SET get_script = 1 where id = $id") ;
   $conn->query(" UPDATE script_log SET status = 0 where site_id = $id") ;
   $conn->query(" INSERT INTO script_log(site_id, url, expired_at, script_type) VALUES ('$id', '$surl' ,'$plan_period_end', 'Free') ") ;

 // header("location: ".$_SERVER['HTTP_REFERER']);
 //    	exit;


    }
?>


