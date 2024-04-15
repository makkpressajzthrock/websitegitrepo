<?php 
   // ini_set('display_errors', 1); ini_set('display_startup_errors', 1); error_reporting(E_ALL);
   
   include('session.php');
   
   require_once("adminpannel/config.php") ;
   require_once('adminpannel/inc/functions.php') ;
   // echo $_REQUEST['change_plans'];
   
   
  
   //123
   if ( !checkUserLogin() ) {
       header("location: ".HOST_URL."") ;
       die() ;
   }
   
   $subsc_id = base64_decode($_REQUEST['sid']);
   
   
   
   
   $subsc_id_url = $_REQUEST['sid'];
   
   $plan_type="";
   $plan_id=0;
   $subs_id = "";
   $current_plan_frequency = "1 Website";
   $current_plan_interval = "month";
   $plan_open_type="";
   
       $user_id = $_SESSION['user_id'];
   
         $sites = getTableData( $conn , " boost_website " , " id = '".$subsc_id."' ");
   
   
   
         $websites = array();
         if($sites['plan_id']!='999'){
   
                $websites = getTableData( $conn , " user_subscriptions " , " id = '".$sites['subscription_id']."' " , "" , 1 );
   
         }
    
         if($sites['plan_id']=='0'){
   
                $activate_free = 1;
   
         }
        
     // $activate_free = 1;
    if(count($websites) > 0){
          
   // print_r($websites);
   
          $plan_id=$websites[0]['plan_id'];
          $subs_id = base64_encode($websites[0]['id']);
           $plan_type="subscription"; 
           
         $query_cp = $conn->query(" SELECT * FROM `plans` WHERE id = '$plan_id'") ;
         $data_cp = $query_cp->fetch_assoc();
            $current_plan_frequency = $data_cp['plan_frequency'];
       // echo '<br>';                                
            $current_plan_interval = $data_cp['interval'];
        $plan_open_type="update";
   
   }
        
       $_SESSION["siteId"] = "" ;
       $user_status = $websites[0]['status'];
   
   
   $subsfree = getTableData( $conn , " user_subscriptions_free " , " user_id ='".$_SESSION['user_id']."' " ) ;
   
   if($subsfree['status'] == 1){
      $plan_type="Free"; 
      $plan_id=$subsfree['plan_id'];
   }
   
   if(!empty($subsfree)){
       if($subsfree['status'] == 0){
          $activate_free = 0;
       }
   }
   
    
   
   // echo $plan_type;
   
   
   
           $get_flow = $conn->query(" SELECT * FROM `admin_users` WHERE id = '$user_id' ");
           $d = $get_flow->fetch_assoc();
           if($d['sumo_code'] !="" && $d['sumo_code'] !="null"){
              $plan_type="subscription"; 
           }
   
          
      
   
           if($d['country'] !=""){
               if($d['country'] == "101"){
                   header("location: ".HOST_URL."plan.php?sid=".$_GET['sid']) ;
                   die();
               }
           }
           elseif($d['country_code'] == "+91"){
                header("location: ".HOST_URL."plan.php?sid=".$_GET['sid']) ;
               die();
           }
   
           
           $get_flow = $conn->query(" SELECT * FROM `admin_users` WHERE id = '$user_id' ");
           $d = $get_flow->fetch_assoc();
           if($d['sumo_code'] !="" && $d['sumo_code'] !="null"){
              $plan_type="subscription"; 
           }
    
   ?>
<?php require_once('adminpannel/inc/style-and-script-cdn.php') ; ?>
</head>
<style type="text/css">
   .check-box {
   transform: scale(1);
   }
   input[type="checkbox"] {
   position: relative;
   appearance: none;
   width: 65px;
   height: 30px;
   background: #ccc;
   border-radius: 50px;
   box-shadow: inset 0 0 5px rgba(0, 0, 0, 0.2);
   cursor: pointer;
   transition: 0.4s;
   }
   input:checked[type="checkbox"] {
   background: #f23640;
   }
   input[type="checkbox"]::after {
   position: absolute;
   content: "";
   width: 22px;
   height: 22px;
   top: 4px;
   left: 5px;
   background: #fff;
   border-radius: 50%;
   box-shadow: 0 0 5px rgba(0, 0, 0, 0.2);
   transform: scale(1.1);
   transition: 0.4s;
   }
   input:checked[type="checkbox"]::after {
   left: 58%;
   }
   .makk-page-wrapper.plan-page.plan-box.active,.plan_main_container.active{
   display: block;
   animation: fadeIn .5s;
   }
   .makk-page-wrapper.plan-page.plan-box,.plan_main_container{
   display: none;
   }
   @keyframes fadeIn {
   from { opacity: 0.5; }
   to { opacity: 1; }
   }
   .subscribe_free {
   z-index:9;
   width:100%;
   order:4;
   }
