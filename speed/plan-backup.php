<?php 
include('session.php');

require_once("adminpannel/config.php") ;
require_once('adminpannel/inc/functions.php') ;


if ( !checkUserLogin() ) {
    header("location: ".HOST_URL."signup.php") ;
    die() ;
}

$subsc_id = base64_decode($_REQUEST['sid']);
$subsc_id_url = $_REQUEST['sid'];

$plan_type="";
$plan_id=0;
$subs_id = "";

    $user_id = $_SESSION['user_id'];
      $websites = getTableData( $conn , " user_subscriptions " , " id = '".$subsc_id."' " , "" , 1 );
 
 if(count($websites) > 0){
       
       $plan_id=$websites[0]['plan_id'];
       $subs_id = base64_encode($websites[0]['id']);
        $plan_type="subscription"; 

}
     
    $_SESSION["siteId"] = "" ;
    $user_status = $websites[0]['status'];


$subsfree = getTableData( $conn , " user_subscriptions_free " , " user_id ='".$_SESSION['user_id']."' " ) ;

if($subsfree['status'] ==1 ){
   $plan_type="Free"; 
   $plan_id=$subsfree['plan_id'];
}

// echo $plan_type;
 
?>
<html>
    <head>
        <title>Admin Dashboard</title>
        
        <link rel="stylesheet" type="text/css" href="adminpannel/style.css">
        <link rel="stylesheet" type="text/css" href="css/slick.css">
        <link rel="stylesheet" type="text/css" href="css/slick-theme.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css">
        <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
        <script src="js/jquery.js"></script>
        <script src="js/slick.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.min.js" ></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js" ></script>
        <?php include('common-css-js-home.php'); ?>
    </head>
 <body>
    <div class="whole-cover dashboard">
        <!-- Tab Nav -->    
         
        <input type="hidden" id="shop__url" value="">
        <!--------------Tab Content 1-------------------->
        <div id="tab-1" class="tab-content current">
            <!-- For Select Option Code -->
            <div class="padding-top">
                <div class="Polaris-Labelled__LabelWrapper">
                    
                    <div class="Polaris-Label">
                        <label id="Select1Label" for="Select1" class="Polaris-Label__Text ">Choose subscription plans</label>
                    </div>


                </div>

                <div class="Polaris-Labelled__LabelWrapper">

                    <div class="Polaris-wrapper">
                        <div class="Polaris-card">
                            <div class="app-setting">
                               
                                <label class="switch">
                                  <input type="checkbox" name="is_enable" class="is_enable" id="is_enable">
                                  <span class="slider round"></span>
                                </label>
                            </div>
                        </div> 
                    </div> 
 
                </div>               
                <?php require_once('inc/alert-message.php') ; ?>
                <!--**************** Start: Subscription Plan3 *****************-->
                <div class="makk-page-wrapper plan-page">
                    <div class="makk-wrapper-card">
                        <div class="plan-items">
                        <?php

                            $user_subscription_id = $plan_id ;
                            
                            $query = $conn->query(" SELECT * FROM `plans` WHERE status = 1 ") ;

                            if($query->num_rows > 0) 
                            {
                                $i = 1;
                                while($data = $query->fetch_assoc() ) 
                                {
                                    if($data['id'] ==$user_subscription_id){ $status = 'active'; } 

                                    ?>
                                     
                                    <div class="Polaris-Card <?php if($data['id'] == $user_subscription_id && $status == 'active'){ echo 'active'; } ?>">
                                        <div class="Polaris-Card__Section">
                                            <div class="top-sec-card">
                                                <h2 class="plan-name">
                                                    <?php echo $data['s_type']; ?>
                                                    <?php 
                                                    if( $data['id'] == $user_subscription_id && $status == 'active'  && $plan_type == "Free"  )
                                                        { echo '<span>Current Free Plan</span>'; }
                                                    elseif($data['id'] == $user_subscription_id && $status == 'active' ){ echo '<span>Current Plan</span>'; } ?> 
                                                </h2>

                                                <?php 
                                                if($data['s_price'] != "") {
                                                    ?>
                                                    <div class="price-tag"><span class="symbol"><?php if($i!=0){?>$<?php }?></span><span class="amount" subs="<?php echo $data['id'];?>"><?php echo $data['s_price'];  ?></span> <span class="after"><?php if($i!=0){?><span class="month-slash" >/</span>month<?php }?></span></div>
                                                    <?php 
                                                } 
                                                ?>
                                            </div>
                                          
                                            <ul>
                                               <li><?php echo number_format($data['page_view']); ?>  PAGEVIEWS /MONTH</li>

                                                <?php
                                                    if($data['s_type'] == "Silver" || $data['s_type'] == "Gold" || $data['s_type'] == "Diamond" || $data['s_type'] == "Pro"){
                                                    ?>
                                                <li>Full Site Optimization</li>
                                                <?php
                                                    }
                                                    if($data['s_type'] == "Silver" || $data['s_type'] == "Gold" || $data['s_type'] == "Diamond" || $data['s_type'] == "Pro"){
                                                    ?>
                                                <li>Easy Manual For Adding Code</li>
                                                <?php
                                                    }
                                                    if($data['s_type'] == "Silver"){
                                                    ?>
                                                <li>Boost 3X Speed</li>
                                                <?php
                                                    }
                                                    
                                                    if($data['s_type'] == "Gold" || $data['s_type'] == "Diamond" || $data['s_type'] == "Pro"){
                                                    ?>
                                                <li>Boost 3X Speed</li>
                                                <?php
                                                    }
                                                    
                                                    if($data['s_type'] == "Gold" || $data['s_type'] == "Diamond" || $data['s_type'] == "Pro"){
                                                    ?>
                                                <li>Expert Help</li>
                                                <li>Other Help</li>
                                                <?php
                                                    }
                                                    
                                                    if($data['s_type'] == "Free"){
                                                    ?>
                                                <li><?=$data['s_duration']?> days free trial</li>
                                                <?php
                                                    }

                                                    if($data['s_type'] == "Diamond" || $data['s_type'] == "Pro"){
                                                    ?>
                                                <li>Help Support</li>
                                                <?php
                                                    }
                                                    
                                                    ?>
                                            </ul>
                                        </div>

                                        <div style="--top-bar-background:#00848e; --top-bar-background-lighter:#1d9ba4; --top-bar-color:#f9fafb; --p-frame-offset:0px;">
                                            <form method="POST" action="/ecommercespeedy/payment/">
                                            <?php 
                                                if($data['id'] == $user_subscription_id && $status == 'active' && $plan_type != "Free"  ) { 
                                                ?>
                                                <?php  echo '<span>Plan Activated For '.$websites[0]['site_count'].' Sites.</span>'; ?>
                                            <a  data-plan-id="<?php echo $data['id']; ?>" href="javascript:void(0)" class="Polaris-Button activated__plan"><span class="Polaris-Button__Content"><span class="Polaris-Button__Text">Activated</span></span></a>

                                               
                                            <div class="form-group addon" style="display: none;">
                                                <label for="count_site">Number of sites</label>
                                                <input type="text" onkeypress='return /[0-9]/i.test(event.key)' class="Polaris-text count_site" id="count_site" name="count_site" placeholder="Number of sites" sites="<?php echo $websites[0]['site_count']; ?>" value="<?php echo $websites[0]['site_count']; ?>" required="">
                                            <a data-plan-id="<?php echo $data['id']; ?>" class="Polaris-Button upgrade_plan">
                                                      <span class="Polaris-Button__Text">Upgrade</span>
                                            </a>


                                            </div>    
                                    

                                            <a style="display: none;"> data-plan-id="<?php echo $data['id']; ?>"  class="Polaris-Button add_site"><span class="Polaris-Button__Content"><span class="Polaris-Button__Text">+</span></span></a>


                                            <?php 
                                                }
                                                else 
                                                { 
                                                
                                                if($data['s_type'] == "Free" ) {
                                                ?> 
                                            <a href="<?=HOST_URL?>get-free-trial.php?plan=<?=$data['id']?>" class="Polaris-Button">
                                            <span class="Polaris-Button__Content">
                                            <span class="Polaris-Button__Text"><?php if($data['id'] == $user_subscription_id && $status == 'pending'){ echo 'Pending Plan'; }else { echo 'Subscribe'; } ?></span>
                                            <?php 
                                                } 
                                                else { 
                                                ?>
                                                <input type="hidden" name="subscription" value="<?php echo $data['id']; ?>" />

                                            <?php if($subs_id =="" ){ ?>
                                               
                   <!-- Fist time subscription -->
                                            <div class="form-group">
                                                <label for="count_site">Number of sites</label>
                                                <input type="text" onkeypress='return /[0-9]/i.test(event.key)' class="Polaris-text count_site" id="count_site" name="count_site" placeholder="Number of sites" required="" value="<?php echo $websites[0]['site_count']; ?>" >
                                            </div>    
                                    
                                            <button  data-plan-id="<?php echo $data['id']; ?>" href="<?php echo HOST_URL; ?>shopify_payment.php?plan_id=<?php echo $data['id']; ?>&shop_url=<?php echo $shop_url; ?>" class="Polaris-Button <?php if($data['id'] == $user_subscription_id && $status == 'pending'){ echo 'pending_plan'; } ?>">
                                            <span class="Polaris-Button__Content">
                                            <span class="Polaris-Button__Text"><?php if($data['id'] == $user_subscription_id && $status == 'pending'){ echo 'Pending Plan'; }else { echo 'Subscribe'; } ?></span>

                                            </span>
                                            </button>

                   <!-- Upgrade time subscription -->
                                            <?php }else{ ?>

                                           <div class="form-group">
                                            <?php  echo '<span>Plan Upgrade For '.$websites[0]['site_count'].' Sites.</span>'; ?>
                                                <!-- <label for="count_site">Number of sites</label> -->
                                                <input type="hidden" onkeypress='return /[0-9]/i.test(event.key)' class="Polaris-text count_site" id="count_site" name="count_site" placeholder="Number of sites" sites="<?php echo $websites[0]['site_count']; ?>" value="<?php echo $websites[0]['site_count']; ?>" required="">
                                            <a data-plan-id="<?php echo $data['id']; ?>" class="Polaris-Button upgrade_plan">
                                                      <span class="Polaris-Button__Text">Change Plan</span>
                                            </a>


                                            </div>    

                                    
                                            <?php } ?>
                                            <?php } ?>
                                            <?php } ?>


                                            </form>
                                        </div>

                                        <?php
                                        // echo "count=".count($subsfree);
                                 if($subsc_id == ""){
                                        if($plan_type == ""  &&  count($subsfree) <= 0 ) {

                                        ?>

                                        <a href="<?=HOST_URL?>get-free-trial.php?plan=<?=$data['id']?>" class="Polaris-Button">
                                            <span class="Polaris-Button__Content">
                                                <span class="Polaris-Button__Text">Get 7 days Free trial</span>
                                            </span>
                                        </a>
                                        <?php 
                                        }
                                        else{
                                         ?>   
                                        <a href="javascript:void(0)" class="Polaris-Button disabled">
                                            <span class="Polaris-Button__Content">
                                                <span class="Polaris-Button__Text">Free trial end</span>
                                            </span>
                                        </a>

                                         <?php
                                        }
                                  }      
                                        ?>

                                    </div>
                                   
                                    <?php 
                                    $i++;

                                }
                                
                            }
                                
                        ?>
                            <!--*************** End: Subscription plan3 ******************-->
                        </div>
                    </div>
                </div>

            </div>
        </div>
        <!------------------------------End---------------------------------->
    </div>
    <i class="fa-regular fa-angle-right"></i>
    <i class="fa-regular fa-angle-left"></i>
