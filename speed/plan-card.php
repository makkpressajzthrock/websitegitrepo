<?php

require_once("adminpannel/config.php") ;

if(isset($_GET['change-sid'])){
    // echo $_GET['change-sid']."aman";
}

//  $query_cp1 = $conn->query("select count(*) from `admin_users`") ;
//                                          $data_cp1 = $query_cp1->fetch_assoc();
//                                                 echo $data_cp1['count'];

$result=mysqli_query($conn,"SELECT count(*) as total from admin_users");
$data11=mysqli_fetch_assoc($result);
// echo $data['total'];
// print_r($data11);

$result2=mysqli_query($conn,"SELECT * FROM `power_plan`");
$data112=mysqli_fetch_assoc($result2);
// print_r($data112);
//123
$siteID = base64_decode($_GET['sid']);    
$changeId = base64_decode($_GET['change-sid']);    
if(($data['id']=='29' || $data['id']=='30') && (isset($_SESSION['sid']) && $_SESSION['sid'] == $siteID ) || (!empty($changeId) && ($data['id']=='29' || $data['id']=='30')))  {
   $display = 'none';
}else{
   if(empty($siteID) && ($data['id']=='29' || $data['id']=='30')){
      $display = 'none'; 
   }else{
      $display = 'block';
   }
}


?>



<div  style="display : <?=$display?>" class=" other card__plan  <?php if($data['id'] == $user_subscription_id && $status == 'active'){ echo 'active'; } ?>"  
    <?php     
                                             
        $pid=$data['id'];
        $query_cp = $conn->query("select * from `plans_functionality`  where plan_id=$pid") ;
        $data_cp = $query_cp->fetch_assoc();

        //  $query_cp1 = $conn->query("select count(*) from `admin_users`") ;
        //  $data_cp1 = $query_cp1->fetch_assoc();

        if($data['name']=='Power Plan'){

            if($data11['total']>=$data112['page_view']){
                echo "style='display:none'";
            }
        }

        //print_r($data_cp); 
    ?> 
>
    <div class="card__plan__inner__wrapper ">

    <div class="top__area" >

        <div class="area-one"></div>
        <div class="area-two"></div>
        <h4 class="plan-name">
        <span class="year_free"><?php if($plan_frequency_interval == 'year'  && $pid=='30'  ){echo "";}elseif($data['interval']=='month'){echo "<p>MOST POPULAR 50% DISCOUNT</p> ";}else{echo "<p>MOST POPULAR 50% DISCOUNT</p> ";}?></p> </span> 
                 <!-- //123 -->
                <?php  if($data['name']=='Free'){
                            echo "Basic Plan";
                    }else{
                        echo $data['name'];
                    } 
                ?>
                <?php 
                if( $data['id'] == $user_subscription_id && $status == 'active'  && $plan_type == "Free"  )
                    { echo '<span class="crnt_pln">Current Plan</span>'; }
                elseif($data['id'] == $user_subscription_id && $status == 'active' ){ echo '<span class="crnt_pln">Current Plan</span>'; } ?> 
        </h4>
        <?php if($data_cp['line15']!=null){   ?>  <p class="sub_hs"><?php echo $data_cp['line15']; ?></p> <?php } ?>
        <?php
            if($data['s_price'] != "") {
                ?>
                <div class="price-tag">
                    <div class="discount__price" >
                        <span class="symbol"><?php if($i!=0){?>₹<?php }?></span><span class="amount" subs="<?php echo $data['id'];?>"><?php echo round($data['s_price']/12);  ?></span> 
                        <span class="after month">INR<span class="month-slash">/<?php if($data['id']=='29'){?> </span>Forever</span> <?php }else{?> </span>month</span>  <?php }?>
                        <span class="after year">INR<span class="month-slash">/<?php if($data['id']=='30'){?> </span>Forever</span> <?php }else{?> </span>Month</span>  <?php }?>
                        <span class="after d-none">
                            <?php if($i!=0){?>
                            <span class="month-slash" >/</span>
                                <?php echo $data['interval'];  ?><?php }?>
                        </span> 
                        <?php if( $data['id']!='29' && $data['id']!='30'){ ?><span class="paid_ann">paid annually</span><?php } ?>
                    </div>

                   <div class="original__price">
                    <?php if( !empty($data['main_p']) && $data['main_p'] != $data['s_price'] ){   ?>
                        <del>₹<?=$data['main_p']?></del>
                        <!-- <span class="month" >/month</span>  
                        <span class="year" >/year</span>   -->
                        <span class="d-none">/<?=($data['interval'])?></span> 
                    <?php } ?>     

                    </div>
                    <div class="text__paid__anu" >Paid Annually</div>
                    <div class="price__changed" >
                    <div><span>₹ <?=number_format($data['s_price']/12, 2)?> <cs>/month</cs></span></div>
                    <div>Paid Annually <span class="main_p">₹<?=$data['main_p']?> <cs>/yr</cs></span> <span class="d-price">₹<?php echo $data['s_price'];  ?> <cs>/yr</cs></span></div> 
                    </div>

                </div>
                <?php 
            } 
        ?>

                <p class="sp_first ind"><span>Up To <?php echo $data['page_view']; ?> <span> <?php if($data_cp['line2']!=null){ ?> <?php echo $data_cp['line2']; ?><?php } ?>  <span class="q__icon">?</span><span class="text__hidden">To get an idea about your monthly page views go to your Google Analytics Dashboard.<br>