</style>
<body>
   <!-- Google Tag Manager (noscript) -->
   <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-MK5VN7M"
      height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
   <!-- End Google Tag Manager (noscript) -->
   <div class="whole-cover dashboard">
   <!-- Tab Nav -->    
   <input type="hidden" id="shop__url" value="">
   <!--------------Tab Content 1-------------------->
   <div id="tab-1" class="tab-content current plans_page">
      <!-- For Select Option Code -->
      <div class="padding-top">
         <div class="padding-top">
            <div class="glass"></div>
            <div class="glass"></div>
            <div class="glass"></div>
            <div class="glass"></div>
            <div class="glass"></div>
            <div class="glass"></div>
            <div class="glass"></div>
            <!-- <div class="wrpper__plan__card first">
               <div class="label__plan__card">
                   <label class="label__text__plan__card">Average Speed score of our customer for any of our plans</label>
                   <label class="label__text__plan__card medium">Google page insignts - Pingdom - Gtmatrix</label>
                   <span class="label__text__plan__card small">
                       <div><i class="las la-mobile"></i></i><span>Mobile - 75+</span></div> 
                       <div><i class="las la-laptop"></i><span>Desktop - 95+</span></div></span>
               </div>
               </div>                 -->
            <div class="plan__page heading" >
               <button class="back__button" onclick="window.history.back()"></button>
               <h1>Make your website load at <span>lightning fast</span> speed and get more sales</h1>
               <h2>Boost your website speed and sales in less than 5 minutes</h2>
               <p>Setup in less than 5 minutes</p>
            
               <div class="free__tag" > 
                  <span>All Plans</span>
                  <span>Free</span>
                  <span>For 7 days</span>
               </div>
            </div>

            <!-- for showinng only yearly plan -->
            <!-- <div class="plan__switch">
                  <div class="wrpper__plan__card second">
                     <div class="button__toggle__month__year">
                        <div class="" >
                           <div class="app-setting row">
                              <div class="check-box plan_tgg">
                                  <span class="monthly">Monthly</span>
                                  <input id="check_box_plan" type="checkbox" <?php if($current_plan_interval == 'year'){echo 'checked';} ?> checked>
                                  <span class="yearly" checked>Yearly</span>
                              </div>
                           </div>
                        </div>
                        <div class="floating__other__text" >
                           <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" width="256" height="256" viewBox="0 0 256 256" xml:space="preserve" >
                              <defs>
                              </defs>
                              <g style="stroke: none; stroke-width: 0; stroke-dasharray: none; stroke-linecap: butt; stroke-linejoin: miter; stroke-miterlimit: 10; fill: none; fill-rule: nonzero; opacity: 1;" transform="translate(1.4065934065934016 1.4065934065934016) scale(2.81 2.81)">
                                 <path d="M 9.763 73.891 c 0.049 0.007 0.095 0.029 0.145 0.029 c 0.029 0 0.055 -0.014 0.084 -0.017 c 0.038 -0.001 0.073 0.012 0.111 0.006 c 0.064 -0.009 0.117 -0.042 0.176 -0.063 c 0.007 -0.003 0.013 -0.004 0.019 -0.007 c 0.21 -0.078 0.383 -0.211 0.498 -0.393 l 8.733 -8.734 c 0.391 -0.391 0.391 -1.023 0 -1.414 s -1.023 -0.391 -1.414 0 l -7.41 7.411 c -1.64 -15.449 4.181 -28.442 16.193 -35.847 c 0.471 -0.29 0.617 -0.906 0.327 -1.376 s -0.906 -0.616 -1.376 -0.326 C 14.547 40.125 8.41 51.72 8.41 65.636 c 0 1.505 0.08 3.04 0.225 4.596 l -6.934 -6.934 c -0.391 -0.391 -1.023 -0.391 -1.414 0 c -0.195 0.195 -0.293 0.451 -0.293 0.707 c 0 0.256 0.098 0.512 0.293 0.707 l 8.914 8.914 c 0.131 0.131 0.298 0.204 0.475 0.247 C 9.704 73.882 9.733 73.885 9.763 73.891 z" style="stroke: none;stroke-width: 1;stroke-dasharray: none;stroke-linecap: butt;stroke-linejoin: miter;stroke-miterlimit: 10;fill: rgb(242 54 64);fill-rule: nonzero;opacity: 1;" transform=" matrix(1 0 0 1 0 0) " stroke-linecap="round"></path>
                              </g>
                           </svg>
                           Get 1<br> month free 
                        </div>
                     </div>
                  </div>
               
            </div> -->
          

            <?php require_once('inc/alert-message.php') ; ?>
           

            <!-- for showinng only yearly plan -->
            <div class="plan_main_container year  <?php if($current_plan_interval == 'year'){echo 'active';} ?> active">
             

               <?php                
                  $query_g = $conn->query(" SELECT * FROM `plans` group by  plan_frequency");
                  $tab = 0;
                  while($data_all = $query_g->fetch_assoc() ) 
                   {
                     $tab++;
                    $plan_frequency = $data_all['plan_frequency'];
                  ?>                     
               <div class="makk-page-wrapper plan-page plan-box <?php if($tab==1){echo 'active';}?>" id="y-tabs-<?=$tab?>">
                  <div class="makk-wrapper-card">
                     <!-- <div class="text__excul">Exclusive Launch Deal for First 1000 Users, only a few left!</div> -->
                     <div class="plan-items">
                        <?php
                           $user_subscription_id = $plan_id ;
                           $query = $conn->query(" SELECT * FROM `plans` WHERE status = 1 and interval_plan = 'year' and plan_frequency = '$plan_frequency'") ;
                           
                           if($query->num_rows > 0) 
                           {
                               $i = 1;
                               while($data = $query->fetch_assoc() ) 
                               {
                                  $plan_frequency_interval = $data['interval'];
                           
                                   if($data['id'] ==$user_subscription_id){ $status = 'active'; } 
                           
                                   include("plan-card-us.php");
                           
                                   $i++;
                           
                               }
                               
                           }
                               
                           ?>
                     </div>
                  </div>
               </div>
               <?php
                  }
                  ?>
            </div>
            <!--*************** End: Subscription Yearly ******************-->
         </div>
      </div>
      <!------------------------------End---------------------------------->
   </div>

