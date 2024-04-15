<?php 

require_once('config.php');
require_once('inc/functions.php') ;
require_once('meta_details.php') ;
$admin_user = $conn->query(" SELECT email, id,country,flow_step,user_type,sumo_code,shareasale , phone FROM `admin_users` WHERE `id` = '".$_SESSION['user_id']."' AND userstatus LIKE '".$_SESSION  ['role']."' ");
    if ( $admin_user->num_rows > 0 ) {
    $row = $admin_user->fetch_assoc() ;
    }  

/*** pagespeed_report table condition ***/ 
$pagespeedy_report = PAGESPEED_REPORT_TABLE;

$is_cancled = 1;
$plan_country = "";
if($row['country'] != "101"){   // Matching user country to show plan link
    $plan_country = "-us";
} 
include("error_message_bar_subscription.php");
$boost_web = $conn->query(" SELECT id,website_url,website_name,subscription_id,plan_type FROM `boost_website` WHERE manager_id = '".$_SESSION['user_id']."' ORDER BY `boost_website`.`id` ASC ");
    if ( $boost_web->num_rows > 0 ) {
        $first_data = $boost_web->fetch_assoc() ;
    }
if($first_data['id'] == ''){
    header("location: ".HOST_URL."customize-flow.php") ;
    die();   
}   
if($row['flow_step']==1){
    $user_id = $_SESSION["user_id"] ; 
    $get_flow = $conn->query(" SELECT id FROM `boost_website` WHERE manager_id = '$user_id' ");
    $d = $get_flow->fetch_assoc();
    if ( $row["user_type"] == "Dealify" || $row["user_type"] == "AppSumo" ) {
        header("location: ".HOST_URL."adminpannel/dashboard.php");
    }
    else{
        header("location: ".HOST_URL."plan$plan_country.php?sid=".base64_encode($d['id']));
    }
    die() ;  
}     
$suco_c = 0;
if($row['sumo_code'] !="" && $row['sumo_code'] !="null"){
    $suco_c = 1;
}
$plan_lifetime = "";
$plan_lifetime_type = "";
if ( empty(count($row)) ) {
    header("location: ".HOST_URL."adminpannel/");
    die() ;
}
$user_id = $_SESSION["user_id"] ;
$project_id = base64_decode($_GET['project']) ;
?>
<?php require_once("inc/style-and-script.php") ; ?>
<style>
.loader {
    background-color: #ffffff5e;
    height: 100%;
    position: absolute;
    text-align: center;
    margin: auto;
    display: none;
    width: 100%;
}
.profile_tabs.dashboard_sn h3 {
    border-bottom: none;
    font-size: 17px;
}
.url-old-speed-score {
    outline: 2px solid #f23640 !important;
}
.d-rma-request , .d-rma-view {
    text-transform: capitalize !important;
}
</style>
</head>
<body class="custom-tabel">
    <?php
        if( $row['shareasale'] == 0 ) {
            $user_id = $_SESSION['user_id'];
            ?>
            <img src="https://www.shareasale.com/sale.cfm?tracking=<?=$user_id?>&amount=0.00&merchantID=144859&transtype=lead" width="1" height="1">
            <script src="https://www.dwin1.com/58969.js" type="text/javascript" defer="defer"></script>
            <?php
            $conn->query(" UPDATE admin_users SET shareasale = 1 WHERE `id` = '".$user_id."'; ") ;
        }
    ?>
    <div class="loader">Please Wait...</div>
    <input type="hidden" id="manager-id" value="<?=$row["id"]?>">
    <div class="d-flex" id="wrapper">
        <div class="top-bg-img" ></div>
        <!-- sidebar -->
        <?php require_once("inc/sidebar.php"); ?>
        <!-- Page content wrapper-->
        <div id="page-content-wrapper">
            <!-- topbar -->
            <?php require_once("inc/topbar.php"); ?>           
            <!-- Page content-->
            <div class="container-fluid content__up project__dashboard">
                <h1 class="mt-4 on_desktop">Overview</h1>
                <?php require_once("inc/alert-status.php") ; ?>
                <div><h3>Dashboard</h3></div>
                <div class="profile_tabs dashboard_sn">
                    <div class="card_con_ss">
                        <div class="speed_head">
                            <h2>Old Speed </h2>
                            <h2>Updated Speed</h2>
                        </div>
                        <?php
                        $speed = 0 ;
                        $website_data = getTableData( $conn , " boost_website " , " id = '".$project_id."' AND manager_id = '".$user_id."' " );
                        $wd_desktop = $wd_mobile = "-";
                        $datetimecon = "-"; 
                        // $wd_query = $conn->query("SELECT id , website_id , parent_website , categories , mobile_categories , updated_at , no_speedy FROM `pagespeed_report` WHERE `website_id` = '".$project_id."' AND `parent_website` = '0' AND no_speedy = 1 ORDER BY `pagespeed_report`.`id` asc LIMIT 1");
                        $wd_query = $conn->query("SELECT id , website_id , parent_website , categories , mobile_categories , updated_at , no_speedy FROM $pagespeedy_report WHERE `website_id` = '".$project_id."' AND `parent_website` = '0' AND no_speedy = 1 ORDER BY id asc LIMIT 1");
                        if ($wd_query->num_rows > 0) {
                            $speed = 1 ;
                            $wd_data = $wd_query->fetch_assoc();
                            $wd_categories = unserialize($wd_data["categories"]);
                            $wd_performance = round($wd_categories["performance"]["score"] * 100, 2);
                            $wd_desktop = $wd_performance."/100";
                            //abc
                            $bwd_desktop_url1 = $wd_performance;
                            $wd_mobile_categories = unserialize($wd_data["mobile_categories"]);
                            $wd_mobile = round($wd_mobile_categories["performance"]["score"] * 100, 2)."/100";
                            //abc
                            $bwd_mobile_url1  = round($wd_mobile_categories["performance"]["score"] * 100, 2);
                            $timedy2 = $wd_data['updated_at'];
                            $vartime2 = strtotime($timedy2);
                            $datetimecon = date("F d, Y H:i", $vartime2);
                        }
                        else {
                            
                            $wd_query = $conn->query("SELECT id , website_id , parent_website , categories , mobile_categories , updated_at , no_speedy FROM $pagespeedy_report WHERE `website_id` = '".$project_id."' AND `parent_website` = '0' ORDER BY `id` ASC LIMIT 1");
                            // $wd_query = $conn->query("SELECT id , website_id , parent_website , categories , mobile_categories , updated_at , no_speedy FROM `pagespeed_report` WHERE `website_id` = '".$project_id."' AND `parent_website` = '0' ORDER BY `pagespeed_report`.`id` ASC LIMIT 1");
                            if ($wd_query->num_rows > 0) {
                                $speed = 1 ;
                                $wd_data = $wd_query->fetch_assoc();
                                $wd_categories = unserialize($wd_data["categories"]);
                                $wd_performance = round($wd_categories["performance"]["score"] * 100, 2);
                                $wd_desktop = $wd_performance."/100";
                                //abc
                                $bwd_desktop_url1 = $wd_performance;
                                $wd_mobile_categories = unserialize($wd_data["mobile_categories"]);
                                $wd_mobile = round($wd_mobile_categories["performance"]["score"] * 100, 2)."/100";
                                //abc
                                $bwd_mobile_url1  = round($wd_mobile_categories["performance"]["score"] * 100, 2);
                                $timedy2 = $wd_data['updated_at'];
                                $vartime2 = strtotime($timedy2);
                                $datetimecon = date("F d, Y H:i", $vartime2);
                            }
                        }
                        ?>
                        <div class="row dash_overview" style="flex-wrap: wrap;">
                            <!-- url1 old speed -->
                            <div class="col-md-3 border rounded with__shadow p-0 mx-4 web_details_s dash_board_s card_one">   
                                <div style="display: none;" id="page-speed-table" data-project="<?=$website_data['id']?>" data-type="page-speed">
                                <?php
                                if( $speed == 0 ) {
                                    ?>
                                    <button type="button" class="btn btn-primary reanalyze-btn-new" data-speedtype="old" data-website_name="<?=$website_data["website_url"]."?nospeedy"?>" data-website_url="<?=$website_data["website_url"]?>" data-website_id="<?=$website_data["id"]?>" data-additional="0">
                                        <svg class="svg-inline--fa fa-arrows-rotate" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="arrows-rotate" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" data-fa-i2svg="">
                                        <path fill="currentColor" d="M105.1 202.6c7.7-21.8 20.2-42.3 37.8-59.8c62.5-62.5 163.8-62.5 226.3 0L386.3 160H336c-17.7 0-32 14.3-32 32s14.3 32 32 32H463.5c0 0 0 0 0 0h.4c17.7 0 32-14.3 32-32V64c0-17.7-14.3-32-32-32s-32 14.3-32 32v51.2L414.4 97.6c-87.5-87.5-229.3-87.5-316.8 0C73.2 122 55.6 150.7 44.8 181.4c-5.9 16.7 2.9 34.9 19.5 40.8s34.9-2.9 40.8-19.5zM39 289.3c-5 1.5-9.8 4.2-13.7 8.2c-4 4-6.7 8.8-8.1 14c-.3 1.2-.6 2.5-.8 3.8c-.3 1.7-.4 3.4-.4 5.1V448c0 17.7 14.3 32 32 32s32-14.3 32-32V396.9l17.6 17.5 0 0c87.5 87.4 229.3 87.4 316.7 0c24.4-24.4 42.1-53.1 52.9-83.7c5.9-16.7-2.9-34.9-19.5-40.8s-34.9 2.9-40.8 19.5c-7.7 21.8-20.2 42.3-37.8 59.8c-62.5 62.5-163.8 62.5-226.3 0l-.1-.1L125.6 352H176c17.7 0 32-14.3 32-32s-14.3-32-32-32H48.4c-1.6 0-3.2 .1-4.8 .3s-3.1 .5-4.6 1z"></path>
                                        </svg><!-- <i class="fa fa-refresh" aria-hidden="true"></i> Font Awesome fontawesome.com -->
                                    </button>
                                    <?php
                                }
                                ?>
                                </div>
                                <ul class="list-group">
                                    <li class="list-group-item">Link 1<span class="float-right">Homepage</span></li>
                                    <li class="list-group-item">URL<span class="float-right"><a style="pointer-events: none; " href="<?=$website_data["website_url"]?>" ><?=$website_data["website_url"]?></a></span></li>
                                    <li class="list-group-item">Desktop <span class="float-right link1-desktop-speed"><?=$wd_desktop?></span></li>
                                    <li class="list-group-item">Mobile <span class="float-right link1-mobile-speed"><?=$wd_mobile?></span></li>
                                    <li class="list-group-item">Last Updated <span class="float-right link1-last-update"><?php echo $datetimecon;  ?></span></li>
                                </ul>
                            </div>
                            <?php
                            for ($i=2; $i < 4 ; $i++) { 
                                if ( $i == 2 ) {
                                    // url2 old speed
                                    $link2Txt = 'Category page or services page or similar page URL';
                                }
                                elseif ( $i == 3 ) {
                                    // url3 old speed
                                    $link2Txt = 'Product page or lead generation page or similar page URL';
                                }
                                $sno = $i ;
                                $awu2_query = $conn->query(" SELECT id,updated_at,website_url FROM `additional_websites` WHERE `manager_id` = '$user_id' AND `website_id` = '$project_id' AND url_priority = $i ORDER BY id DESC LIMIT 1 ; ");
                                if ( $awu2_query->num_rows > 0 ) {
                                    $additional_url = $awu2_query->fetch_assoc() ;
                                    $ps_desktop = $ps_mobile = "complete Installation";
                                    
                                    // $ps_query = $conn->query("SELECT id , website_id , parent_website , categories , mobile_categories , updated_at , no_speedy FROM pagespeed_report WHERE website_id = '".$additional_url["id"]."' AND parent_website = '$project_id'  AND no_speedy='1' ORDER BY id DESC LIMIT 1") ;
                                    $ps_query = $conn->query("SELECT id , website_id , parent_website , categories , mobile_categories , updated_at , no_speedy FROM $pagespeedy_report WHERE website_id = '".$additional_url["id"]."' AND parent_website = '$project_id'  AND no_speedy='1' ORDER BY id DESC LIMIT 1") ;
                                    if ( $ps_query->num_rows > 0 ) {
                                        $ps_data = $ps_query->fetch_assoc() ;
                                        $ps_categories = unserialize($ps_data["categories"]) ;
                                        $ps_performance = round($ps_categories["performance"]["score"]*100,2) ;
                                        $ps_desktop = $ps_performance."/100" ;
                                        //abc
                                        $bwd_desktop_url2 = $ps_performance;
                                        $ps_mobile_categories = unserialize($ps_data["mobile_categories"]) ;
                                        $ps_mobile = round($ps_mobile_categories["performance"]["score"]*100,2)."/100" ;
                                        //abc
                                        $bwd_mobile_url2 = round($ps_mobile_categories["performance"]["score"]*100,2); 
                                        $timedy= $ps_data['updated_at'];
                                        $vartime = strtotime($timedy);
                                        $datetimecon= date("F d, Y H:i", $vartime);
                                    }
                                    if($ps_desktop=='complete Installation'  && $ps_mobile=='complete Installation' ){
                                        $hideCI = 'block';   
                                        $datetimecon = 'complete Installation';                        
                                    }
                                    else{
                                        $updatedDate ='block';
                                        $hideCI = 'none';
                                    }
                                    ?>
                                    <div class="col-md-3 border rounded with__shadow p-0  mx-4 dash_board_s" id="card<?=$sno;?>" >
                                        <div class="web_dts">
                                            <ul class="list-group">
                                                <li class="list-group-item">Link <?=$sno;?><span class="float-right"><?=$link2Txt?></span></li>
                                                <li class="list-group-item">URL <?=$sno;?><span class="float-right com_inst"><a style="pointer-events: none; " id="websiteUrl<?=$sno;?>" href="<?=$additional_url["website_url"]?>" ><?=$additional_url["website_url"]?></a></span></li>
                                                <li class="list-group-item">Desktop <span class="float-right"><?=$ps_desktop?></span></li>
                                                <li class="list-group-item">Mobile <span class="float-right"><?=$ps_mobile?></span></li>
                                                <li id="lastUpdated<?=$sno;?>" style="display:<?=$updatedDate?>" class="list-group-item">Last Updated <span class="float-right"><?php echo $datetimecon;?></span></li>
                                            </ul>
                                            <span class="btn_con_w"><a type="button" class="btn btn-danger"  style="position: relative;z-index: 1; display:<?=$hideCI?>"  href="/adminpannel/script-installations.php?project=<?=base64_encode($project_id)?>" >Complete Installation</a></span>
                                        </div>
                                    </div>
                                    <?php
                                }
                                else {
                                    if ( $i == 2 ) {
                                        // url2 old speed
                                        $link2Classes = "col-md-3 border rounded with__shadow p-0 mx-4 web_details_s dash_board_s u_urltwo";
                                    }
                                    elseif ( $i == 3 ) {
                                        // url3 old speed
                                        $link2Classes = "col-md-3 border rounded with__shadow p-0 mx-4 web_details_s dash_board_s u_urlthree";
                                    }
                                    ?>
                                    <div class="<?=$link2Classes;?>"  id="hideAddedNewUrl<?=$sno;?>Form">
                                        <div class="" style="position: relative; z-index:1">
                                            <form id="addedNewUrl<?=$sno;?>" class="additionalUrlForm" method="post">
                                                <div class="web_dts">
                                                    <ul class="list-group">
                                                        <li class="list-group-item">Link <?=$sno;?><span class="float-right"><?=$link2Txt;?></span></li>
                                                        <li class="list-group-item">URL <?=$sno;?><span class="float-right com_inst"><input type="text" name="newUrl<?=$sno;?>" id="newUrl<?=$sno;?>" placeholder="Enter Url <?=$sno;?>" onblur="removeHashAndQueryParams(this);" >                               <span class="btn_con_w"><button type="submit" name="addedNewUrl<?=$sno;?>Btn" id="addedNewUrl<?=$sno;?>Btn" class="btn btn-danger">OK</button></span>
                                                            </span>
                                                        </li>
                                                        <li class="list-group-item">Desktop <span class="float-right">Enter Url <?=$sno;?></span></li>
                                                        <li class="list-group-item">Mobile <span class="float-right">Enter Url <?=$sno;?></span></li>
                                                        <li class="list-group-item">Last Updated  <span class="float-right">Enter Url <?=$sno;?></span></li>
                                                    </ul>
                                                    <div class="errors">
                                                        <span class="errroUrl<?=$sno;?>"></span>                 
                                                        <span class="subDomainUrl<?=$sno;?>Error"></span>  
                                                        <span id="sameEnterUrl<?=$sno;?>"></span> 
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                    <?php
                                }
                            }
                            ?>
                            <!-- END Additional pages urls -->
                            <!-- Boosted speed section -->
                            <!-- url1 new speed -->
                            <?php
                            $wd_desktop = $wd_mobile = "complete Installation" ;
                            $wd_desktop  = '<a href="'.HOST_URL.'adminpannel/script-installations.php?project='.base64_encode($project_id).'" >complete Installation</a>';   
                            $wd_mobile  = '<a href="'.HOST_URL.'adminpannel/script-installations.php?project='.base64_encode($project_id).'" >complete Installation</a>';  
                            $datetimecon2  = '--'; 
                            $ws_status = $blank_record = "" ; 
                            $wd_query = $conn->query(" SELECT id , website_id , parent_website , categories , mobile_categories , updated_at , no_speedy , ws_status , blank_record FROM $pagespeedy_report WHERE `website_id` = '".$project_id."' AND `parent_website` = '0'  AND no_speedy = '0' AND initial_url='0' ORDER BY id DESC LIMIT 1") ;
                            // $wd_query = $conn->query(" SELECT id , website_id , parent_website , categories , mobile_categories , updated_at , no_speedy , ws_status , blank_record FROM `pagespeed_report` WHERE `website_id` = '".$project_id."' AND `parent_website` = '0'  AND no_speedy = '0' AND initial_url='0' ORDER BY `pagespeed_report`.`id` DESC LIMIT 1") ;
                            if ( $wd_query->num_rows > 0 ) {
                                $wd_desktop_val = $wd_mobile_val = 0 ;
                                $wd_data = $wd_query->fetch_assoc() ;
                                $wd_categories = unserialize($wd_data["categories"]) ;
                                $wd_performance = round($wd_categories["performance"]["score"]*100,2) ;
                                $wd_desktop_val = $wd_performance ;
                                $wd_desktop = $wd_performance."/100" ;
                                $wd_mobile_categories = unserialize($wd_data["mobile_categories"]) ;
                                $wd_mobile_performance = round($wd_mobile_categories["performance"]["score"]*100,2) ;
                                $wd_mobile_val = $wd_mobile_performance ;
                                $wd_mobile = $wd_mobile_performance."/100" ;
                                $ws_status = $wd_data["ws_status"] ;
                                $blank_record = $wd_data["blank_record"] ;
                                if ( $ws_status == "popup" ) {
                                    $hst_query = $conn->query(" SELECT ticket_id , ticket_type , status FROM `help_support_tickets` WHERE `website_id` LIKE '".$website_data["id"]."' AND ticket_type LIKE 'Manual Audit By Team' AND `status` = 1 LIMIT 1 ; ") ;
                                    if ( $hst_query->num_rows > 0 ) {
                                        $hst_data = $hst_query->fetch_assoc() ;
                                        $hst_id = $hst_data["ticket_id"] ;
                                        $hst_url = HOST_HELP_URL."user/support/ticket/".$hst_id ;
                                        $request_manual_audit = '<a href="'.$hst_url.'" class="d-rma-view btn btn-primary" target="_blank" >View Ticket</a>' ;
                                    }
                                    else {
                                        $request_manual_audit = '<a href="javascript:void(0);" class="d-rma-request" >Request Manual Audit</a>' ;
                                    }
                                    if ( $blank_record == "both" ) {
                                        if ( $wd_mobile_val <= 70 ) {
                                            $wd_mobile  = '<span>'.$request_manual_audit.'</span>'; 
                                        }
                                        if ( $wd_desktop_val <= 85 ) {
                                            $wd_desktop  = '<span>'.$request_manual_audit.'</span>'; 
                                        }
                                    }
                                    if ( $blank_record == "mobile" && $wd_mobile_val <= 70 ) {
                                        $wd_mobile  = '<span>'.$request_manual_audit.'</span>'; 
                                    }
                                    if ( $blank_record == "desktop" && $wd_desktop_val <= 85 ) {
                                        $wd_desktop  = '<span>'.$request_manual_audit.'</span>'; 
                                    }
                                }
                                $timedy2=$wd_data['updated_at'];
                                $vartime2 = strtotime($timedy2);
                                $datetimecon2= date("F d, Y H:i", $vartime2);
                            }
                            ?>
                            <div class="col-md-3 border rounded with__shadow p-0 mx-4 web_details_s dash_board_s u_urlone fix__d <?php if($ws_status == "popup"){echo"url-old-speed-score";}?> ">
                                <ul class="list-group">
                                <li class="list-group-item">Link 1<span class="float-right">Homepage</span></li>
                                <li class="list-group-item">Desktop <span class="float-right"><?=$wd_desktop?> </span></li>
                                <li class="list-group-item">Mobile <span class="float-right"><?=$wd_mobile?></span></li>
                                <li class="list-group-item">Last Updated <span class="float-right"><?=$datetimecon2?> </span></li>
                                </ul>
                            </div>
                            <!-- Additional pages urls -->
                            <!-- updated speed of url2 & url3 -->
                            <?php
                            for ($i=2; $i < 4 ; $i++) { 
                                if ( $i == 2 ) {
                                    // url2 new speed
                                    $link2Txt = 'Category page or services page or similar page URL';
                                    $link2Class = "col-md-3 border rounded with__shadow p-0  mx-4 dash_board_s u_urltwo fix__d";
                                }
                                elseif ( $i == 3 ) {
                                    // url3 new speed
                                    $link2Txt = 'Product page or lead generation page or similar page URL';
                                    $link2Class = "col-md-3 border rounded with__shadow p-0  mx-4 dash_board_s u_urlthree fix__d";
                                }
                                $sno = $i ;
                                $ps_desktop = $ps_mobile = "complete Installation" ;
                                $ps_desktop  = '<a href="'.HOST_URL.'adminpannel/script-installations.php?project='.base64_encode($project_id).'" >'.$ps_desktop.' </a>';   
                                $ps_mobile  = '<a href="'.HOST_URL.'adminpannel/script-installations.php?project='.base64_encode($project_id).'" >'.$ps_mobile.' </a>';  
                                $datetimecon2  = '--'; 
                                $ws_status = $blank_record = "" ;
                                $awu2_query = $conn->query(" SELECT id,updated_at,website_url FROM `additional_websites` WHERE `manager_id` = '$user_id' AND `website_id` = '$project_id' AND url_priority = $i ORDER BY id DESC LIMIT 1 ; ");
                                if ( $awu2_query->num_rows > 0 ) {
                                    $additional_url = $awu2_query->fetch_assoc() ;
                                    $timedy= $additional_url['updated_at'];
                                    $vartime = strtotime($timedy);
                                    $datetimecon= date("F d, Y H:i", $vartime); 
                                    $ps_query = $conn->query("SELECT id , categories , mobile_categories , updated_at , no_speedy , parent_website , website_id , ws_status , speed_data , blank_record FROM $pagespeedy_report WHERE website_id = '".$additional_url["id"]."' AND parent_website = '$project_id' AND no_speedy = '0' ORDER BY id DESC LIMIT 1 ") ;
                                    // $ps_query = $conn->query("SELECT id , categories , mobile_categories , updated_at , no_speedy , parent_website , website_id , ws_status , speed_data , blank_record FROM pagespeed_report WHERE website_id = '".$additional_url["id"]."' AND parent_website = '$project_id' AND no_speedy = '0' ORDER BY id DESC LIMIT 1 ") ;
                                    if ( $ps_query->num_rows > 0 ) {
                                        $ps_desktop_v = $ps_mobile_v = 0 ;
                                        $ps_data = $ps_query->fetch_assoc();
                                        $ps_categories = unserialize($ps_data["categories"]) ;
                                        $ps_performance = round($ps_categories["performance"]["score"]*100,2) ;
                                        $ps_desktop_v = $ps_performance ;
                                        $ps_desktop = $ps_performance."/100" ;
                                        //abc
                                        $awd_desktop_url2 = $ps_performance;
                                        $ps_mobile_categories = unserialize($ps_data["mobile_categories"]) ;
                                        $ps_performance_mobile = round($ps_mobile_categories["performance"]["score"]*100,2) ;
                                        $ps_mobile_v = $ps_performance_mobile ;
                                        $ps_mobile = $ps_performance_mobile."/100" ;
                                        //abc
                                        $awd_mobile_url2 = round($ps_mobile_categories["performance"]["score"]*100,2);
                                        $timedy2=$ps_data['updated_at'];
                                        //   echo $timedy2 ;
                                        $vartime2 = strtotime($timedy2);
                                        $datetimecon2= date("F d, Y H:i", $vartime2);
                                        $ws_status = $ps_data["ws_status"] ;
                                        $blank_record = $ps_data["blank_record"] ;
                                        if ( $ws_status == "popup" ) {
                                            $hst_query = $conn->query(" SELECT ticket_id , ticket_type , status FROM `help_support_tickets` WHERE `website_id` LIKE '".$website_data["id"]."' AND ticket_type LIKE 'Manual Audit By Team' AND `status` = 1 LIMIT 1 ; ") ;
                                            if ( $hst_query->num_rows > 0 ) {
                                                $hst_data = $hst_query->fetch_assoc() ;
                                                $hst_id = $hst_data["ticket_id"] ;
                                                $hst_url = HOST_HELP_URL."user/support/ticket/".$hst_id ;
                                                $request_manual_audit = '<a href="'.$hst_url.'" class="d-rma-view btn btn-primary" target="_blank" >View Ticket</a>' ;
                                            }
                                            else {
                                                $request_manual_audit = '<a href="javascript:void(0);" class="d-rma-request" >Request Manual Audit</a>' ;
                                            }
                                            if ( $blank_record == "both" ) {
                                                if ( $ps_mobile_v <= 70 ) {
                                                    $ps_mobile  = '<span>'.$request_manual_audit.'</span>'; 
                                                }
                                                if ( $ps_desktop_v <= 85 ) {
                                                    $ps_desktop  = '<span>'.$request_manual_audit.'</span>'; 
                                                }                                                  
                                            }
                                            if ( $blank_record == "mobile" ) {
                                                if ( $ps_mobile_v <= 70 ) {
                                                    $ps_mobile  = '<span>'.$request_manual_audit.'</span>'; 
                                                }
                                            }
                                            if ( $blank_record == "desktop" ) {
                                                if ( $ps_desktop_v <= 85 ) {
                                                    $ps_desktop  = '<span>'.$request_manual_audit.'</span>'; 
                                                }
                                            }
                                        }
                                    }
                                }
                                ?>
                                <div class="<?=$link2Class;?> <?php if($ws_status == "popup"){echo"url-old-speed-score";}?> " >
                                    <div class="web_dts">
                                        <ul class="list-group">
                                            <li class="list-group-item">Link <?=$sno?><span class="float-right"><?=$link2Txt?></span></li>
                                            <li class="list-group-item">Desktop <span class="float-right"> <?=$ps_desktop?></span></li>
                                            <li class="list-group-item">Mobile <span class="float-right"><?=$ps_mobile?></span></li>
                                            <li  id="lastUpdated<?=$sno?>" style="display:<?=$updatedDate?>" class="list-group-item">Last Updated <span class="float-right"><?php echo  $datetimecon2;?></span></li>
                                        </ul>
                                    </div>
                                </div>
                                <?php
                            }
                            ?>
                            <!-- END Additional pages urls -->
                        </div>
                        <!-- plan status timer/card -->
                        <div class="row page_details" style="flex-wrap: wrap;margin-top:20px; display:none;">
                            <?php
                            $sele_run = $website_data ;
                            $sub_ids= $sele_run['subscription_id'];
                            $plan_type= $sele_run['plan_type'];
                            $zero= 0;
                            $current_date = date('Y-m-d H:i:s') ;
                            if( $plan_type != "Free" ) {
                                $sele_run2 = getTableData( $conn , " user_subscriptions " , " id = '$sub_ids' " );
                                $plan_lifetime = $sele_run2["plan_interval"];
                                $is_subscription = count($sele_run2);
                                $is_cancled = $sele_run2['is_active'];
                                $date_expire = $sele_run2["plan_period_end"] ;
                                $date_expire_start = $sele_run2["script_updated_on"] ;
                                $diff = date_diff(date_create($current_date) , date_create($date_expire) ) ;
                                $zero= $diff->days;
                                $expired = $diff->invert; 
                            }
                            else {
                                $sele_run2 = getTableData( $conn , " user_subscriptions " , " `user_id` ='".$user_id."' AND `status` LIKE '1' " );
                                $date_expire = $sele_run2["plan_end_date"] ;
                                $diff = date_diff(date_create($current_date) , date_create($date_expire) ) ;
                                $zero = $diff->days;
                                $expired = $diff->invert;
                            }
                            ?>
                            <div class="col-md-3 border rounded with__shadow p-0 mx-4 dash_s">
                                <ul class="list-group">
                                <?php
                                if ( $plan_lifetime != 'Lifetime' ) {
                                    $ss = '';
                                    if($diff->days >1) {
                                        $ss = "s";
                                    }
                                    $activePlanLabel1 = '' ;
                                    if($is_cancled != 0){   
                                        if ($zero == 0 ||  $expired == 1) {
                                            if($is_subscription > 0) { 
                                                if($plan_lifetime=="Month Free"){                                          
                                                    $activePlanLabel1 = "Your Subscription is Expired";
                                                }
                                                else{                                           
                                                    $activePlanLabel1 = "Your subscription will Renew";
                                                }
                                            }
                                            else{
                                                $activePlanLabel1 = "Your subscription is Expired, Please subscribe to continue your website speed";
                                            }
                                        }
                                        else{ 
                                            if($is_subscription > 0){ 
                                                if($plan_lifetime=="Month Free"){
                                                    $activePlanLabel1 = "Your Subscription is Expired";
                                                }
                                                else{
                                                    $activePlanLabel1 = "Your subscription will Renew";
                                                }                                   
                                            }
                                            else{
                                                $activePlanLabel1 = "Your subscription is Expired, Please subscribe to continue your website speed";
                                            }
                                        } 
                                    }
                                    else{
                                        $site_ids = $_REQUEST['project'];
                                        $activePlanLabel1 = "<a class='upgrade_button_loc btn btn-primary' href='/plan".$plan_country.".php?sid=".$site_ids."' class='btn btn-primary'>Subscribe Now</a>";
                                    } 

                                    $activePlanLabel2 = '' ; 
                                    if($is_cancled != 0){
                                        if ($zero >= 1 && $expired ==0 ) { 
                                            $activePlanLabel2 = "After".' ' .($diff->days).' '. "Day".$ss;  
                                        } 
                                        else{ 

                                            if ($is_subscription > 0 && $zero == 0 && $expired ==0 ) {  
                                            }
                                            if($is_subscription > 0) {
                                                $site_ids = $_REQUEST['project'];
                                                if($plan_lifetime=="Month Free"){
                                                    if ($is_subscription > 0 && $zero == 0 && $expired ==0 ) {
                                                        $activePlanLabel2 = "Will Expire Today";
                                                        $activePlanLabel2 .= "<a class='upgrade_button_loc btn btn-primary' href='/plan".$plan_country.".php?change-sid=".$site_ids."&sid=".$site_ids."' class='btn btn-primary'>Upgrade Plan</a>";                                             
                                                    }
                                                    else{
                                                        $activePlanLabel2 = "Expired";
                                                        $activePlanLabel2 .= "<a class='upgrade_button_loc btn btn-primary' href='/plan".$plan_country.".php?change-sid=".$site_ids."&sid=".$site_ids."' class='btn btn-primary'>Upgrade Plan</a>";
                                                    }
                                                }
                                                else{
                                                    $activePlanLabel2 = "Renew Today";
                                                }
                                            }
                                            else {
                                                $activePlanLabel2 = "Expired";
                                            }
                                        } 
                                    }
                                    else{
                                        $activePlanLabel2 = "Canceled";
                                    } 
                                    ?>
                                    <li class="list-group-item"><?=$activePlanLabel1;?></li>
                                    <li class="list-group-item"><?=$activePlanLabel2;?></li>
                                    <?php
                                }
                                else {
                                    ?>
                                    <li class="list-group-item"></span></li>
                                    <li class="list-group-item">Lifetime Access</li>
                                    <?php
                                }
                                ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php
