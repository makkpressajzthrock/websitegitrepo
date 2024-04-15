<?php
include('config.php');
 
$store_id = $_REQUEST['website'];
$id =  base64_decode($store_id);

   $query = $conn->query(" SELECT boost_website.*,user_subscriptions.plan_period_end FROM boost_website,user_subscriptions WHERE boost_website.id = $id and  boost_website.subscription_id = user_subscriptions.id and user_subscriptions.status='succeeded' and boost_website.plan_type <>'Free'") ;

   if($data = $query->fetch_assoc() ) 
    {
    	
     $subscription_id = $data['subscription_id'];
     
   	 $plan_period_end = $data['plan_period_end'];

   	 $encSu = $data['shopify_url'];

	$encryptKey =  base64_encode(base64_encode(base64_encode(base64_encode($id))));
	// echo $encryptKey;
	$encryptSx = $store_id ; //site_id 
	// echo $encryptSx;

	$encFn = "ecmrx_$id";

	$Xzxs = "";

	$d = DateTime::createFromFormat('Y-m-d H:i:s', $plan_period_end);
	if ($d === false) {
	    die("Something went wrong");
	} else {
	    $Xzxs = $d->getTimestamp();
	}

	$url = "/var/www/html/ecommercespeedy/script/ecmrx/$encFn";
	if (!file_exists($url)) {
	    mkdir($url, 0777, true);
	} 

   $query_s = $conn->query("SELECT script from speed_script where id= 1") ;

   $data_s = $query_s->fetch_assoc();
   $script = $data_s['script'];
   // die;
   $script = str_replace("{-Xzxs-}",$Xzxs,$script);
   $script = str_replace("{-encryptKey-}",$encryptKey,$script);
   $script = str_replace("{-encryptSx-}",$encryptSx,$script);
   $script = str_replace("{-encFn-}",$encFn,$script);
   $script = str_replace("{-encSu-}",$encSu,$script);

	$url_F = "/var/www/html/ecommercespeedy/script/ecmrx/$encFn/$encFn.js";
	$myfile = fopen($url_F, "w") or die("Unable to open file!");
	$txt = $script;
	fwrite($myfile, $txt); 
	fclose($myfile);	


	$surl = "/ecommercespeedy/script/ecmrx/$encFn/$encFn.js";
   $conn->query(" UPDATE boost_website SET get_script = 1 where id = $id") ;
   $conn->query(" INSERT INTO script_log(site_id, url) VALUES ('$id', '$surl') ") ;

 header("location: ".$_SERVER['HTTP_REFERER']);
 
exit;

	}


   $query = $conn->query(" SELECT boost_website.*,user_subscriptions_free.plan_end_date FROM boost_website,user_subscriptions_free WHERE boost_website.id = $id and boost_website.plan_type = 'Free' and boost_website.subscription_id = user_subscriptions_free.id  ") ;

   if($data = $query->fetch_assoc() ) 
    {
    	print_r($data);

    	
     $subscription_id = $data['subscription_id'];
     
   	 $plan_period_end = $data['plan_end_date'];

   	 $encSu = $data['shopify_url'];

	$encryptKey =  base64_encode(base64_encode(base64_encode(base64_encode($id))));
	// echo $encryptKey;
	$encryptSx = $store_id ; //site_id 
	// echo $encryptSx;

	$encFn = "ecmrx_$id";

	$Xzxs = "";

	$d = DateTime::createFromFormat('Y-m-d H:i:s', $plan_period_end);
	if ($d === false) {
	    die("Something went wrong");
	} else {
	    $Xzxs = $d->getTimestamp();
	}

	$url = "/var/www/html/ecommercespeedy/script/ecmrx/$encFn";
	if (!file_exists($url)) {
	    mkdir($url, 0777, true);
	} 

   $query_s = $conn->query("SELECT script from speed_script where id= 1") ;

   $data_s = $query_s->fetch_assoc();
   $script = $data_s['script'];
   // die;
   $script = str_replace("{-Xzxs-}",$Xzxs,$script);
   $script = str_replace("{-encryptKey-}",$encryptKey,$script);
   $script = str_replace("{-encryptSx-}",$encryptSx,$script);
   $script = str_replace("{-encFn-}",$encFn,$script);
   $script = str_replace("{-encSu-}",$encSu,$script);

	$url_F = "/var/www/html/ecommercespeedy/script/ecmrx/$encFn/$encFn.js";
	$myfile = fopen($url_F, "w") or die("Unable to open file!");
	$txt = $script;
	fwrite($myfile, $txt); 
	fclose($myfile);	


	$surl = "/ecommercespeedy/script/ecmrx/$encFn/$encFn.js";
   $conn->query(" UPDATE boost_website SET get_script = 1 where id = $id") ;
   $conn->query(" INSERT INTO script_log(site_id, url) VALUES ('$id', '$surl') ") ;

 header("location: ".$_SERVER['HTTP_REFERER']);
    	exit;
    }
?>