<script>
$('.slide_btn_t').click(function(){
    $(this).parents('.card__plan').find('.key_wrap_t').slideToggle();
    $(this).toggleClass('open_key');
})
</script>

</body>
</html>

<script type="text/javascript">
   $(document).ready(function(){
   
   
   // Monthly and Yearly
   
   $(".plan_yearly").click(function(){
   $("#check_box_plan").prop("checked",true);
       $(".plan_main_container").removeClass("active");
       $(".plan_main_container.year").addClass("active");
       $(".floating__other__text").show();
   
   });
   
   $(".plan_monthly").click(function(){
   $("#check_box_plan").prop("checked",false);
       $(".plan_main_container").removeClass("active");
       $(".plan_main_container.month").addClass("active");
       $(".floating__other__text").hide();
   
   });
   
   // End monthly and yearly
   $(".pricing-tab").click(function(event){
   event.preventDefault();
   $(this).parents(".plan_main_container").first().find(".pricing-tab").removeClass("active");
   $(this).addClass("active");
   var target = $(this).attr("href");
   // console.log(target);
   $(this).parents(".plan_main_container").first().find(".plan-box").removeClass("active");
   $(this).parents(".plan_main_container").first().find(target).addClass("active");
   
   });
   
   $("#check_box_plan").change(function(){
   if ($(this).prop('checked')==true){ 
       // console.log("true");
       $(".plan_main_container").removeClass("active");
       $(".plan_main_container.year").addClass("active");
       $(".floating__other__text").show();
   
   }   
   else{
       // console.log("false");
       $(".plan_main_container").removeClass("active");
       $(".plan_main_container.month").addClass("active");
       $(".floating__other__text").hide();
   }
   });
   
   

   
   // End pricing tab
   
   
   // Update Plan
   $(".upgrade_plan").click(function(){
   var c = $(this).prev().val();
   var p = $(this).attr('data-plan-id');
   // alert(c+","+p);
   window.location.href="<?=HOST_URL?>payment/update_subscription.php?pl="+p+"&cs="+c+"&sid=<?=$subs_id?>";
   });
   // End UpdatePlan
   
   
   
       $(".upgrade_plan_change").click(function(){
           var b = $(this).prev();
           
           Swal.fire({
                 title: 'Outside From You Current Plan',
                 text: "You Need to purchase this plan subscription seperatly.",
                 icon: 'info',
                 showCancelButton: true,
                 confirmButtonColor: '#3085d6',
                 cancelButtonColor: '#d33',
                 confirmButtonText: 'Yes Get'
               }).then((result) => {
                 if (result.isConfirmed) {
                       b.click();
                 }
               })
       });
   
   });
