<?php 

// ini_set('display_errors', 1); ini_set('display_startup_errors', 1); error_reporting(E_ALL);

function cprint($data){ print_r($data) ; echo "<hr>" ; }

require_once('config.php');
require_once('inc/functions.php') ;
require_once('meta_details.php') ;

// cprint($_SESSION) ;

// get user detail from admin_users table
$query = $conn->query(" SELECT id , userstatus , country , flow_step , user_type , sumo_code , shareasale , phone FROM admin_users WHERE id ='".$_SESSION['user_id']."' AND userstatus LIKE '".$_SESSION['role']."' ; ") ;

if ( $query->num_rows > 0 ) {
    $user_data = $query->fetch_assoc() ;
    // cprint($user_data) ;

    // Matching user country to show plan link
    $plan_country = "";
    if($user_data['country'] != "101"){   
        $plan_country = "-us";
    }

    // get website details from boost_website table
    $query1 = $conn->query(" SELECT id , manager_id , website_name , website_url , subscription_id , plan_type FROM boost_website WHERE manager_id ='".$_SESSION['user_id']."' ; ") ;
    if ( $query1->num_rows <= 0 ){
        header("location: ".HOST_URL."customize-flow.php") ;
        die(); 
    }
    else {
        $website_data = $query1->fetch_assoc() ;
        // cprint($website_data) ;
    }

    if($user_data['flow_step']==1){
        $user_id = $_SESSION["user_id"] ;
        if ( $user_data["user_type"] == "Dealify" || $user_data["user_type"] == "AppSumo" ) {
            header("location: ".HOST_URL."adminpannel/dashboard.php");
        }
        else{
            header("location: ".HOST_URL."plan$plan_country.php?sid=".base64_encode($website_data['id']));
        }
        die() ;  
    }
}
else {
    header("location: ".HOST_URL."adminpannel/");
    die();
}


$is_cancled = 1;


require_once("error_message_bar_subscription.php");



$suco_c = 0;
if( !empty($user_data['sumo_code']) ) {
    $suco_c = 1;
}

$plan_lifetime = "";
$plan_lifetime_type = "";

$user_id = $_SESSION["user_id"] ;
$project_id = base64_decode($_GET['project']) ;

require_once("inc/style-and-script.php") ; 

?>

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
</style>

</head>

<input type="hidden" id="user_id" value="<?=$user_id;?>">
<input type="hidden" id="project_id" value="<?=$project_id;?>">
<input type="hidden" id="project_id_encode" value="<?=$_GET['project'];?>">
<input type="hidden" id="website_name" value="<?=$website_data['website_name'];?>">
<input type="hidden" id="website_url" value="<?=$website_data['website_url'];?>">