To go to page views in google analytics : Go to - Lifecycle > Engagement > Pages & Screens > Pageviews</span></p>


        <!-- trial / subscription btn -->
        <div class="subscribe_free">

            <div class="sub_free">


                <form method="POST" class="subscription_trial_form" action="/payment/init.php">
                      <!-- //123 -->
                  <?php 
                     $list_of_price = json_decode($data['list_of_price_inr']);
                   
                     if($data['s_type']=='Gold' ) { 
                        ?>
                        <div class="pr_sd  selectPricingDiv<?php echo $data['id'];?>" style="order: 0;">
                           <select name="selectPricingInr" id="selectPricingInr" class="selectPricing">
                              <option  style="display:none">Subscribe</option>
                              <?php
                                 foreach($list_of_price as $key => $row) {
                                    ?>
                                    <option value="<?=$key?>" <?php if($key=='250000'){ echo 'selected';} ?> data-planId="<?=$data['id']?>" > 
                                    <?php 
                                    if($key != 'Unlimited' && ($key=='250000')){ echo ($key/1000)."k page views";}  
                                    if($key == 'Unlimited' ){ echo "I want unlimited views"; }    ?></option>
                                    <?php  
                                 } 
                              ?>
                           </select>
                        </div>
                        <!-- //123 -->
                       
                           <a class="card__plan__button sales contactSalesBtn"   href="https://tfft.io/81r97R1" target="blank">Contact sales</a>
                        <?php 
                     } 
                  ?>
                <?php 
                if($data['id'] == $user_subscription_id && $status == 'active' && $plan_type != "Free"  ) { 
                    ?>
                        <a  data-plan-id="<?php echo $data['id']; ?>" href="javascript:void(0)" class="card__plan__button activated__plan"><span class="card__plan__button__text__wrapper"><span class="">Activated</span></span></a>

                        <a style="display: none;"> data-plan-id="<?php echo $data['id']; ?>"  class="card__plan__button add_site"><span class="card__plan__button__text__wrapper"><span class="">+</span></span></a>
                    <?php 
                }
                else 
                { 
                if($data['s_type'] == "Free" ) {
                    ?> 
                        <!-- <a href="<?=HOST_URL?>get-free-trial.php?plan=<?=$data['id']?>" class="Polaris-Button"> -->
                        <!-- //123 -->
                     <input type="hidden" name="subscription" value="<?php echo $data['id']; ?>" />
                     <input type="hidden" name="change_id" value="<?php echo base64_decode($_REQUEST['change-sid']); ?>" />
                     <input type="hidden" name="sid_id" value="<?php echo base64_decode($_REQUEST['sid']); ?>" />
                     <input type="hidden" name="website_url" value="<?php echo $sites['website_url'];?>" />
                     <input type="hidden" name="website_id" value="<?php echo $sites['id']; ?>" />
                     <input type="hidden" name="with_trial" value="0" />
                     <button type="button" class="card__plan__button start-trial-button" id="<?php echo str_replace(' ', '', strtolower($data['name'])); ?><?php echo $data['id']; ?>">
                     <span class="card__plan__button__text__wrapper">
                     <span class="">Get Started</span>
                     </span>
                     </button>
                     
                        <span class="card__plan__button__text__wrapper">
                            <!-- //123 -->
                        <span class=""><?php if($data['id'] == $user_subscription_id && $status == 'pending'){ echo 'Pending Plan'; }else { echo ''; } ?></span>
                    <?php 
                } 
                else { 

                    ?>
                    <input type="hidden" name="subscription" value="<?php echo $data['id']; ?>" />
                    <input type="hidden" name="change_id" value="<?php echo base64_decode($_REQUEST['change-sid']); ?>" />
                    <input type="hidden" name="sid_id" value="<?php echo base64_decode($_REQUEST['sid']); ?>" />
                    <input type="hidden" name="website_url" value="<?php echo $sites['website_url'];?>" />
                    <input type="hidden" name="website_id" value="<?php echo $sites['id']; ?>" />
                    <input type="hidden" name="with_trial" value="0" />

                    <?php 

                        $count_site = 1;
                        if($data['plan_frequency'] == "1 Website"){$count_site = 1;}
                        elseif($data['plan_frequency'] == "2 Websites"){$count_site = 2;}
                        elseif($data['plan_frequency'] == "3+ Websites"){$count_site = "Unlimited";}


                        if($subs_id =="" || $plan_frequency_interval != $current_plan_interval ) 
                        { 
                            ?>
                            <!-- Fist time subscription -->
                            <input type="hidden" name="count_site" value="<?php echo $count_site; ?>" />

                            <button id="<?php echo str_replace(' ', '', strtolower($data['name'])); ?><?php echo $data['id']; ?>" type="button" data-plan-id="<?php echo $data['id']; ?>" href="<?php echo HOST_URL; ?>shopify_payment.php?plan_id=<?php echo $data['id']; ?>&shop_url=<?php echo $shop_url; ?>" class="card__plan__button start-subscription-button <?php if($data['id'] == $user_subscription_id && $status == 'pending'){ echo 'pending_plan'; } ?> getStartedBtn<?=$data['id']?>">
                                <span class="card__plan__button__text__wrapper">
                                    <span class=""><?php if($data['id'] == $user_subscription_id && $status == 'pending'){ echo 'Pending Plan'; }else { echo 'Get Started'; } ?></span>
                                </span>
                            </button>

                            <?php

                            // show trial
                            if($activate_free == 1) {
                                ?>
                                    <button type="button" class="card__plan__button start-trial-button" style="display:none;">
                                        <span class="card__plan__button__text__wrapper">
                                            <span class="">Start 7 days Free trial</span>
                                        </span>
                                    </button>
                                <?php 
                            }

                            ?>

                            <!-- END Fist time subscription -->

                            <?php 
                        }
                        else{ 
                            // echo  $plan_frequency_interval;
                            if($plan_open_type=="update" && $plan_frequency == $current_plan_frequency && $plan_frequency_interval == $current_plan_interval){  
                                ?>




                                <input type="hidden" name="count_site" value="<?php echo $count_site; ?>" />

                                <button  id="<?php echo str_replace(' ', '', strtolower($data['name'])); ?><?php echo $data['id']; ?>"  data-plan-id="<?php echo $data['id']; ?>" href="<?php echo HOST_URL; ?>shopify_payment.php?plan_id=<?php echo $data['id']; ?>&shop_url=<?php echo $shop_url; ?>" class="card__plan__button <?php if($data['id'] == $user_subscription_id && $status == 'pending'){ echo 'pending_plan'; } ?> getStartedBtn<?=$data['id']?>">
                                    <span class="card__plan__button__text__wrapper">
                                        <span class=""><?php if($data['id'] == $user_subscription_id && $status == 'pending'){ echo 'Pending Plan'; }else { echo 'Get Started'; } ?></span>

                                    </span>
                                </button>                                                
                                <!--          <input type="hidden" onkeypress='return /[0-9]/i.test(event.key)' class="Polaris-text count_site" id="count_site" name="count_site" placeholder="Number of sites" sites="<?php echo $websites[0]['site_count']; ?>" value="<?php echo $websites[0]['site_count']; ?>" required="">
                                <a data-plan-id="<?php echo $data['id']; ?>" class="card__plan__button upgrade_plan">
                                <span class="">Change Plan 1</span>
                                </a> -->




                                <?php 
                            }
                            else { 
                               
                               // echo $user_subscription_id ;

                               $bw_id = base64_decode($_GET['sid']) ;

                               $bw_query = $conn->query(" SELECT id , plan_type , subscription_id FROM `boost_website` WHERE `id` = '$bw_id' ; ") ;

                               if ( $bw_query->num_rows > 0 ) {

                                  $bw_data = $bw_query->fetch_assoc() ;

                                  if ( $bw_data["plan_type"] == "Subscription" ) {

                                     $us_query = $conn->query(" SELECT id , stripe_subscription_id FROM `user_subscriptions` WHERE `id` = '".$bw_data["subscription_id"]."' ; ") ;

                                     if ( $us_query->num_rows > 0 ) {

                                        $us_data = $us_query->fetch_assoc() ;

                                        if ( ($us_data["stripe_subscription_id"] != "xxxxxxxxxxxx") || (strpos($us_data["stripe_subscription_id"], "xxxxxx") !== FALSE) ) {

                                           ?>
                                           <input type="hidden" name="count_site" value="<?php echo $count_site; ?>" />
                                           <button  id="<?php echo str_replace(' ', '', strtolower($data['name'])); ?><?php echo $data['id']; ?>"  data-plan-id="<?php echo $data['id']; ?>" href="<?php echo HOST_URL; ?>shopify_payment.php?plan_id=<?php echo $data['id']; ?>&shop_url=<?php echo $shop_url; ?>" class="card__plan__button <?php if($data['id'] == $user_subscription_id && $status == 'pending'){ echo 'pending_plan'; } ?>  getStartedBtn<?=$data['id']?>">
                                              <span class="card__plan__button__text__wrapper">
                                                 <span ><?php if($data['id'] == $user_subscription_id && $status == 'pending'){ echo 'Pending Plan'; }else { echo 'Get Started'; } ?></span>
                                              </span>
                                           </button>
                                           <?php

                                        }
                                        else {
                                           ?>
                                           <div class="form-group">
                                              <input type="hidden" onkeypress='return /[0-9]/i.test(event.key)' class="Polaris-text count_site" id="count_site" name="count_site" placeholder="Number of sites" sites="<?php echo $websites[0]['site_count']; ?>" value="<?php echo $websites[0]['site_count']; ?>" required="">
                                              <a data-plan-id="<?php echo $data['id']; ?>" class="card__plan__button upgrade_plan">
                                              <span class="">Change Plan</span>
                                              </a>
                                           </div>
                                           <?php  
                                        }

                                     }
                                     else {
                                        ?>
                                        <input type="hidden" name="count_site" value="<?php echo $count_site; ?>" />
                                        <button  id="<?php echo str_replace(' ', '', strtolower($data['name'])); ?><?php echo $data['id']; ?>"  data-plan-id="<?php echo $data['id']; ?>" href="<?php echo HOST_URL; ?>shopify_payment.php?plan_id=<?php echo $data['id']; ?>&shop_url=<?php echo $shop_url; ?>" class="card__plan__button <?php if($data['id'] == $user_subscription_id && $status == 'pending'){ echo 'pending_plan'; } ?>  getStartedBtn<?=$data['id']?>">
                                           <span class="card__plan__button__text__wrapper">
                                              <span ><?php if($data['id'] == $user_subscription_id && $status == 'pending'){ echo 'Pending Plan'; }else { echo 'Get Started'; } ?></span>
                                           </span>
                                        </button>
                                        <?php
                                     }

                                  }
                                  else {

                                     ?>
                                     <input type="hidden" name="count_site" value="<?php echo $count_site; ?>" />
                                     <button  id="<?php echo str_replace(' ', '', strtolower($data['name'])); ?><?php echo $data['id']; ?>"  data-plan-id="<?php echo $data['id']; ?>" href="<?php echo HOST_URL; ?>shopify_payment.php?plan_id=<?php echo $data['id']; ?>&shop_url=<?php echo $shop_url; ?>" class="card__plan__button <?php if($data['id'] == $user_subscription_id && $status == 'pending'){ echo 'pending_plan'; } ?>  getStartedBtn<?=$data['id']?>">
                                        <span class="card__plan__button__text__wrapper">
                                           <span ><?php if($data['id'] == $user_subscription_id && $status == 'pending'){ echo 'Pending Plan'; }else { echo 'Get Started'; } ?></span>
                                        </span>
                                     </button>
                                     <?php

                                  }


                               } 
                               else {

                                  ?>
                                  <input type="hidden" name="count_site" value="<?php echo $count_site; ?>" />
                                  <button  id="<?php echo str_replace(' ', '', strtolower($data['name'])); ?><?php echo $data['id']; ?>"  data-plan-id="<?php echo $data['id']; ?>" href="<?php echo HOST_URL; ?>shopify_payment.php?plan_id=<?php echo $data['id']; ?>&shop_url=<?php echo $shop_url; ?>" class="card__plan__button <?php if($data['id'] == $user_subscription_id && $status == 'pending'){ echo 'pending_plan'; } ?>  getStartedBtn<?=$data['id']?>">
                                     <span class="card__plan__button__text__wrapper">
                                        <span ><?php if($data['id'] == $user_subscription_id && $status == 'pending'){ echo 'Pending Plan'; }else { echo 'Get Started'; } ?></span>
                                     </span>
                                  </button>
                                  <?php

                               }
                               
                            }
                        }
                    ?>

                <?php } ?>
                <?php } ?>

                </form>
            </div>
        </div> 
        <!-- END trial / subscription btn -->

        <?php

            // echo "count=".count($subsfree);
            /*** 
            if("" == ""){
                if($activate_free == 1) {

                    ?>
                        <!-- <div class="free_trial_end">
                            <a href="<?=HOST_URL?>get-free-trial.php?plan=<?=$data['id']?>" class="free_days_btn card__plan__button disabled">
                                <span class="card__plan__button__text__wrapper">
                                    <span class="">Start my FREE trial</span>
                                    <i class="fa-solid fa-chevron-right"></i>
                                </span>
                            </a>
                        </div> -->
                    <?php 
                }
                else{

                    ?>   
                        <div class="top-subscribe btn">
                            <form method="POST"  action="/payment/init.php">
                                <input type="hidden" name="subscription" value="<?php echo $data['id']; ?>" />
                                <input type="hidden" name="change_id" value="<?php echo base64_decode($_REQUEST['change-sid']); ?>" />
                                <input type="hidden" name="sid_id" value="<?php echo base64_decode($_REQUEST['sid']); ?>" />
                                <input type="hidden" name="website_url" value="<?php echo $sites['website_url'];?>" />
                                <input type="hidden" name="website_id" value="<?php echo $sites['id']; ?>" />

                                <input type="hidden" name="count_site" value="<?php echo $count_site; ?>" />
                                <div class="free_trial_end"> 
                                <button  data-plan-id="<?php echo $data['id']; ?>" href="<?php echo HOST_URL; ?>shopify_payment.php?plan_id=<?php echo $data['id']; ?>&shop_url=<?php echo $shop_url; ?>" class="card__plan__button <?php if($data['id'] == $user_subscription_id && $status == 'pending'){ echo 'pending_plan'; } ?>">
                                <span class="card__plan__button__text__wrapper">
                                <span class=""><?php if($data['id'] == $user_subscription_id && $status == 'pending'){ echo 'Pending Plan'; }else { echo 'Subscribe'; } ?></span>

                                </span>
                                </button>
                                </div>
                            </form>   
                        </div>
                    <?php
                }
            }   
            ***/   
        ?> 
        

    </div>
    <div class="key_wrap key_wrap_t">   
    <div class="key__points">
        <ul>
            
            <li style="display:none;">    
            <?php     


            $pid=$data['id'];
            $query_cp = $conn->query("select * from `plans_functionality`  where plan_id=$pid") ;
            $data_cp = $query_cp->fetch_assoc();

            //print_r($data_cp); 
            ?> 
            </li>


            <?php if($data_cp['line1']!=null){ ?> <li> <span class="first__line" ><?php echo $data_cp['line1']; ?></span><span class="second__line" ><?php echo $data_cp['line1']; ?></span></li> <?php } ?>                             
            <?php if($data_cp['line3']!=null){ ?> <li> <span class="first__line"><?php echo $data_cp['line3']; ?></span>
            <span class="second__line"><?php echo $data_cp['line3']; ?></span> </li>  <?php } ?>
            <?php if($data_cp['line4']!=null){  ?>  <li><?php echo $data_cp['line4']; ?></li> <?php } ?>
            <?php if($data_cp['line5']!=null){  ?> <li><?php echo $data_cp['line5']; ?></li> <?php } ?>
            <?php if($data_cp['line6']!=null){ ?>  <li><?php echo $data_cp['line6']; ?></li> <?php } ?>
            <?php if($data_cp['line7']!=null){  ?> <li><?php echo $data_cp['line7']; ?></li> <?php } ?>
            <?php if($data_cp['line8']!=null){  ?> <li><?php echo $data_cp['line8']; ?></li> <?php } ?>
            <?php if($data_cp['line9']!=null){   ?>  <li><?php echo $data_cp['line9']; ?></li> <?php } ?>
            <?php if($data_cp['line10']!=null){   ?>  <li><?php echo $data_cp['line10']; ?></li> <?php } ?>
            <?php if($data_cp['line11']!=null){   ?>  <li><?php echo $data_cp['line11']; ?></li> <?php } ?>
            <?php if($data_cp['line12']!=null){   ?>  <li><?php echo $data_cp['line12']; ?></li> <?php } ?>
            <?php if($data_cp['line13']!=null){   ?>  <li><?php echo $data_cp['line13']; ?></li> <?php } ?>
            <?php if($data_cp['line14']!=null){   ?>  <li><?php echo $data_cp['line14']; ?></li> <?php } ?>
                <!-- //123 -->
       
        </ul>

    </div>
    </div>
    <div class="slide_btn slide_btn_t"><div class="flx_con"><span>What’s included</span> <?xml version="1.0" ?><svg fill="none" stroke-width="1.5" viewBox="0 0 24 24" width="24" xmlns="http://www.w3.org/2000/svg"><path d="M6 9L12 15L18 9" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"/></svg></div></div>


        <div class="top-sec-card  freebtn-active <?php if($activate_free == 1 ) { echo 'freebtn-active'; } ?> ">
            <!-- <div class="popular__text">Most Popular</div> -->
            <div class="wave -one"></div>
            <div class="wave -two"></div>
            <h2 class="plan-name">
                <span class="year_free"><?php if($plan_frequency_interval == 'year'){echo "<p>MOST POPULAR 50% DISCOUNT
</p>";} ?></p> </span> 
                <?php echo $data['name']; ?>
                <?php 
                if( $data['id'] == $user_subscription_id && $status == 'active'  && $plan_type == "Free"  )
                    { echo '<span class="crnt_pln">Current Plan</span>'; }
                elseif($data['id'] == $user_subscription_id && $status == 'active' ){ echo '<span class="crnt_pln">Current Plan</span>'; } ?> 
            </h2>
            <?php if($data_cp['line15']!=null){   ?>  <p><?php echo $data_cp['line15']; ?></p> <?php } ?>
            <?php 
                if($data['s_price'] != "") {
                    ?>
                    <div class="price-tag">
                        <div class="discount__price" >
                            <span class="symbol"><?php if($i!=0){?>₹<?php }?></span><span class="amount" subs="<?php echo $data['id'];?>"><?php echo round($data['s_price']/12);  ?></span> 
                            <span class="after month">INR<span class="month-slash">/</span>month</span>
                            <span class="after year">INR<span class="month-slash">/</span>year</span>
                            <span class="after d-none">
                                <?php if($i!=0){?>
                                <span class="month-slash" >/</span>
                                    <?php echo $data['interval'];  ?><?php }?>
                            </span> 
                            
                        </div>

                       <div class="original__price">
                    <?php if( !empty($data['main_p']) && $data['main_p'] != $data['s_price'] ){   ?>
                         <del>₹<?=$data['main_p']?></del>
                         <!-- <span class="month" >/month</span>  
                         <span class="year" >/year</span>   -->
                         <span class="d-none">/<?=($data['interval'])?></span> 
                     <?php } ?>     

                        </div>

                    </div>
                    <?php 
                } 
            ?>

            
            <?php
                echo "here" ;
                // echo "count=".count($subsfree);
                if("" == ""){
                    if($activate_free == 1) {
                        ?>
                        <div class="free_trial_end">
                            <a href="<?=HOST_URL?>get-free-trial.php?plan=<?=$data['id']?>" class="free_days_btn card__plan__button disabled">
                                <span class="card__plan__button__text__wrapper">
                                    <span class="">Start my FREE trial</span>
                                    <i class="fa-solid fa-chevron-right"></i>
                                </span>
                            </a>
                        </div>
                        <?php 
                    }
                    else{
                        ?>   
                            <div class="top-subscribe btn">
                                <form method="POST"  action="/payment/init.php">
                                    <input type="hidden" name="subscription" value="<?php echo $data['id']; ?>" />
                                    <input type="hidden" name="change_id" value="<?php echo base64_decode($_REQUEST['change-sid']); ?>" />
                                    <input type="hidden" name="sid_id" value="<?php echo base64_decode($_REQUEST['sid']); ?>" />
                                    <input type="hidden" name="website_url" value="<?php echo $sites['website_url'];?>" />
                                    <input type="hidden" name="website_id" value="<?php echo $sites['id']; ?>" />
                                    
                                  <input type="hidden" name="count_site" value="<?php echo $count_site; ?>" />
                                  <div class="free_trial_end"> 
                                  <button  data-plan-id="<?php echo $data['id']; ?>" href="<?php echo HOST_URL; ?>shopify_payment.php?plan_id=<?php echo $data['id']; ?>&shop_url=<?php echo $shop_url; ?>" class="card__plan__button <?php if($data['id'] == $user_subscription_id && $status == 'pending'){ echo 'pending_plan'; } ?>">
                                  <span class="card__plan__button__text__wrapper">
                                  <span class=""><?php if($data['id'] == $user_subscription_id && $status == 'pending'){ echo 'Pending Plan'; }else { echo 'Get Started'; } ?></span>

                                  </span>
                                  </button>
                                    </div>
                                </form>   
                            </div>
                        <?php
                    }
                }      
            ?>
        </div>
      
        <ul>
            <li style="display:none;">    
                 <?php     
                 
         
                $pid=$data['id'];
           $query_cp = $conn->query("select * from `plans_functionality`  where plan_id=$pid") ;
            $data_cp = $query_cp->fetch_assoc();

            //print_r($data_cp); 
                 ?> 
               </li>
                <li><span>Up To <span><?php echo $data['page_view']; ?> <br> <?php if($data_cp['line2']!=null){ ?><?php echo $data_cp['line2']; ?><?php } ?>  <span class="q__icon">?</span><span class="text__hidden">To get an idea about your monthly page views go to your Google Analytics Dashboard.<br>
To go to page views in google analytics : Go to - Lifecycle > Engagement > Pages & Screens > Pageviews</span></li>
            

            <?php if($data_cp['line1']!=null){ ?> <li> <?php echo $data_cp['line1']; ?></li> <?php } ?>                             
             <?php if($data_cp['line3']!=null){ ?> <li> <?php echo $data_cp['line3']; ?> </li>  <?php } ?>
             <?php if($data_cp['line4']!=null){  ?>  <li><?php echo $data_cp['line4']; ?></li> <?php } ?>
             <?php if($data_cp['line5']!=null){  ?> <li><?php echo $data_cp['line5']; ?></li> <?php } ?>
             <?php if($data_cp['line6']!=null){ ?>  <li><?php echo $data_cp['line6']; ?></li> <?php } ?>
             <?php if($data_cp['line7']!=null){  ?> <li><?php echo $data_cp['line7']; ?></li> <?php } ?>
              <?php if($data_cp['line8']!=null){  ?> <li><?php echo $data_cp['line8']; ?></li> <?php } ?>
            <?php if($data_cp['line9']!=null){   ?>  <li><?php echo $data_cp['line9']; ?></li> <?php } ?>
              <?php if($data_cp['line10']!=null){   ?>  <li><?php echo $data_cp['line10']; ?></li> <?php } ?>
              <?php if($data_cp['line11']!=null){   ?>  <li><?php echo $data_cp['line11']; ?></li> <?php } ?>
              <?php if($data_cp['line12']!=null){   ?>  <li><?php echo $data_cp['line12']; ?></li> <?php } ?>
              <?php if($data_cp['line13']!=null){   ?>  <li><?php echo $data_cp['line13']; ?></li> <?php } ?>
              <?php if($data_cp['line14']!=null){   ?>  <li><?php echo $data_cp['line14']; ?></li> <?php } ?>
                <!-- //123 -->
        </ul>
    </div>

    <div style="display:none" class="subscribe_free">
        <div class="sub_free">
            <form method="POST"  action="/payment/init.php">
            <?php 
                if($data['id'] == $user_subscription_id && $status == 'active' && $plan_type != "Free"  ) { 
                ?>
                
            <a  data-plan-id="<?php echo $data['id']; ?>" href="javascript:void(0)" class="card__plan__button activated__plan"><span class="card__plan__button__text__wrapper"><span class="">Activated</span></span></a>

    

            <a style="display: none;"> data-plan-id="<?php echo $data['id']; ?>"  class="card__plan__button add_site"><span class="card__plan__button__text__wrapper"><span class="">+</span></span></a>


            <?php 
                }
                else 
                { 
                
                if($data['s_type'] == "Free" ) {
                ?> 
            <!-- <a href="<?=HOST_URL?>get-free-trial.php?plan=<?=$data['id']?>" class="Polaris-Button"> -->

            <span class="card__plan__button__text__wrapper">
            <span class=""><?php if($data['id'] == $user_subscription_id && $status == 'pending'){ echo 'Pending Plan'; }else { echo 'Get Started'; } ?></span>
            <?php 
                } 
                else { 
                ?>
                <input type="hidden" name="subscription" value="<?php echo $data['id']; ?>" />
                <input type="hidden" name="change_id" value="<?php echo base64_decode($_REQUEST['change-sid']); ?>" />
                <input type="hidden" name="sid_id" value="<?php echo base64_decode($_REQUEST['sid']); ?>" />
                <input type="hidden" name="website_url" value="<?php echo $sites['website_url'];?>" />
                <input type="hidden" name="website_id" value="<?php echo $sites['id']; ?>" />

            <?php 

            $count_site = 1;
              if($data['plan_frequency'] == "1 Website"){$count_site = 1;}
              elseif($data['plan_frequency'] == "2 Websites"){$count_site = 2;}
              elseif($data['plan_frequency'] == "3+ Websites"){$count_site = "Unlimited";}

           
            if($subs_id =="" || $plan_frequency_interval != $current_plan_interval ){ ?>
               
            <!-- Fist time subscription -->
                
            <input type="hidden" name="count_site" value="<?php echo $count_site; ?>" />
    
            <button  data-plan-id="<?php echo $data['id']; ?>" href="<?php echo HOST_URL; ?>shopify_payment.php?plan_id=<?php echo $data['id']; ?>&shop_url=<?php echo $shop_url; ?>" class="card__plan__button <?php if($data['id'] == $user_subscription_id && $status == 'pending'){ echo 'pending_plan'; } ?>">
            <span class="card__plan__button__text__wrapper">
            <span class=""><?php if($data['id'] == $user_subscription_id && $status == 'pending'){ echo 'Pending Plan'; }else { echo 'Get Started'; } ?></span>

            </span>
            </button>

             <!-- Upgrade time subscription -->

             <!--   Fist time subscription -- -->
               <!--  
            <input type="hidden" name="count_site" value="<?php echo $count_site; ?>" />
    
            <button  data-plan-id="<?php echo $data['id']; ?>" href="<?php echo HOST_URL; ?>shopify_payment.php?plan_id=<?php echo $data['id']; ?>&shop_url=<?php echo $shop_url; ?>" class="card__plan__button <?php if($data['id'] == $user_subscription_id && $status == 'pending'){ echo 'pending_plan'; } ?>">
            <span class="card__plan__button__text__wrapper">
            <span class=""><?php if($data['id'] == $user_subscription_id && $status == 'pending'){ echo 'Pending Plan'; }else { echo 'Get Started'; } ?></span>

            </span>
            </button> -->

            <!-- Upgrade time subscription -->

            <?php }else{ 
                  // echo  $plan_frequency_interval;
                if($plan_open_type=="update" && $plan_frequency == $current_plan_frequency && $plan_frequency_interval == $current_plan_interval){  
                ?>


            

            
            <input type="hidden" name="count_site" value="<?php echo $count_site; ?>" />
    
            <button  data-plan-id="<?php echo $data['id']; ?>" href="<?php echo HOST_URL; ?>shopify_payment.php?plan_id=<?php echo $data['id']; ?>&shop_url=<?php echo $shop_url; ?>" class="card__plan__button <?php if($data['id'] == $user_subscription_id && $status == 'pending'){ echo 'pending_plan'; } ?>">
            <span class="card__plan__button__text__wrapper">
            <span class=""><?php if($data['id'] == $user_subscription_id && $status == 'pending'){ echo 'Pending Plan'; }else { echo 'Get Started'; } ?></span>

            </span>
            </button>                                                
            <!--          <input type="hidden" onkeypress='return /[0-9]/i.test(event.key)' class="Polaris-text count_site" id="count_site" name="count_site" placeholder="Number of sites" sites="<?php echo $websites[0]['site_count']; ?>" value="<?php echo $websites[0]['site_count']; ?>" required="">
            <a data-plan-id="<?php echo $data['id']; ?>" class="card__plan__button upgrade_plan">
                      <span class="">Change Plan 1</span>
            </a> -->



    
            <?php }else{ ?>

            

           <div class="form-group">
            
                
                <input type="hidden" onkeypress='return /[0-9]/i.test(event.key)' class="Polaris-text count_site" id="count_site" name="count_site" placeholder="Number of sites" sites="<?php echo $websites[0]['site_count']; ?>" value="<?php echo $websites[0]['site_count']; ?>" required="">
            <a data-plan-id="<?php echo $data['id']; ?>" class="card__plan__button upgrade_plan">
                      <span class="">Change Plan</span>
            </a>


            </div>

          <?php  }
            }
             ?>
            <?php } ?>
            <?php } ?>


            </form>
        </div>

        <?php
        // echo "count=".count($subsfree);
        if("" == ""){
        if($activate_free == 1) {

        ?>

        <a href="<?=HOST_URL?>get-free-trial.php?plan=<?=$data['id']?>" class="Polaris-Button card__plan__button free_days_s free_days_btn" style="display:none;">
            <span class="card__plan__button__text__wrapper">
                <span class="">Start 7 days Free trial</span>
            </span>
        </a>
        <?php 
        }
        else{
         ?>   
         

         <?php
        }
        }      
        ?>
    </div>  
                                

</div>