</script>
<script>
   const x = document.querySelectorAll('.second__line'); 
   const z = document.querySelectorAll('.first__line');
   for (let i = 0; i < x.length; i++) {
      var y = x[i].innerText.slice(3);
      x[i].innerText = y
   }
   for (let j = 0; j < z.length; j++) {
      var k = z[j].innerText.slice(0, 3)
      z[j].innerText = k
   }
</script>
<script>
   $(document).ready(function(){
       // for subscription with trial
       $(".start-trial-button").click(function(){
           // $("input[name='with_trial']").val(1) ;
           $(this).parents(".subscription_trial_form").find("input[name='with_trial']").val(1);
           $(this).parents(".subscription_trial_form")[0].submit();
       }) ;
   
       $(".start-subscription-button").click(function(){        
           // $("input[name='with_trial']").val(0) ;
           $(this).parents(".subscription_trial_form").find("input[name='with_trial']").val(0);
           $(this).parents(".subscription_trial_form")[0].submit();
       }) ;
   });
   
</script>
<!-- 
   <script type="text/javascript">
       
   $(document).ready(function(){
   
       
   var user_countr= '' ;
   
   
   $.getJSON('https://ipapi.co/json/', function(data) {
       user_country = data.country_name ;
       checkcountry(user_country);
   });
   
   if ( user_country == '' ) {
       $.getJSON('https://ipinfo.io/json', function(data) {
           user_country = data.country ;
           checkcountry(user_country);
       });
   }
   
   if ( user_country == '' ) { 
       $.get('https://www.cloudflare.com/cdn-cgi/trace', function(data) {
           data = data.trim().split('\n') ;
           if (data.length > 0 ) {
   
               data = data.reduce(function(obj, pair) {
                   pair = pair.split('=');
                   if ( pair[0] == 'loc' ) { user_country = pair[1] }
                   return obj[pair[0]] = pair[1], obj;
                   checkcountry(user_country);
               }, {});
               user_flag = 1;
           }
       });
   
   }
    
   function checkcountry(uc){
       console.log("country="+uc);
       if(uc=="IN" || uc =="India"){
            window.location.href="<?=HOST_URL?>plan.php"+window.location.search;  
       }
       else{
            // window.location.href="<?=HOST_URL?>plan-us.php";
            
       }
   }
   
   });
   
   </script> -->
   <!-- //123 -->

   <!-- for showinng only yearly plan -->
<script>

