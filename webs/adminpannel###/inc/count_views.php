<?php
require_once('../config.php');
require_once('../inc/functions.php') ;

$table ="<div class='ount_views'><table><thead><tr><th>Website URL</th><th>Total View</th></tr></thead><tbody>";

if ( isset($_POST) && !empty($_POST['user_id']) ) {

    extract($_POST) ;
    $user_id = $conn->real_escape_string($user_id) ;

    // get all websites added by user
    $bw_query = $conn->query(" SELECT website_url , plan_id , id , plan_type , subscription_id FROM `boost_website` WHERE manager_id = '".$user_id."' ; ");

    if ( $bw_query->num_rows > 0 ) {
        $manager_sites = $bw_query->fetch_all(MYSQLI_ASSOC) ;

        foreach ($manager_sites as $key => $manager_site) {

            // print_r($manager_site) ; echo "<hr/>" ;
            
            $table .="<tr><td>".$manager_site["website_url"]."</td>";

            $view_percentage = '0%' ;
            $view_count = 0 ;
            $sb_mpage_view = 5000000;
            $total_view_label = '';

            if ( $manager_site["plan_type"] == "Subscription" ) {
                $p_query = $conn->query(" SELECT user_subscriptions.id , user_subscriptions.requested_views , plans.page_view FROM user_subscriptions , plans WHERE user_subscriptions.id = '".$manager_site["subscription_id"]."' AND plans.id = '".$manager_site["plan_id"]."' ; ");
            }
            else {
                $p_query = $conn->query(" SELECT user_subscriptions_free.id , user_subscriptions_free.requested_views , plans.page_view FROM user_subscriptions_free , plans WHERE user_subscriptions_free.id = '".$manager_site["subscription_id"]."' AND plans.id = '".$manager_site["plan_id"]."' ; ");
            }

            if ( $p_query->num_rows > 0 ) {

                $p_data = $p_query->fetch_assoc();
                $sb_mpage_view = empty($p_data["requested_views"]) ? $p_data["page_view"] : $p_data["requested_views"];
                // print_r($p_data) ; echo "<hr/>" ;
                
                // get page views used
                $svc_query = $conn->query(" SELECT id , view_count FROM `site_visit_count` WHERE website_id = '".$manager_site["id"]."' ORDER BY id ASC LIMIT 1 ; ") ;

                if ( $svc_query->num_rows > 0 ) {

                    $svc_data = $svc_query->fetch_assoc();

                    $view_count = $svc_data["view_count"] ;

                    // if ( $p_data["page_view"] != "Unlimited" ) {
                    //     $sb_mpage_view = str_replace(',', '', $p_data["page_view"]);
                    //     $sb_mpage_view = (int)$sb_mpage_view;
                    // }
                    // else {
                    //     $sb_mpage_view = $p_data["page_view"];
                    // }
                    // $sb_mpage_view = $p_data["page_view"];

                    $table .="<td>".$view_count."/".$sb_mpage_view."</td></tr>"; 
                }
                else {
                    $table .="<td>0/".$sb_mpage_view."</td></tr>"; 
                }
            }
            else {
               $table .="<td>No plan found.</td></tr>"; 
            }

            /*** Code with plans table ***/ 
            /*** 
            $p_query = $conn->query(" SELECT id , page_view , list_of_price , list_of_price_inr FROM `plans` WHERE `id` = '".$manager_site["plan_id"]."' AND status = 1 LIMIT 1 ");

            if ( $p_query->num_rows > 0 ) {

                $p_data = $p_query->fetch_assoc();

                // get page views used
                $svc_query = $conn->query(" SELECT id , view_count FROM `site_visit_count` WHERE website_id = '".$manager_site["id"]."' ORDER BY id ASC LIMIT 1 ; ") ;

                if ( $svc_query->num_rows > 0 ) {

                    $svc_data = $svc_query->fetch_assoc();

                    $view_count = $svc_data["view_count"] ;

                    // if ( $p_data["page_view"] != "Unlimited" ) {
                    //     $sb_mpage_view = str_replace(',', '', $p_data["page_view"]);
                    //     $sb_mpage_view = (int)$sb_mpage_view;
                    // }
                    // else {
                    //     $sb_mpage_view = $p_data["page_view"];
                    // }

                    $sb_mpage_view = $p_data["page_view"];

                    $table .="<td>".$view_count."/".$sb_mpage_view."</td></tr>"; 
                }
                else {
                    $table .="<td>0/".$p_data["page_view"]."</td></tr>"; 
                }
            }
            else {
               $table .="<td>No plan found.</td></tr>"; 
            }
            ***/
            /*** End code with plans table ***/ 
        }
    }
    else {
       $table .="<tr><td colspan='2'>No data found.</td>"; 
    }


}
else {
    $table .="<tr><td colspan='2'>No data found.</td>";
}

$table .="</tbody></table></div>";

echo $table;


?>