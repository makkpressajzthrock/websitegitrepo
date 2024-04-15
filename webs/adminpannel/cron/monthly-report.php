<?php


ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once('../config.php');
//include('meta_details.php');

require_once('../inc/functions.php');

 $user_subscriptions = getTableData( $conn , " user_subscriptions " , " is_active = 1 AND status LIKE 'succeeded' AND script_available = 1 and id = 143 " , "" , 1 ) ;

//print_r($user_subscriptions);
foreach ($user_subscriptions as $key => $subscription_data) 
{
	
 echo $report_sent_on = $subscription_data["report_sent_on"] ;
echo '<br>';

echo $subscription_data['payer_email'];
// echo "<pre>";
echo "<br>";

$report = 1;

	$current_date = date('Y-m-d H:i:s') ;
	$diff = date_diff(date_create($current_date) , date_create($report_sent_on) ) ;
// print_r($diff);

	if ( ($diff->invert == 1 ) && ($diff->m ==1)) 
	{

		// echo "report sending";
        
		$sele = "select * from  boost_website where manager_id='".$subscription_data["user_id"]." ' and subscription_id = '".$subscription_data["id"]."' and plan_type = 'Subscription'";
		$run= mysqli_query($conn,$sele);
		$run_qr=mysqli_fetch_array($run,MYSQLI_BOTH );

		 $project=$run_qr['id'];
	
		 $website_url=$run_qr['website_url'];

		 $data = google_page_speed_insight($website_url,"desktop") ;

 


		if ( is_array($data) ) 
		{
			$lighthouseResult = $data["lighthouseResult"] ;
			$requestedUrl = $lighthouseResult["requestedUrl"] ;
			$finalUrl = $lighthouseResult["finalUrl"] ;
			$userAgent = $lighthouseResult["userAgent"] ;
			$fetchTime = $lighthouseResult["fetchTime"] ;
			$environment = $conn->real_escape_string(serialize($lighthouseResult["environment"])) ;
			$runWarnings = $conn->real_escape_string(serialize($lighthouseResult["runWarnings"])) ;
			$configSettings = $conn->real_escape_string(serialize($lighthouseResult["configSettings"])) ;
			$audits = $conn->real_escape_string(serialize($lighthouseResult["audits"])) ;
			$categories = $conn->real_escape_string(serialize($lighthouseResult["categories"])) ;
			$categoryGroups = $conn->real_escape_string(serialize($lighthouseResult["categoryGroups"])) ;
			$i18n = $conn->real_escape_string(serialize($lighthouseResult["i18n"])) ;
			$ps_desktop_s = round($lighthouseResult["categories"]["performance"]["score"] * 100, 2);

			// print_r($data);

			if($ps_desktop_s >= 1)
			{

						$mobile_data = google_page_speed_insight($website_url,"mobile") ;

							if ( is_array($mobile_data) ) 
							{
								$mobile_lighthouseResult = $mobile_data["lighthouseResult"] ;

								$mobile_environment = $conn->real_escape_string(serialize($mobile_lighthouseResult["environment"])) ;
								$mobile_runWarnings = $conn->real_escape_string(serialize($mobile_lighthouseResult["runWarnings"])) ;
								$mobile_configSettings = $conn->real_escape_string(serialize($mobile_lighthouseResult["configSettings"])) ;
								$mobile_audits = $conn->real_escape_string(serialize($mobile_lighthouseResult["audits"])) ;
								$mobile_categories = $conn->real_escape_string(serialize($mobile_lighthouseResult["categories"])) ;
								$mobile_categoryGroups = $conn->real_escape_string(serialize($mobile_lighthouseResult["categoryGroups"])) ;
								$mobile_i18n = $conn->real_escape_string(serialize($mobile_lighthouseResult["i18n"])) ;
								$ps_mobile_s = round($mobile_lighthouseResult["categories"]["performance"]["score"] * 100, 2);
									if($ps_mobile_s >= 1 )
									{


										echo $ps_desktop_s;
										echo "<br>";
										echo $ps_mobile_s;

									}
									else{
										$report = 0;
									}

							}
					

				}
				else{
					$report = 0;
				}			



		}


		if($report==1){
			echo "<br>";
			echo "Report ready";

			echo "<br>";
			echo "<br>";

				 $sql = " INSERT INTO pagespeed_report ( website_id , requestedUrl , finalUrl , userAgent , fetchTime , environment , runWarnings , configSettings , audits , categories , categoryGroups , i18n , mobile_environment , mobile_runWarnings , mobile_configSettings , mobile_audits , mobile_categories , mobile_categoryGroups , mobile_i18n ,report_sent) VALUES ( '$project' , '$requestedUrl' , '$finalUrl' , '$userAgent' , '$fetchTime' , '$environment' , '$runWarnings' , '$configSettings' , '$audits' , '$categories' , '$categoryGroups' , '$i18n' , '$mobile_environment' , '$mobile_runWarnings' , '$mobile_configSettings' , '$mobile_audits' , '$mobile_categories' , '$mobile_categoryGroups' , '$mobile_i18n',1 ) " ;

			if ( $conn->query($sql) === TRUE ) {
					echo "Report Generated";
					
					
			}




		}




	}

}