$(document).on('change', '#selectPricing', function () {
   is_valid = true;
   
   var price = $(this).val();
   var planId = $(this).find(':selected').data('planid'); // Using find(':selected') to get the selected option
   var planSelect = 'Select';

   if (planId == '4') {
      if (parseInt(price) == 250000) {
         is_valid = true;
         $('.getStartedBtn4').show();
         $('.contactSalesBtn4').hide();
      }
      else if (parseInt(price) >= 1000000 && parseInt(price) <= 10000000) {
         $('.contactSalesBtn4').show();
         $('.getStartedBtn4').show();
      }
      else if (price === 'Unlimited') {
         $('.getStartedBtn4').show();
         $('.contactSalesBtn4').show();
         is_valid = true;
      }
      else {
         is_valid = true; // Set is_valid to false if none of the conditions match
      }

   }
   else if (planId == '15') {

      $('.getStartedBtn15').show();
         $('.contactSalesBtn15').hide();

         if ( (price === 'Unlimited') || (price === '1000000') || (price === '5000000') ) {
            $('.contactSalesBtn15').show();
         }

         is_valid = true;

      // if (parseInt(price) == 250000) {
      //    is_valid = true;
      //    $('.getStartedBtn15').show();
      //    $('.contactSalesBtn15').hide();
      // }
      // else if (parseInt(price) >= 1000000 && parseInt(price) <= 10000000) {
      //    $('.contactSalesBtn15').show();
      //    $('.getStartedBtn15').show();
      // }
      // else if (price === 'Unlimited') {
      //    $('.getStartedBtn15').show();
      //    $('.contactSalesBtn15').show();
      //    is_valid = true;
      // }
      // else {
      //    is_valid = true; // Set is_valid to false if none of the conditions match
      // }

   }

   if (is_valid) {
      $.ajax({
         url: 'adminpannel/common.php',
         type: 'post',
         data: {
            price: price,
            planId: planId,
            planSelect: planSelect
         },
         success: function (response) {
            console.log(response);
            var obj = $.parseJSON(response);
            console.log(obj.message.price);
            if (obj.status == '1' && obj.message.id == '4') {
               // console.log(1)
               $('.amount4').html(obj.message.price);

               setTimeout(function () {
                  if ($(".super-plan-btn").length > 0) {
                     var data_active = $(this).find(':selected').data('active');

                     console.log("data_active " + data_active);

                     $(".card__plan__button.activated__plan").show();
                     $(".super-plan-btn").hide();

                     if (data_active == "false") {
                        $(".card__plan__button.activated__plan").hide();
                        $(".super-plan-btn").show();
                     }
                  }
               }, 500);

            }
            else if (obj.status == '1' && obj.message.id == '15') {
               // console.log(2)
               $('.amount15').html(obj.message.price);

               setTimeout(function () {
                  if ($(".super-plan-btn").length > 0) {
                     var data_active = $(this).find(':selected').data('active');

                     console.log("data_active " + data_active);

                     $(".card__plan__button.activated__plan").show();
                     $(".super-plan-btn").hide();

                     if (data_active == "false") {
                        $(".card__plan__button.activated__plan").hide();
                        $(".super-plan-btn").show();
                     }
                  }
               }, 500);
            }
            else {
               // console.log(3)
               $('.price-tag.amount4').html();
               $('.price-tag.amount15').html();
            }
         }
      })
   }
})


      //onload
      $(document).ready(function(){
         is_valid = true;
         var price = $('#selectPricing').val();
          var planId = $('#selectPricing').find(':selected').data('planid'); // Using find(':selected') to get the selected option
          var planSelect = 'Select';
          console.log(price);
          console.log(planId); 
         
         if(planId==4){
            planId = 15;
         }
         
         if(is_valid){
          $.ajax({
            url : 'adminpannel/common.php',
            type : 'post',
            data : {
               price : price,
               planId : planId,
               planSelect : planSelect
            },
            success : function(response){
               console.log(response);
               var obj = $.parseJSON(response);
               console.log(obj.message.price);
               if(obj.status =='1' && obj.message.id=='4' ){
                  // console.log(1)
                  $('.amount4').html(obj.message.price);
                  $('.selectPricingDiv4').load(' .selectPricingDiv4')
               }
               else if(obj.status =='1' && obj.message.id=='15'){
                  // console.log(2)
                  $('.amount15').html(obj.message.price);
                  $('.selectPricingDiv15').load(' .selectPricingDiv15')
               }else{
                  // console.log(3)
                  $('.price-tag.amount4').html();
                  $('.price-tag.amount15').html();
               }
            }
          })
         }
    
   })
   </script>

