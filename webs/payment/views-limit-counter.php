<?php

$views_limit = 0 ;

$cquery = $db->query(" SELECT id , cart_id , subscription_id , requested_views , change_id , page_view FROM `cart` , `plans` WHERE plans.id = cart.subscription_id  AND `user_id` LIKE '".$userID."' AND `website_id` LIKE '".$change_id."' ; ");

if ( $cquery->num_rows > 0 ) {

	$cdata = $cquery->fetch_assoc() ;

	if ( empty($cdata["requested_views"]) ) {
		$views_limit = $cdata["requested_views"] ;
	}
	else {
		$views_limit = (float) str_replace(',', '', $cdata["page_view"]);
	}

	// check boost_website table for already exist subscription (for upgrade/change)
	$bwquery = $db->query(" SELECT * FROM `boost_website` WHERE `id` = ".$change_id." AND manager_id = '".$userID."'; ");

	if ( $bwquery->num_rows > 0 ) {
		$bwdata = $bwquery->fetch_assoc() ;

		// consumed views
		$sb_purl = parse_url($bwdata["website_url"]) ;
		$temp_http = $sb_purl["scheme"]."://".$sb_purl["host"] ;
		if ( $bwdata["platform"] == "shopify" || $bwdata["platform"] == "Shopify" ) {
			$shopify_preview_url = $bwdata["shopify_preview_url"] ;

			$svc_sql = " SELECT * FROM `site_visit_count` WHERE `site_url` LIKE '%".$sb_purl["host"]."%' OR `site_url` LIKE '%".$temp_http."%' OR `site_url` LIKE '%".$shopify_preview_url."%' " ;
		}
		else {
		 	$svc_sql = " SELECT * FROM `site_visit_count` WHERE `site_url` LIKE '%".$sb_purl["host"]."%' OR `site_url` LIKE '%".$temp_http."%' " ;
		}

		$sb_svcq = $conn->query($svc_sql) ;

		if ( $sb_svcq->num_rows > 0 ) {
			$sb_svcd = $sb_svcq->fetch_assoc() ;
			$view_count = $sb_svcd["view_count"] ;
		}

		if ( $bwdata["subscription_id"] != "111111" && $bwdata["subscription_id"] != "1111111111" ) {

			$pquery = $db->query(" SELECT page_view FROM `plans` WHERE plans.id = '".$bwdata["plan_id"]."' ; ");

			if ( $pquery->num_rows > 0 ) {
				$pdata = $pquery->fetch_assoc() ;


			}

		}

	}


}




?>