</body>
</html>
<script type="text/javascript">
    $(document).ready(function(){
        //alert('<?php echo $discount; ?>');
        var discount = '<?php echo $discount; ?>'.split("|");
        var am;
        $(".count_site").keyup(function(){
            var sites = $(this).attr("sites");
           var count = $(this).val(); 
            if(sites != undefined){
                console.log(sites);
                if(count < sites ){
                    // alert("downgrade");
                }
                else if (sites == count){
                    // alert("already have plan");
                }

            }

           am =  $(this).parents('.Polaris-Card ').first().find(".amount");
           var subs = am.attr("subs");

          $.ajax({
            type: "POST",
            url: '<?=HOST_URL?>calculate.php',
            data: {"count":count, "subs":subs},
            async: false,
            success: function(response)
            {
              if(response!="")
              {
                 am.html(response);
              }

            }
          });
        });

$(".count_site").keyup();
$(".upgrade_plan").click(function(){
    var c = $(this).prev().val();
    var p = $(this).attr('data-plan-id');
    // alert(c+","+p);
    window.location.href="<?=HOST_URL?>payment/update_subscription.php?pl="+p+"&cs="+c+"&sid=<?=$subs_id?>";
});

$(".add_site").click(function(){
    $(this).prev().toggle();
    $(this).hide();
});


    });
</script>

<script>
// $('.plan-items').slick({
//   dots: false,
//   infinite: false,
//   speed: 300,
//   slidesToShow: 3,
//   slidesToScroll: 1

// });

</script>