$country__code = '<div><label>Country Code</label><select id="country-code" class="form-control" required  name="country" ><option value="">Select</option>';
$country__code .= '';
$list_countries_code = getTableData( $conn , " list_countries " , "" , " group by sortname order by name" , 1 , " phonecode , name "  ) ;
foreach ($list_countries_code as $key => $country_data) {
    $selected = "";
    if( $country_data['id']==$row['country'] ) { $selected = "selected"; }
    $country__code .= '<option value="+'.$country_data["phonecode"].'" '.$selected.' >'.$country_data["name"].'+'.$country_data["phonecode"].'</option>';
}
$country__code .= '</select></div>';
$timezone = '<div class="full__col"><label>Time Zone</label><select class="form-control" id="tz" name="country" required>';
$timezone .= '';
$list_countries = getTableData( $conn , " timezones " , " 1 " , "" , 1 , " value , label " ) ;
foreach ($list_countries as $key => $country_data) {
    $timezone .= '<option value="'.$country_data["label"].'" >'.$country_data["label"].'</option>';
}
$timezone .= '</select></div>';
$form = '<input type="hidden" name="suitable-time"><input type="hidden" name="time-form"><input type="hidden" name="time-to"><div class="support__form" ><div class="d__flex" ><span class="request-err d-none">Please Fill All Field.</span>'.$country__code.'<div><label>Contact Number</label><input id="contact" class="form-control" type="number" value="'.$row['phone'].'" required></div></div><div class="full__col"><label>Email</label><input type="email"  class="form-control" id="email"  value="'.$row['email'].'"></div>'.$timezone.'<div class="col-12"><label>Suitable Time For Contact</label><input id="suitable-time" onchange="setDateTime(this);" min="" type="date" class="form-control" required><div class="col-12"><div class="row"><div class="col-6">From : <input type="time" id="time-form" onchange="setDateTime(this);" required></div><div class="col-6">To : <input type="time"  id="time-to" onchange="setDateTime(this);" required></div></div></div></div><button class="mt-3 btn btn-success" id="request_form" onclick="rmaSubmit();">Save</button>';
?>
<span style="display:none;" id="rma-content"><?=$form?></span>
</body>
<script src="https://code.jquery.com/jquery-3.7.1.js" ></script>
<script type="text/javascript">
function extractDomain(url) {
    try {
        const domain = new URL(url).hostname;
        return domain.startsWith('www.') ? domain.replace('www.', '') : domain;
    }
    catch (error) {
        console.error('Invalid URL:', error.message);
        return null;
    }
}
function compareAndStoreSubdomain(previousUrl, newUrl) {
    var previousDomain = extractDomain(previousUrl);
    var newDomain = extractDomain(newUrl);
    console.log("newDomain :" + newDomain)
    console.log("previousDomain :" + previousDomain)
    if (previousDomain && newDomain && (previousDomain == newDomain)) {
        return previousDomain;
    }
    else {
        return false;
    }
}
function validatePageURL(url) {
    var urlRegex = /^(https?:\/\/)?([\da-z.-]+)\.([a-z.]{2,6})(\/[^\/\s]+)$/;
    return urlRegex.test(url);
}
$(document).ready(function () {
    if ($(".reanalyze-btn-new").length > 0) {
        $(".reanalyze-btn-new").click();
    }
    $(".additionalUrlForm").on("submit", function(e){
        e.preventDefault();
        var is_valid = true;
        var managerId = <?=$user_id;?> ;
        var boostId = <?=$project_id;?> ;
        var websiteName = '<?=$website_data['website_name'];?>';
        var websiteUrl = '<?=$website_data['website_url'];?>';
        if ( $(this).attr("id") == "addedNewUrl2" ) {
            var url_priority = 2 ;
            removeHashAndQueryParams('#newUrl2') ;
            var url = $('#newUrl2').val();
        }
        else {
            var url_priority = 3 ;
            removeHashAndQueryParams('#newUrl3') ;
            var url = $('#newUrl3').val();
        }
        $('.errroUrl'+url_priority).html('');
        $('.subDomainUrl'+url_priority+'Error').html('');
        $('#sameEnterUrl'+url_priority).html('');
        if (url == '') {
            $('.errroUrl'+url_priority).html('Please enter valid url').css('color', 'red').delay(3000).fadeOut().css('display', 'block');
            is_valid = false;
        }
        else {
            var check_url = compareAndStoreSubdomain(websiteUrl, url);
            if (check_url) {
                var checkDomainVerification = checkDomainVerification1(websiteUrl,url) ;
                if ( !checkDomainVerification ) {
                    is_valid = true;
                    // loader
                    $(".loader").show().html("<div class='loader_s 123 devdev'><dotlottie-player src='https://lottie.host/ce2baafe-2f55-4b3f-8042-d3b04d48212c/FV1UcE5Bkc.lottie'  background='transparent'  speed='1'  style='width: 300px; height: 300px;' loop autoplay></dotlottie-player><p><span class='auto-type'></span></p></div>");
                    loaderest();
                    var additional_id = 0 ;
                    $.ajax({
                        url: "inc/dashboard-additional-url-fetch.php",
                        type: "POST",
                        data: {
                            user_id: managerId,
                            website_id: boostId,
                            additional_url: url,
                            website_name: websiteName,
                            website_url: websiteUrl,
                            url_priority: url_priority,
                            action: "add-additional-url",
                        },
                        dataType:"JSON",
                        success: function (response) {
                            if ( response.status == "error" ) {
                                $(".loader").hide().html("");
                                var title = "Error in URL"+url_priority ;
                                var content = response.message ;
                                swalForDashboardDomain(title,content) ;
                                $('#sameEnterUrl'+url_priority).html(obj.message).css('color', 'red').delay(3000).fadeOut().css('display', 'block')
                            }
                            else {
                                additional_id = response.message ;
                                if ( url_priority == 2 ) {
                                    // url2 new speed
                                    var link2Txt = 'Category page or services page or similar page URL';
                                }
                                else if ( url_priority == 3 ) {
                                    // url3 new speed
                                    var link2Txt = 'Product page or lead generation page or similar page URL';
                                }
                                $('#hideAddedNewUrl'+url_priority+'Form').html('<div class="web_dts"><ul class="list-group"><li class="list-group-item">Link '+url_priority+'<span class="float-right">'+link2Txt+'</span></li><li class="list-group-item">URL '+url_priority+'<span class="float-right"><a style="pointer-events: none; " id="websiteUrl'+url_priority+'" href="' + url + '" >' + url + '</a></span></li><li class="list-group-item">Desktop <span class="float-right">complete Installation</span></li><li class="list-group-item">Mobile <span class="float-right">complete Installation</span></li><li  id="lastUpdated'+url_priority+'" class="list-group-item">Last Updated <span class="float-right">complete Installation</span></li></ul><span class="btn_con_w"><a type="button" class="btn btn-danger"  style="position: relative;z-index: 1; display:block"  href="/adminpannel/script-installations.php?project=<?=base64_encode($project_id)?>" >Complete Installation</a></span></div>');
                                // =======================================================================
                                var api_key = $("meta[name='google_pagespeed_api']").attr("content") ;
                                if ( url.includes("?nospeedy") ) {
                                    var request_url = url ;
                                }
                                else {
                                    if ( url.includes("?") ) {
                                        var request_url = url+"&nospeedy" ;
                                    }
                                    else {
                                        var request_url = url+"?nospeedy" ;
                                    }
                                }
                                // get speed for desktop
                                var apiEndpoint = `https://www.googleapis.com/pagespeedonline/v5/runPagespeed?url=${encodeURIComponent(request_url)}&key=${api_key}&strategy=desktop&category=ACCESSIBILITY&category=BEST_PRACTICES&category=PERFORMANCE&category=PWA&category=SEO`;
                                fetch(apiEndpoint).then(response => {
                                    if (!response.ok) {
                                        $(".loader").hide().html("");
                                        throw new Error('Please thoroughly review your domain URL ('+url+') to ensure it is correct.');
                                    }
                                    return response.json();
                                })
                                .then(data => {
                                    // Process your data here
                                    if ( data.hasOwnProperty("lighthouseResult") ) {
                                        var lighthouseResult = data.lighthouseResult ;
                                        var requestedUrl = lighthouseResult.requestedUrl ;
                                        var finalUrl = lighthouseResult.finalUrl ;
                                        var userAgent = lighthouseResult.userAgent ;
                                        var fetchTime = lighthouseResult.fetchTime ;
                                        var environment = JSON.stringify(lighthouseResult.environment) ;
                                        var runWarnings = JSON.stringify(lighthouseResult.runWarnings) ;
                                        var configSettings = JSON.stringify(lighthouseResult.configSettings) ;
                                        var audits = JSON.stringify(lighthouseResult.audits) ;
                                        var categories = JSON.stringify(lighthouseResult.categories) ;
                                        var categoryGroups = JSON.stringify(lighthouseResult.categoryGroups) ;
                                        var i18n = JSON.stringify(lighthouseResult.i18n) ;
                                        var desktop = lighthouseResult.categories.performance.score ;
                                        desktop = Math.round(desktop * 100) ;
                                        if ( desktop > 0 ) {
                                            var additional_desktop = desktop+"/100" ;
                                            $.ajax({
                                                url: "inc/dashboard-additional-url-fetch.php",
                                                type: "post",
                                                data: {
                                                    manager_id: managerId,
                                                    additional_url: url,
                                                    website_id: boostId,
                                                    additional_id: additional_id,
                                                    requestedUrl:requestedUrl,
                                                    finalUrl:finalUrl,
                                                    userAgent:userAgent,
                                                    fetchTime:fetchTime,
                                                    environment:environment,
                                                    runWarnings:runWarnings,
                                                    configSettings:configSettings,
                                                    audits:audits,
                                                    categories:categories,
                                                    categoryGroups:categoryGroups,
                                                    i18n:i18n,
                                                    request_url:request_url,
                                                    action:"check-speed-fetch",
                                                },
                                                dataType: "JSON",
                                                beforeSend: function () {},
                                                success: function (obj) {
                                                    if (obj.status == 'done') {
                                                        var additional_desktop = obj.message.desktop ;
                                                        // now get mobile speed ===========================
                                                        var apiEndpoint = `https://www.googleapis.com/pagespeedonline/v5/runPagespeed?url=${encodeURIComponent(request_url)}&key=${api_key}&strategy=mobile&category=ACCESSIBILITY&category=BEST_PRACTICES&category=PERFORMANCE&category=PWA&category=SEO`;
                                                        fetch(apiEndpoint).then(response => {
                                                            if (!response.ok) {
                                                                throw new Error('Please thoroughly review all your URLs within the domain. Ensure there are no duplicate URLs and that all URLs are correct.');
                                                            }
                                                            return response.json();
                                                        })
                                                        .then(data => {
                                                            // Process your data here
                                                            if ( data.hasOwnProperty("lighthouseResult") ) {
                                                                var lighthouseResult = data.lighthouseResult ;
                                                                var requestedUrl = lighthouseResult.requestedUrl ;
                                                                var finalUrl = lighthouseResult.finalUrl ;
                                                                var userAgent = lighthouseResult.userAgent ;
                                                                var fetchTime = lighthouseResult.fetchTime ;
                                                                var environment = JSON.stringify(lighthouseResult.environment) ;
                                                                var runWarnings = JSON.stringify(lighthouseResult.runWarnings) ;
                                                                var configSettings = JSON.stringify(lighthouseResult.configSettings) ;
                                                                var audits = JSON.stringify(lighthouseResult.audits) ;
                                                                var categories = JSON.stringify(lighthouseResult.categories) ;
                                                                var categoryGroups = JSON.stringify(lighthouseResult.categoryGroups) ;
                                                                var i18n = JSON.stringify(lighthouseResult.i18n) ;
                                                                var mobile = lighthouseResult.categories.performance.score ;
                                                                mobile = Math.round(mobile * 100) ;
                                                                var additional_mobile = mobile+"/100" ;
                                                                $.ajax({
                                                                    url: "inc/dashboard-additional-url-fetch.php",
                                                                    method: "POST",
                                                                    data: {
                                                                        manager_id: managerId,
                                                                        additional_url: url,
                                                                        website_id: boostId,
                                                                        additional_id: additional_id,
                                                                        requestedUrl:requestedUrl,
                                                                        finalUrl:finalUrl,
                                                                        userAgent:userAgent,
                                                                        fetchTime:fetchTime,
                                                                        environment:environment,
                                                                        runWarnings:runWarnings,
                                                                        configSettings:configSettings,
                                                                        audits:audits,
                                                                        categories:categories,
                                                                        categoryGroups:categoryGroups,
                                                                        i18n:i18n,
                                                                        request_url:request_url,
                                                                        action:"check-speed-mobile-fetch",
                                                                    },
                                                                    dataType: "JSON",
                                                                    timeout: 0,
                                                                    success: function (obj) {
                                                                        if (obj.status == "done") {
                                                                            additional_mobile = obj.message.mobile ;
                                                                            $.ajax({
                                                                                type: "POST",
                                                                                url: "inc/dashboard-additional-url-fetch.php",
                                                                                data: {
                                                                                    manager_id: managerId,
                                                                                    additional_url: url,
                                                                                    website_id: boostId,
                                                                                    additional_id: additional_id,
                                                                                    url_priority: url_priority,
                                                                                    action:"manage-speed-fetch",
                                                                                },
                                                                                dataType: "JSON",
                                                                                encode: true,
                                                                            })
                                                                            .done(function (data) {
                                                                                const currentDate = new Date();
                                                                                const options = { year: 'numeric', month: 'long', day: 'numeric', hour: 'numeric', minute: 'numeric', hour12: false };
                                                                                const formattedDate = currentDate.toLocaleString('en-US', options);
                                                                                $('#hideAddedNewUrl'+url_priority+'Form').html('<div class="web_dts"><ul class="list-group"><li class="list-group-item">Link '+url_priority+'<span class="float-right">'+link2Txt+'</span></li><li class="list-group-item">URL '+url_priority+'<span class="float-right"><a style="pointer-events: none; " id="websiteUrl'+url_priority+'" href="'+url+'" >'+url+'</a></span></li><li class="list-group-item">Desktop <span class="float-right">'+additional_desktop+'</span></li><li class="list-group-item">Mobile <span class="float-right">'+additional_mobile+'</span></li><li  id="lastUpdated'+url_priority+'" class="list-group-item">Last Updated <span class="float-right">'+formattedDate+'</span></li></ul><span class="btn_con_w"><a type="button" class="btn btn-danger" style="position: relative;z-index: 1; display:block"  href="/adminpannel/script-installations.php?project=<?=base64_encode($project_id)?>" >Complete Installation</a></span></div>');
                                                                                $.ajax({
                                                                                    type: "POST",
                                                                                    url: "update_meta.php",
                                                                                    data: {
                                                                                        user_id: managerId,
                                                                                        action : 'meta_value_update',
                                                                                    },
                                                                                    dataType: "JSON",
                                                                                    encode: true,
                                                                                }) ;
                                                                            })
                                                                            .fail(function (jqXHR, textStatus) {
                                                                                console.error(jqXHR) ;
                                                                                console.error(textStatus) ;
                                                                            })
                                                                            .always(function(){
                                                                                $(".loader").hide().html("");
                                                                            });
                                                                        }
                                                                        else {

                                                                            $(".loader").hide().html("");
                                                                            swalForDashboardDomain ( "Unable to Retrieve Speed for Domain: "+url+"!" , obj.message ) ;
                                                                        }
                                                                    },
                                                                    error: function (xhr) { 
                                                                        $(".loader").hide().html("");
                                                                        script_loading++;
                                                                        console.error(xhr.statusText + xhr.responseText);
                                                                    },
                                                                    complete: function () { }
                                                                });
                                                            }
                                                            else { 
                                                                $(".loader").hide().html("");
                                                                var error = "Please thoroughly review your domain URL to ensure it is correct." ;
                                                                swalForDashboardDomain ( "Unable to Retrieve Speed for Domain: "+url+"!" , error ) ;
                                                                removeAdditionalUrlRecord(boostId,additional_id,url,url_priority) ; 
                                                            }
                                                        })
                                                        .catch(error => {
                                                            $(".loader").hide().html("");
                                                            var error = "Please thoroughly review your domain URL to ensure it is correct." ;
                                                            swalForDashboardDomain ( "Unable to Retrieve Speed for Domain: "+url+"!" , error ) ;
                                                            console.error('Fetch error:', error);
                                                            removeAdditionalUrlRecord(boostId,additional_id,url,url_priority) ;
                                                        });
                                                    }
                                                    else {
                                                        $(".loader").hide().html("");
                                                        Swal.fire({
                                                            title: 'Error!',
                                                            icon: 'error',
                                                            text: obj.message,
                                                            showDenyButton: false,
                                                            showCancelButton: false,
                                                            allowOutsideClick: false,
                                                            allowEscapeKey: false,
                                                            confirmButtonText: 'Close',
                                                        }).then((result) => {
                                                            if (result.isConfirmed) {}
                                                        }) ;
                                                    }
                                                },
                                                error: function (xhr, status, error) {
                                                    console.error(xhr.responseText);
                                                },
                                                complete:function() {}
                                            });
                                        }
                                        else {
                                            $(".loader").hide().html("");
                                            var error = "Please thoroughly review your domain URL to ensure it is correct." ;
                                            swalForDashboardDomain ( "Unable to Retrieve Speed for Page URL: "+url+"!" , error ) ;
                                            removeAdditionalUrlRecord(boostId,additional_id,url,url_priority) ;
                                        }
                                    }
                                    else {
                                        $(".loader").hide().html("");
                                        var error = "Please thoroughly review your domain URL to ensure it is correct." ;
                                        swalForDashboardDomain ( "Unable to Retrieve Speed for Page URL: "+url+"!" , error ) ;
                                        removeAdditionalUrlRecord(boostId,additional_id,url,url_priority) ;
                                    }
                                })
                                .catch(error => {
                                    $(".loader").hide().html("");
                                    console.error('Fetch error:', error);
                                    removeAdditionalUrlRecord(boostId,additional_id,url,url_priority) ;
                                    swalForDashboardDomain ( "Unable to Retrieve Speed for Page URL: "+url+"!" , error ) ;
                                });
                            }
                        },
                        error: function (xhr, status, error) {
                            console.error(xhr.responseText);
                            $(".loader").show().html("");
                        }
                    });
                }
                else {
                   var expurl2 = websiteUrl + 'abc';
                    $('.subDomainUrl'+url_priority+'Error').html('Please enter domain page url').css('color', 'red').delay(3000).fadeOut().css('display', 'block');
                    is_valid = false; 
                }
            }
            else {
                var expurl2 = websiteUrl + 'abc';
                $('.subDomainUrl'+url_priority+'Error').html('Please enter domain page url').css('color', 'red').delay(3000).fadeOut().css('display', 'block');
                is_valid = false;
            }
        }
    });
});
function reassignSubmitFuntionality(){
    $(".additionalUrlForm").on("submit", function(e){
        e.preventDefault();
        var is_valid = true;
        var managerId = <?=$user_id;?> ;
        var boostId = <?=$project_id;?> ;
        var websiteName = '<?=$website_data['website_name'];?>';
        var websiteUrl = '<?=$website_data['website_url'];?>';
        if ( $(this).attr("id") == "addedNewUrl2" ) {
            var url_priority = 2 ;
            var url = $('#newUrl2').val();
        }
        else {
            var url_priority = 3 ;
            var url = $('#newUrl3').val();
        }
        $('.errroUrl'+url_priority).html('');
        $('.subDomainUrl'+url_priority+'Error').html('');
        $('#sameEnterUrl'+url_priority).html('');
        if (url == '') {
            $('.errroUrl'+url_priority).html('Please enter valid url').css('color', 'red').delay(3000).fadeOut().css('display', 'block');
            is_valid = false;
        }
        else {
            var check_url = compareAndStoreSubdomain(websiteUrl, url);
            if (check_url) {
                is_valid = true;
                // loader
                $(".loader").show().html("<div class='loader_s 123 devdev'><dotlottie-player src='https://lottie.host/ce2baafe-2f55-4b3f-8042-d3b04d48212c/FV1UcE5Bkc.lottie'  background='transparent'  speed='1'  style='width: 300px; height: 300px;' loop autoplay></dotlottie-player><p><span class='auto-type'></span></p></div>");
                loaderest();
                var additional_id = 0 ;
                $.ajax({
                    url: "inc/dashboard-additional-url-fetch.php",
                    type: "POST",
                    data: {
                        user_id: managerId,
                        website_id: boostId,
                        additional_url: url,
                        website_name: websiteName,
                        website_url: websiteUrl,
                        url_priority: url_priority,
                        action: "add-additional-url",
                    },
                    dataType:"JSON",
                    success: function (response) {
                        if ( response.status == "error" ) {
                            $(".loader").hide().html("");
                            var title = "Error in URL"+url_priority ;
                            var content = response.message ;
                            swalForDashboardDomain(title,content) ;
                            $('#sameEnterUrl'+url_priority).html(obj.message).css('color', 'red').delay(3000).fadeOut().css('display', 'block')
                        }
                        else {
                            additional_id = response.message ;
                            if ( url_priority == 2 ) {
                                // url2 new speed
                                var link2Txt = 'Category page or services page or similar page URL';
                            }
                            else if ( url_priority == 3 ) {
                                // url3 new speed
                                var link2Txt = 'Product page or lead generation page or similar page URL';
                            }
                            $('#hideAddedNewUrl'+url_priority+'Form').html('<div class="web_dts"><ul class="list-group"><li class="list-group-item">Link '+url_priority+'<span class="float-right">'+link2Txt+'</span></li><li class="list-group-item">URL '+url_priority+'<span class="float-right"><a style="pointer-events: none; " id="websiteUrl'+url_priority+'" href="' + url + '" >' + url + '</a></span></li><li class="list-group-item">Desktop <span class="float-right">complete Installation</span></li><li class="list-group-item">Mobile <span class="float-right">complete Installation</span></li><li  id="lastUpdated'+url_priority+'" class="list-group-item">Last Updated <span class="float-right">complete Installation</span></li></ul><span class="btn_con_w"><a type="button" class="btn btn-danger"  style="position: relative;z-index: 1; display:block"  href="/adminpannel/script-installations.php?project=<?=base64_encode($project_id)?>" >Complete Installation</a></span></div>');
                            // =======================================================================
                            var api_key = $("meta[name='google_pagespeed_api']").attr("content") ;
                            if ( url.includes("?nospeedy") ) {
                                var request_url = url ;
                            }
                            else {
                                if ( url.includes("?") ) {
                                    var request_url = url+"&nospeedy" ;
                                }
                                else {
                                    var request_url = url+"?nospeedy" ;
                                }
                            }
                            // get speed for desktop
                            var apiEndpoint = `https://www.googleapis.com/pagespeedonline/v5/runPagespeed?url=${encodeURIComponent(request_url)}&key=${api_key}&strategy=desktop&category=ACCESSIBILITY&category=BEST_PRACTICES&category=PERFORMANCE&category=PWA&category=SEO`;
                            fetch(apiEndpoint).then(response => {
                                if (!response.ok) {
                                    $(".loader").hide().html("");
                                    throw new Error('Please thoroughly review your domain URL ('+url+') to ensure it is correct.');
                                }
                                return response.json();
                            })
                            .then(data => {
                                // Process your data here
                                if ( data.hasOwnProperty("lighthouseResult") ) {
                                    var lighthouseResult = data.lighthouseResult ;
                                    var requestedUrl = lighthouseResult.requestedUrl ;
                                    var finalUrl = lighthouseResult.finalUrl ;
                                    var userAgent = lighthouseResult.userAgent ;
                                    var fetchTime = lighthouseResult.fetchTime ;
                                    var environment = JSON.stringify(lighthouseResult.environment) ;
                                    var runWarnings = JSON.stringify(lighthouseResult.runWarnings) ;
                                    var configSettings = JSON.stringify(lighthouseResult.configSettings) ;
                                    var audits = JSON.stringify(lighthouseResult.audits) ;
                                    var categories = JSON.stringify(lighthouseResult.categories) ;
                                    var categoryGroups = JSON.stringify(lighthouseResult.categoryGroups) ;
                                    var i18n = JSON.stringify(lighthouseResult.i18n) ;
                                    var desktop = lighthouseResult.categories.performance.score ;
                                    desktop = Math.round(desktop * 100) ;
                                    if ( desktop > 0 ) {
                                        var additional_desktop = desktop+"/100" ;
                                        $.ajax({
                                            url: "inc/dashboard-additional-url-fetch.php",
                                            type: "post",
                                            data: {
                                                manager_id: managerId,
                                                additional_url: url,
                                                website_id: boostId,
                                                additional_id: additional_id,
                                                requestedUrl:requestedUrl,
                                                finalUrl:finalUrl,
                                                userAgent:userAgent,
                                                fetchTime:fetchTime,
                                                environment:environment,
                                                runWarnings:runWarnings,
                                                configSettings:configSettings,
                                                audits:audits,
                                                categories:categories,
                                                categoryGroups:categoryGroups,
                                                i18n:i18n,
                                                request_url:request_url,
                                                action:"check-speed-fetch",
                                            },
                                            dataType: "JSON",
                                            beforeSend: function () {},
                                            success: function (obj) {
                                                if (obj.status == 'done') {
                                                    var additional_desktop = obj.message.desktop ;
                                                    // now get mobile speed ===========================
                                                    var apiEndpoint = `https://www.googleapis.com/pagespeedonline/v5/runPagespeed?url=${encodeURIComponent(request_url)}&key=${api_key}&strategy=mobile&category=ACCESSIBILITY&category=BEST_PRACTICES&category=PERFORMANCE&category=PWA&category=SEO`;
                                                    fetch(apiEndpoint).then(response => {
                                                        if (!response.ok) {
                                                            throw new Error('Please thoroughly review all your URLs within the domain. Ensure there are no duplicate URLs and that all URLs are correct.');
                                                        }
                                                        return response.json();
                                                    })
                                                    .then(data => {
                                                        // Process your data here
                                                        if ( data.hasOwnProperty("lighthouseResult") ) {
                                                            var lighthouseResult = data.lighthouseResult ;
                                                            var requestedUrl = lighthouseResult.requestedUrl ;
                                                            var finalUrl = lighthouseResult.finalUrl ;
                                                            var userAgent = lighthouseResult.userAgent ;
                                                            var fetchTime = lighthouseResult.fetchTime ;
                                                            var environment = JSON.stringify(lighthouseResult.environment) ;
                                                            var runWarnings = JSON.stringify(lighthouseResult.runWarnings) ;
                                                            var configSettings = JSON.stringify(lighthouseResult.configSettings) ;
                                                            var audits = JSON.stringify(lighthouseResult.audits) ;
                                                            var categories = JSON.stringify(lighthouseResult.categories) ;
                                                            var categoryGroups = JSON.stringify(lighthouseResult.categoryGroups) ;
                                                            var i18n = JSON.stringify(lighthouseResult.i18n) ;
                                                            var mobile = lighthouseResult.categories.performance.score ;
                                                            mobile = Math.round(mobile * 100) ;
                                                            var additional_mobile = mobile+"/100" ;
                                                            $.ajax({
                                                                url: "inc/dashboard-additional-url-fetch.php",
                                                                method: "POST",
                                                                data: {
                                                                    manager_id: managerId,
                                                                    additional_url: url,
                                                                    website_id: boostId,
                                                                    additional_id: additional_id,
                                                                    requestedUrl:requestedUrl,
                                                                    finalUrl:finalUrl,
                                                                    userAgent:userAgent,
                                                                    fetchTime:fetchTime,
                                                                    environment:environment,
                                                                    runWarnings:runWarnings,
                                                                    configSettings:configSettings,
                                                                    audits:audits,
                                                                    categories:categories,
                                                                    categoryGroups:categoryGroups,
                                                                    i18n:i18n,
                                                                    request_url:request_url,
                                                                    action:"check-speed-mobile-fetch",
                                                                },
                                                                dataType: "JSON",
                                                                timeout: 0,
                                                                success: function (obj) {
                                                                    if (obj.status == "done") {
                                                                        additional_mobile = obj.message.mobile ;
                                                                        $.ajax({
                                                                            type: "POST",
                                                                            url: "inc/dashboard-additional-url-fetch.php",
                                                                            data: {
                                                                                manager_id: managerId,
                                                                                additional_url: url,
                                                                                website_id: boostId,
                                                                                additional_id: additional_id,
                                                                                url_priority: url_priority,
                                                                                action:"manage-speed-fetch",
                                                                            },
                                                                            dataType: "JSON",
                                                                            encode: true,
                                                                        })
                                                                        .done(function (data) {
                                                                            // Create a new Date object
                                                                            const currentDate = new Date();
                                                                            // Define options for formatting with 24-hour time
                                                                            const options = { year: 'numeric', month: 'long', day: 'numeric', hour: 'numeric', minute: 'numeric', hour12: false };
                                                                            // Format the date using toLocaleString
                                                                            const formattedDate = currentDate.toLocaleString('en-US', options);
                                                                            $('#hideAddedNewUrl'+url_priority+'Form').html('<div class="web_dts"><ul class="list-group"><li class="list-group-item">Link '+url_priority+'<span class="float-right">'+link2Txt+'</span></li><li class="list-group-item">URL '+url_priority+'<span class="float-right"><a style="pointer-events: none; " id="websiteUrl'+url_priority+'" href="'+url+'" >'+url+'</a></span></li><li class="list-group-item">Desktop <span class="float-right">'+additional_desktop+'</span></li><li class="list-group-item">Mobile <span class="float-right">'+additional_mobile+'</span></li><li  id="lastUpdated'+url_priority+'" class="list-group-item">Last Updated <span class="float-right">'+formattedDate+'</span></li></ul><span class="btn_con_w"><a type="button" class="btn btn-danger" style="position: relative;z-index: 1; display:block"  href="/adminpannel/script-installations.php?project=<?=base64_encode($project_id)?>" >Complete Installation</a></span></div>');
                                                                            $.ajax({
                                                                                type: "POST",
                                                                                url: "update_meta.php",
                                                                                data: {
                                                                                    user_id: managerId,
                                                                                    action : 'meta_value_update',
                                                                                },
                                                                                dataType: "JSON",
                                                                                encode: true,
                                                                            }) ;
                                                                        })
                                                                        .fail(function (jqXHR, textStatus) {
                                                                            console.error(jqXHR) ;
                                                                            console.error(textStatus) ;
                                                                        })
                                                                        .always(function(){
                                                                            $(".loader").hide().html("");
                                                                        });
                                                                    }
                                                                    else {
                                                                        $(".loader").hide().html("");
                                                                        swalForDashboardDomain ( "Unable to Retrieve Speed for Domain: "+url+"!" , obj.message ) ;
                                                                    }
                                                                },
                                                                error: function (xhr) { 
                                                                    $(".loader").hide().html("");
                                                                    script_loading++;
                                                                    console.error(xhr.statusText + xhr.responseText);
                                                                },
                                                                complete: function () { }
                                                            });
                                                        }
                                                        else {
                                                            $(".loader").hide().html("");
                                                            var error = "Please thoroughly review your domain URL to ensure it is correct." ;
                                                            swalForDashboardDomain ( "Unable to Retrieve Speed for Domain: "+url+"!" , error ) ;
                                                            removeAdditionalUrlRecord(boostId,additional_id,url,url_priority) ;
                                                        }
                                                    })
                                                    .catch(error => {
                                                        $(".loader").hide().html("");
                                                        var error = "Please thoroughly review your domain URL to ensure it is correct." ;
                                                        swalForDashboardDomain ( "Unable to Retrieve Speed for Domain: "+url+"!" , error ) ;
                                                        console.error('Fetch error:', error);
                                                        removeAdditionalUrlRecord(boostId,additional_id,url,url_priority) ;
                                                    });
                                                }
                                                else {
                                                    $(".loader").hide().html("");
                                                    Swal.fire({
                                                        title: 'Error!',
                                                        icon: 'error',
                                                        text: obj.message,
                                                        showDenyButton: false,
                                                        showCancelButton: false,
                                                        allowOutsideClick: false,
                                                        allowEscapeKey: false,
                                                        confirmButtonText: 'Close',
                                                    }).then((result) => {
                                                        if (result.isConfirmed) {}
                                                    }) ;
                                                }
                                            },
                                            error: function (xhr, status, error) {
                                                console.error(xhr.responseText);
                                            },
                                            complete:function() {}
                                        });
                                    }
                                    else {
                                        $(".loader").hide().html("");
                                        var error = "Please thoroughly review your domain URL to ensure it is correct." ;
                                        swalForDashboardDomain ( "Unable to Retrieve Speed for Page URL: "+url+"!" , error ) ;
                                        removeAdditionalUrlRecord(boostId,additional_id,url,url_priority) ;
                                    }
                                }
                                else {
                                    $(".loader").hide().html("");
                                    var error = "Please thoroughly review your domain URL to ensure it is correct." ;
                                    swalForDashboardDomain ( "Unable to Retrieve Speed for Page URL: "+url+"!" , error ) ;
                                    removeAdditionalUrlRecord(boostId,additional_id,url,url_priority) ;
                                }
                            })
                            .catch(error => {
                                $(".loader").hide().html("");
                                console.error('Fetch error:', error);
                                removeAdditionalUrlRecord(boostId,additional_id,url,url_priority) ;
                                swalForDashboardDomain ( "Unable to Retrieve Speed for Page URL: "+url+"!" , error ) ;
                            });
                        }
                    },
                    error: function (xhr, status, error) {
                        console.error(xhr.responseText);
                        $(".loader").hide().html("");
                    }
                });
            }
            else {
                var expurl2 = websiteUrl + 'abc';
                $('.subDomainUrl'+url_priority+'Error').html('Please enter domain page url').css('color', 'red').delay(3000).fadeOut().css('display', 'block');
                is_valid = false;
            }
        }
    });
}
function removeAdditionalUrlRecord(website_id,additional_id,additional_url,url_type=2){
    $.ajax({
        type: "POST",
        url: "inc/dashboard-additional-url-fetch.php",
        data: {
            website_id: website_id,
            additional_id: additional_id,
            action:"remove-additional-url",
        },
        dataType: "JSON",
        encode: true,
    })
    .done(function (data) {
        console.log(data) ;
    })
    .fail(function (jqXHR, textStatus) {
        console.error(jqXHR) ;
        console.error(textStatus) ;
    })
    .always(function(){
        if ( url_type == 2 ) {
            $("#hideAddedNewUrl2Form").html('<div class="" style="position: relative; z-index:1"><form id="addedNewUrl2" class="additionalUrlForm" method="post"><div class="web_dts"><ul class="list-group"><li class="list-group-item">Link 2<span class="float-right">Category page or services page or similar page URL</span></li><li class="list-group-item">URL 2<span class="float-right com_inst"><input type="text" name="newUrl2" id="newUrl2" onblur="removeHashAndQueryParams(this);" placeholder="Enter Url 2" value="'+additional_url+'"><span class="btn_con_w"><button type="submit" name="addedNewUrl2Btn" id="addedNewUrl2Btn" class="btn btn-danger">OK</button></span></span></li><li class="list-group-item">Desktop <span class="float-right">Enter Url 2</span></li><li class="list-group-item">Mobile <span class="float-right">Enter Url 2</span></li><li class="list-group-item">Last Updated  <span class="float-right">Enter Url 2</span></li></ul><div class="errors"><span class="errroUrl2"></span><span class="subDomainUrl2Error"></span><span id="sameEnterUrl2"></span></div></div></form></div>') ;
        }
        else if ( url_type == 3 ) {
            $("#hideAddedNewUrl3Form").html('<div class="" style="position: relative; z-index:1"><form id="addedNewUrl3" class="additionalUrlForm" method="post"><div class="web_dts"><ul class="list-group"><li class="list-group-item">Link 3<span class="float-right">Product page or lead generation page or similar page URL</span></li><li class="list-group-item">URL 3<span class="float-right com_inst"><input type="text" name="newUrl3" id="newUrl3" onblur="removeHashAndQueryParams(this);" placeholder="Enter Url 3" value="'+additional_url+'"><span class="btn_con_w"><button type="submit" name="addedNewUrl3Btn" id="addedNewUrl3Btn" class="btn btn-danger">OK</button></span></span></li><li class="list-group-item">Desktop <span class="float-right">Enter Url 3</span></li><li class="list-group-item">Mobile <span class="float-right">Enter Url 3</span></li><li class="list-group-item">Last Updated  <span class="float-right">Enter Url 3</span></li></ul><div class="errors"><span class="errroUrl3"></span><span class="subDomainUrl3Error"></span><span id="sameEnterUrl3"></span> </div></div></form></div>') ;
        }
        reassignSubmitFuntionality() ;
    });
}
</script>
<script type="text/javascript">
function setDateTime(input) {
    let val = $(input).val() ;
    let id = $(input).attr("id") ;
    $("input[name='"+id+"']").val(val) ;
}
function ValidateEmail(mail){
    if(mail.indexOf("@") >0 && mail.indexOf(".") >0 ){
        return 1;
    }
    return 0;
}
function rmaSubmit() {
    setTimeout(function(){
        var days = 7;
        var date = new Date();
        var res = date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
        var afterDate = new Date(res);
        var month = afterDate.getUTCMonth() + 1;
        var day = afterDate.getUTCDate();
        var year = afterDate.getUTCFullYear();
        var dayss = 3;
        var ress = afterDate.setTime(date.getTime() + (dayss * 24 * 60 * 60 * 1000));
        var afterDatess = new Date(ress);
        var months = afterDatess.getUTCMonth() + 1; 
        var days = afterDatess.getUTCDate();
        var years = afterDatess.getUTCFullYear();
        var start_date = day + '-' + month + '-' + year;
        var end_date = days + '-' + months + '-' + years;
        var cc = document.getElementById('country-code').value;
        var contact = document.getElementById('contact').value;
        var suitable = $("input[name='suitable-time']").val();
        var form = $("input[name='time-form']").val();
        var to = $("input[name='time-to']").val();
        var email = document.getElementById('email').value;
        var tz = document.getElementById('tz').value;
        var e = ValidateEmail(email);
        if (cc != "" && contact != "" && form != "" && suitable != "" && to != "" && e == 1 && tz != "") {
            var from_tab_btn = 4;
            $.ajax({
                url: "generate-script-save-request.php",
                type: "POST",
                dataType: "json",
                data: {
                    start_date: start_date,
                    end_date: end_date,
                    platform: "",
                    traffic: "",
                    email: email,
                    country_code: cc,
                    contact: contact,
                    suitable: suitable,
                    from: form,
                    to: to,
                    tz: tz,
                    step: from_tab_btn,
                    script: '<?=serialize($script_url)?>',
                    website_url: '<?=$website_data['website_url']?>',
                    id: '<?=$_GET["project"]?>'
                },
                beforeSend: function () {
                    $(".loader").show().html("<div class='loader_s rty'><dotlottie-player src='https://lottie.host/ce2baafe-2f55-4b3f-8042-d3b04d48212c/FV1UcE5Bkc.lottie'  background='transparent'  speed='1'  style='width: 300px; height: 300px;' loop autoplay></dotlottie-player><p id='loader_text'>Building your request. It might take few seconds</p><p><span class='auto-type'></span></p></div>");
                    loaderest() ;
                },
                success: function (data) {
                    console.log(data);
                    if (data == 'saved') {
                        var userEmailId = '<?=$row['email']?>';
                        var support_subject = "Manual Audit By Team" ;
                        var message = `
                            Need to install website speedy script and parameters for my website
                            website_url : <?=$website_data['website_url']?> 
                            Contact Number : ${cc} ${contact} 
                            Email : ${email} 
                            Time Zone : ${tz} 
                            From  : ${form} 
                            To : ${to} 
                        `;
                        $.ajax({
                            url: "<?=HOST_HELP_URL?>actions/user/support/create_ticket1",
                            type: "post",
                            data: {
                                'subject': support_subject,
                                'priority': 'medium',
                                'department': '2',
                                'userEmail': userEmailId,
                                'message': message,
                            },
                            success: function (response) {
                                console.log(response);
                                var obj = $.parseJSON(response);
                                console.log(obj.data);
                                if (obj.status == 1) {
                                    $(".loader").hide();
                                    $.ajax({
                                        url: "inc/help-support-tickets.php",
                                        type: "post",
                                        data: {
                                            action: "insert-support-ticket",
                                            website_id: <?=$project_id?> ,
                                            ticket_type: support_subject,
                                            ticket_id: obj.data
                                        },
                                        dataType: "JSON",
                                        success: function (response) {
                                            console.log(response);
                                            swal.close() ;
                                            var d_rma_view = '<a href="<?=HOST_HELP_URL?>user/support/ticket/'+obj.data+'" class="d-rma-view btn btn-primary" target="_blank">View Ticket</a>' ;
                                            $(".d-rma-request").each(function(i,o){
                                                let p = $(o).parent() ;
                                                $(p).html(d_rma_view);
                                            })
                                        },
                                        error: function (response) {
                                            console.log(response)
                                        },
                                    });
                                }
                                else {
                                    $(".loader").hide();
                                    console.error(obj.message)
                                }
                            },
                            error: function(){
                                $(".loader").hide();
                            },
                            complete: function(){ $(".loader").hide();}
                        });
                    }
                    else if (data == 2) {
                        $(".loader").hide().html("");
                        Swal.fire(
                            'Already Saved',
                            'Your Request is already saved.',
                            'info'
                        )
                    }
                    else {
                        $(".loader").hide().html("");
                        Swal.fire(
                            'Opps..',
                            'Something went wrong.',
                            'error'
                        )
                    }
                },
                error: function (argument) {
                    $(".loader").hide().html("");
                }
            });
        }
        else {
            $(".request-err").removeClass("d-none");
            setTimeout(function () {
                $(".request-err").addClass("d-none");
            }, 4000);
        }
    },1000);
}
$(document).ready(function () {
    $(".d-rma-request").click(function () {
        const {value: formValues} = Swal.fire({
            title: 'Please confirm your contact details and a Suitable time to get in touch',
            html: $("#rma-content").html(),
            focusConfirm: false,
            showCloseButton: true,
            allowOutsideClick: false,
            showConfirmButton: false,
            allowEscapeKey: false,
            preConfirm: () => {
                return false;
            }
        })
        if (formValues) {
            Swal.fire(JSON.stringify(formValues))
        }
    });
});
</script>
</html>