<body class="custom-tabel">

    <?php
        if( $user_data['shareasale'] == 0 ) {
            ?>
            <img src="https://www.shareasale.com/sale.cfm?tracking=<?=$user_id?>&amount=0.00&merchantID=144859&transtype=lead" width="1" height="1">
            <script src="https://www.dwin1.com/58969.js" type="text/javascript" defer="defer"></script>
            <?php
            $conn->query(" UPDATE admin_users SET shareasale = 1 WHERE `id` = '".$user_id."'; ") ;
        }
    ?>

    <div class="loader">Please Wait...</div>

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

                <h1 class="mt-4">Overview</h1>

                <?php require_once("inc/alert-status.php") ; ?>

                <div><h3>Dashboard</h3></div>

                <div class="profile_tabs dashboard_sn">
                    
                    <div class="card_con_ss">

                        <!-- <h3>Comparative Speed Analysis for 3 important pages</h3> -->
                        <div class="speed_head">
                            <h2>Old Speed </h2>
                            <h2>Updated Speed</h2>
                        </div>

                        <?php

                        $speed = 0 ;
                        $wd_speeds = [] ;

                        // get url1 / domain speed record
                        $wd_desktop = $wd_mobile = "-";
                        $datetimecon = "-";

                        // categories, mobile_categories
                        $sql = " ( SELECT id, website_id, parent_website, desktop_score, mobile_score, updated_at, no_speedy, ws_status, blank_record FROM pagespeed_report WHERE website_id = '".$project_id."' AND parent_website = '0' AND no_speedy = 1 ORDER BY id ASC LIMIT 1 ) UNION ( SELECT id, website_id, parent_website, desktop_score, mobile_score, updated_at, no_speedy, ws_status, blank_record FROM pagespeed_report WHERE website_id = '".$project_id."' AND parent_website = '0' AND no_speedy = '0' AND initial_url = '0' ORDER BY id DESC LIMIT 1 ); " ;
                        
                        $wd_query = $conn->query($sql);
                        if ($wd_query->num_rows > 0) {
                          $wd_speeds = $wd_query->fetch_all(MYSQLI_ASSOC) ;  
                        }

                        // cprint($wd_speeds);

                        if ( count($wd_speeds) > 0 ) {

                            $speed = 1 ;
                            $wd_data = $wd_speeds[0] ;

                            // $wd_categories = unserialize($wd_data["categories"]);
                            // $wd_performance = round($wd_categories["performance"]["score"] * 100, 2);
                            $wd_performance = $wd_data["desktop_score"];
                            $wd_desktop = $wd_performance."/100";
                            //abc
                            $bwd_desktop_url1 = $wd_performance;

                            // $wd_mobile_categories = unserialize($wd_data["mobile_categories"]);
                            // $wd_mobile = round($wd_mobile_categories["performance"]["score"] * 100, 2)."/100";
                            $wd_mobile = $wd_data["mobile_score"]."/100";
                            //abc
                            // $bwd_mobile_url1  = round($wd_mobile_categories["performance"]["score"] * 100, 2);

                            $timedy2 = $wd_data['updated_at'];
                            $vartime2 = strtotime($timedy2);
                            $datetimecon = date("F d, Y H:i", $vartime2);
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



                            <!-- Additional pages urls -->
                            <!-- url2 old speed --> <!-- url3 old speed -->
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

            
                                $awu2_query = $conn->query(" SELECT additional_websites.id , additional_websites.url_priority , additional_websites.website_url , additional_websites.website_name , pagespeed_report.id as speed_id , parent_website , pagespeed_report.desktop_score , pagespeed_report.mobile_score , pagespeed_report.updated_at , pagespeed_report.no_speedy FROM additional_websites LEFT JOIN pagespeed_report ON pagespeed_report.website_id = additional_websites.id AND pagespeed_report.parent_website = '$project_id' AND pagespeed_report.no_speedy= '0' WHERE additional_websites.manager_id = '$user_id' AND additional_websites.website_id = '$project_id' AND additional_websites.url_priority = $i ORDER BY speed_id ASC LIMIT 1; ");

                                if ( $awu2_query->num_rows > 0 ) {

                                    $additional_url = $awu2_query->fetch_assoc() ;
                                    
                                    $ps_desktop = $ps_mobile = "complete Installation";

                                    if ( !empty($additional_url["speed_id"]) ) {

                                        $ps_data = $additional_url ;

                                        // $ps_categories = unserialize($ps_data["categories"]) ;
                                        // $ps_performance = round($ps_categories["performance"]["score"]*100,2) ;
                                        $ps_performance = $ps_data["desktop_score"] ;

                                        $ps_desktop = $ps_performance."/100" ;
                                        //abc
                                        // $bwd_desktop_url2 = $ps_performance;

                                        // $ps_mobile_categories = unserialize($ps_data["mobile_categories"]) ;
                                        // $ps_mobile = round($ps_mobile_categories["performance"]["score"]*100,2)."/100" ;
                                        $ps_performance = $ps_data["mobile_score"] ;
                                        $ps_mobile = $ps_performance."/100" ;


                                        //abc
                                        // $bwd_mobile_url2 = round($ps_mobile_categories["performance"]["score"]*100,2); 

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

                            if ( count($wd_speeds) > 0 ) {

                                $wd_desktop_val = $wd_mobile_val = 0 ;

                                if ( array_key_exists("1", $wd_speeds) ) {

                                    $wd_data = $wd_speeds[1] ;

                                    // $wd_categories = unserialize($wd_data["categories"]) ;
                                    // $wd_performance = round($wd_categories["performance"]["score"]*100,2) ;
                                    $wd_desktop_val = $wd_data["desktop_score"] ;
                                    $wd_desktop = $wd_performance."/100" ;
                                    
                                    
                                    // $wd_mobile_categories = unserialize($wd_data["mobile_categories"]) ;
                                    // $wd_mobile_performance = round($wd_mobile_categories["performance"]["score"]*100,2) ;
                                    $wd_mobile_val = $wd_data["mobile_score"] ;
                                    $wd_mobile = $wd_mobile_performance."/100" ;

                                   
                                    $ws_status = $wd_data["ws_status"] ;
                                    $blank_record = $wd_data["blank_record"] ;

                                    if ( $ws_status == "popup" ) {

                                        if ( $blank_record == "both" ) {

                                            if ( $wd_mobile_val <= 70 ) {
                                                $wd_mobile  = '<a href="'.HOST_URL.'adminpannel/script-installations.php?project='.base64_encode($project_id).'" >complete Installation</a>'; 
                                            }

                                            if ( $wd_desktop_val <= 85 ) {
                                                $wd_desktop  = '<a href="'.HOST_URL.'adminpannel/script-installations.php?project='.base64_encode($project_id).'" >complete Installation</a>'; 
                                            }

                                        }

                                        if ( $blank_record == "mobile" && $wd_mobile_val <= 70 ) {
                                            $wd_mobile  = '<a href="'.HOST_URL.'adminpannel/script-installations.php?project='.base64_encode($project_id).'" >complete Installation</a>'; 
                                        }

                                        if ( $blank_record == "desktop" && $wd_desktop_val <= 85 ) {
                                            $wd_desktop  = '<a href="'.HOST_URL.'adminpannel/script-installations.php?project='.base64_encode($project_id).'" >complete Installation</a>'; 
                                        }

                                    }

                                    // $awd_desktop_url1 =  $wd_performance;
                                    // $awd_mobile_url1 =  round($wd_mobile_categories["performance"]["score"]*100,2);

                                    $timedy2=$wd_data['updated_at'];
                                    $vartime2 = strtotime($timedy2);
                                    $datetimecon2= date("F d, Y H:i", $vartime2);

                                
                                }

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

                                $awu2_query = $conn->query(" SELECT additional_websites.id , additional_websites.url_priority , additional_websites.website_url , additional_websites.website_name , pagespeed_report.id as speed_id , parent_website , pagespeed_report.desktop_score , pagespeed_report.mobile_score , pagespeed_report.updated_at , pagespeed_report.no_speedy FROM additional_websites LEFT JOIN pagespeed_report ON pagespeed_report.website_id = additional_websites.id AND pagespeed_report.parent_website = '$project_id' AND pagespeed_report.no_speedy= '0' WHERE additional_websites.manager_id = '$user_id' AND additional_websites.website_id = '$project_id' AND additional_websites.url_priority = $i ORDER BY speed_id ASC LIMIT 1; ");


                                if ( $awu2_query->num_rows > 0 ) {

                                    $additional_url = $awu2_query->fetch_assoc() ;

                                    if ( !empty($additional_url["speed_id"]) ) {

                                        $ps_desktop_v = $ps_mobile_v = 0 ;

                                        $ps_data = $additional_url;

                                        // $ps_categories = unserialize($ps_data["categories"]) ;
                                        // $ps_performance = round($ps_categories["performance"]["score"]*100,2) ;
                                        $ps_desktop_v = $ps_data["desktop_score"] ;
                                        $ps_desktop = $ps_desktop_v."/100" ;
                                        //abc
                                        $awd_desktop_url2 = $ps_performance;

                                        // $ps_mobile_categories = unserialize($ps_data["mobile_categories"]) ;
                                        // $ps_performance_mobile = round($ps_mobile_categories["performance"]["score"]*100,2) ;
                                        $ps_mobile_v = $ps_data["mobile_score"] ;
                                        $ps_mobile = $ps_mobile_v."/100" ;
                                        //abc
                                        // $awd_mobile_url2 = round($ps_mobile_categories["performance"]["score"]*100,2);
                                        $timedy2=$ps_data['updated_at'];
                                        //   echo $timedy2 ;
                                        $vartime2 = strtotime($timedy2);

                                        $datetimecon2= date("F d, Y H:i", $vartime2);


                                        $ws_status = $ps_data["ws_status"] ;
                                        $blank_record = $ps_data["blank_record"] ;

                                        // echo "ws_status : ".$ws_status."<br>" ;
                                        // echo "blank_record : ".$blank_record."<br>" ;

                                        if ( $ws_status == "popup" ) {

                                            if ( $blank_record == "both" ) {

                                                if ( $ps_mobile_v <= 70 ) {
                                                    $ps_mobile  = '<a href="'.HOST_URL.'adminpannel/script-installations.php?project='.base64_encode($project_id).'" >complete Installation</a>'; 
                                                }

                                                if ( $ps_desktop_v <= 85 ) {
                                                    $ps_desktop  = '<a href="'.HOST_URL.'adminpannel/script-installations.php?project='.base64_encode($project_id).'" >complete Installation</a>'; 
                                                }                                                  
                                                
                                            }

                                            if ( $blank_record == "mobile" ) {
                                                if ( $ps_mobile_v <= 70 ) {
                                                    $ps_mobile  = '<a href="'.HOST_URL.'adminpannel/script-installations.php?project='.base64_encode($project_id).'" >complete Installation</a>'; 
                                                }
                                            }

                                            if ( $blank_record == "desktop" ) {
                                                if ( $ps_desktop_v <= 85 ) {
                                                    $ps_desktop  = '<a href="'.HOST_URL.'adminpannel/script-installations.php?project='.base64_encode($project_id).'" >complete Installation</a>'; 
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

                            // print_r($website_data) ;

                            // website data of current site
                            /***
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

                                // $sele_free= " SELECT * FROM `user_subscriptions_free` WHERE `user_id` ='".$user_id."' AND `status` LIKE '1'";
                                // $sele_free1 =mysqli_query($conn,$sele_free);
                                // $sele_run2= mysqli_fetch_array($sele_free1);
                                $sele_run2 = getTableData( $conn , " user_subscriptions " , " `user_id` ='".$user_id."' AND `status` LIKE '1' " );

                                $date_expire = $sele_run2["plan_end_date"] ;
                                // $date_expire_start2 = $sele_run2["plan_start_date"] ;


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

                            <?php ***/ ?>
                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</body>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script src="<?=HOST_URL?>adminpannel/js/project-dashboard-new.js"></script>

</html>