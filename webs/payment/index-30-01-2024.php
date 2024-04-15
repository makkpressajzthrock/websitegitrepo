<?php 
//  error_reporting(E_ALL);
// ini_set('display_errors', 1);


session_start();
if(isset($_SESSION['user_id']) && !empty($_SESSION["user_id"]))
{

}
else{
   header("Location: https://websitespeedy.com/adminpannel/");
   die;  
 }
 
include('../adminpannel/session.php');

require_once("../adminpannel/config.php") ;
require_once('../adminpannel/inc/functions.php') ;

require_once("Common.php") ;
 
 
$common = new Common();
$allCountries = $common->getCountries($conn);
 
$managerId=$_SESSION['user_id'];

$sele2 = "SELECT * FROM `billing-address` WHERE `manager_id` = '".$managerId."'";
$sele_con2 = mysqli_query($conn, $sele2);
$sele_run2 = mysqli_fetch_assoc($sele_con2);

$SelectedCountry = $sele_run2['country'];


$sele44 = "SELECT country,firstname,lastname,email FROM `admin_users` WHERE `id` = '".$managerId."'";
$sele_con44 = mysqli_query($conn, $sele44);
$sele_run44 = mysqli_fetch_assoc($sele_con44);


if($sele_run2['country']==""){
   

$SelectedCountry = $sele_run44['country'];


}

if($sele_run2['full_name'] == ""){
  $sele_run2['full_name'] = $sele_run44['firstname']." ".$sele_run44['lastname'];
}
if($sele_run2['email'] == ""){
  $sele_run2['email'] = $sele_run44['email'];
}

$fName = $sele_run2['full_name'];
$payEmail = $sele_run2['email'];

$user_id = $_SESSION["user_id"];
$cart_id = $_REQUEST['cart'];
        $sqlCart = "SELECT * FROM cart where user_id='$user_id' and cart_id = '$cart_id'";
        $resultCartVal = mysqli_query($conn, $sqlCart);
        $resultCart = mysqli_fetch_assoc($resultCartVal);
        // print_r($resultCart);

?>

<!DOCTYPE html>
<html>
<head>

<!-- Google Tag Manager -->
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','GTM-MK5VN7M');</script>
<!-- End Google Tag Manager -->


<link rel="icon" type="image/x-icon" href="//websitespeedycdn.b-cdn.net/speedyweb/images/favicon.ico" > 

<script src="https://www.dwin1.com/58969.js" type="text/javascript" defer="defer"></script>

<link rel="stylesheet" type="text/css" href="css/style.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.3/jquery.min.js" integrity="sha512-STof4xm1wgkfm7heWqFJVn58Hm3EtS31XFaagaa8VMReCXAkQnJZ+jEy8PCC/iT18dFy95WcExNHFTqLyp72eQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="../adminpannel/js/scripts.js"></script>
<script src="/js/dotlottie-player.js"></script>


</head>
<body>

<!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-MK5VN7M"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->
 
<!-- Billing Data -->
<div class="payment__main__page payment_addon_s_wrapper">

    <div class="payment__header" >
            <div class="logo-cus"><a href="https://websitespeedy.com/adminpannel/" ><img    style="width: 175px;" src="https://websitespeedy.com/adminpannel/img/sitelogo_s.png" alt="Ecommercespeedy Logo"></a></div>

            <div class="breadcrumb__wrapper" >
                <span onclick="history.back()" class="select__plan" >Select Plan</span>
                <span class="toogleBtn" id="paymentDetais" >Payment Details</span>
                <span class="toggleBtn disbaled" id="orderConfim" >Order Confirmation</span>
            </div>

            <div class="help__wrapper" >
                <a href="https://help.websitespeedy.com/faqs" target="_blank" >Help</a>
            </div>
    </div>

 
    <div id="paymentContainer" class="payment_addon_s">

    <div class="containers" id="main-billing"  style="width: 50%">

      
            <h3>Billing Address</h3>
            <form id="biling-form" action="#" method="POST">

           <input type="hidden" name="change_id" id="change_id" value="<?php echo $resultCart['change_id']; ?>" />
           <input type="hidden" name="sid_id" id="sid_id" value="<?php echo $resultCart['website_id']; ?>" />

                <input type="hidden" id="subscr_plan" class="form-control" name="subscription" placeholder="Plan" required="" value="<?php echo $resultCart['subscription_id'];  ?>">
                 <input type="hidden" id="subscr_plan" class="form-control" name="count_site" placeholder="site-count" required="" value="1">
                  
            <div class="sm__line__flex" >     
                <div class="form-group"> 
                <label for="fname"><i class="fa fa-user"></i> Full Name <span class="required-star">*</span></label>
                <input type="text" id="fname" class="form-control" value="<?php echo $sele_run2['full_name'];  ?>" name="firstname" placeholder="Full Name" required>
                </div>
                <div class="form-group">
                <label for="email"><i class="fa fa-envelope"></i> Email <span class="required-star">*</span></label>
                <input type="text" id="emailId" class="form-control" value="<?php echo $sele_run2['email'];  ?>"  name="email" placeholder="john@example.com" required>
                </div>
            </div>

            <div class="sm__line__flex" >
                <div class="form-group">
                                        <label for="country">Country <span class="required-star">*</span></label>
                                        <select class="form-control"  data-id-country="<?=$SelectedCountry?>" id="country" data-country="country" class="form-control" name="country" required>
                                            <option  name="option" value="">
                                Select  Country
                                    </option>
                                            <?php
                                                $list_countries = getTableData( $conn , " list_countries " , " 1 " , "" , 1 ) ;
                                                foreach ($list_countries as $key => $country_data) {
                                                
                                                    $selected = ($country_data["id"] == $row["country"]) ? "selected" : "" ;
                                                
                                                    ?>
                                            <option  <?php if($SelectedCountry==$country_data["id"]){

                                        echo "selected"; 
                                } ?>  value="<?=$country_data["id"]?>" <?=$selected?> ><?=$country_data["name"]?></option>
                                            <?php
                                                }
                                                ?>
                                        </select>

                </div>

                <div class="form-group">
                <label for="adr"><i class="fa fa-address-card-o"></i> Address Line 1 <span class="required-star">*</span></label>
                <input type="text" id="adr" class="form-control" value="<?php echo $sele_run2['address'];  ?>"   name="address" placeholder="House number & street" required>
                </div>

            </div>

            <div class="sm__line__flex" >
                <div class="form-group">
                <label for="adr2"><i class="fa fa-address-card-o"></i> Address Line 2</label>
                <input type="text" id="adr2" class="form-control" value="<?php echo $sele_run2['address_2'];  ?>"   name="address2" placeholder="" required>
                </div>


                <div class="form-group">
                <label for="city"><i class="fa fa-address-card-o"></i> City <span class="required-star">*</span></label>
                <input type="text" id="city" class="form-control" value="<?php echo $sele_run2['city'];  ?>"   name="city" placeholder="" required>
                </div>   
            </div>         
                    
            <div class="sm__line__flex" >
              <div class="form-group">
                <label for="zip">Zip <span class="required-star">*</span></label>
                <input type="text" maxlength="10" minlength="3" id="zip" class="form-control" name="zip" value="<?php echo $sele_run2['zip'];  ?>" placeholder="10001" required>
             </div>  
             <div class="form-group"></div>
            </div>
                <!-- <input type="submit" name="billing-submit" value="Continue to checkout" id="validate_btn" class="btn btn-success"> -->
          </form>

        <?php 
        if($SelectedCountry == "101")
            include("payment-summary-in.php");
          else
            include("payment-summary-us.php");
        ?>  
          
        </div>
    
 <?php 
 if($SelectedCountry == "101")
    include("payment-in.php");
  else
    include("payment-us.php");
?>       

</div>

</div>

</div>


</body>
</html>


<script>

checkFocus();
  $("#state").html('<option value=""> Select State </option>');
                $("#city").html('<option value=""> Select City</option>') ;
    
$("#country").change(function(){
        updateAddress("reload");

        var currentCountry = "<?=$SelectedCountry?>";
        var country = $(this).val() ;
        if(country=="101" && currentCountry == "101" ){
        }
        else{

        }
 

        $.ajax({
            url:"ajax.php",
            method:"POST",
            dataType:"JSON",
            data:{country:country , action:"edit-profile"}
        }).done(function(response)
        {
            if ( response.status == 1 ) {
                $("#state").html(response.message) ;
                $("#city").html('<option value="">Please select a state first.</option>') ;
            }
        }).fail(function(){
            console.log("error") ;
        });
    });

    $("#state").change(function(){

        var state = $(this).val() ;

        $.ajax({
            url:"ajax.php",
            method:"POST",
            dataType:"JSON",
            data:{state:state , action:"edit-profile"}
        }).done(function(response)
        {
            if ( response.status == 1 ) {
                $("#city").html(response.message) ;
            }
        }).fail(function(){
            console.log("error") ;
        });
    });

    
 function selected_url() {
   


    var country = $("#country").attr("data-id-country") ;
    var state = $("#state").attr("data-id-state") ;
    var city = $("#city").attr("data-id-city") ;
       $.ajax({
            url:"ajax.php",
            method:"POST",
            dataType:"JSON",
            data:{country:country,state:state,city:city }
        }).done(function(response)
        {
            if ( response.status == 1 ) {
                $("#state").html(response.options_s) ;
                $("#city").html(response.options_c) ;
            }

        }).fail(function(){
            console.log("error") ;
        });
   

 }
 <?php  if (isset($SelectedCountry))

 {

?>

selected_url() ;

 <?php } ?>
 




function checkFocus(){
    var fname1 = document.getElementById("fname");
    var emailid1 = document.getElementById("emailId");
    var country1 = document.getElementById("country");
    var adr1 = document.getElementById("adr");
    var city1 = document.getElementById("city");
    var zip1 = document.getElementById("zip");


 


        if(fname1.value==""){
            fname1.focus();
        }
        else if(emailid1.value==""){
            emailid1.focus();
        }
        else if(country1.value==""){
            country1.focus();
        }
        else if(adr1.value==""){
            adr1.focus();
        }
        else if(city1.value==""){
            city1.focus();
        }
        else if(zip1.value==""){
            zip1.focus();
        }  


        var name = document.getElementById("name");
        var email = document.getElementById("email");

        if ( name ) {
            if(name.value==""){
                name.focus();
            }   
        }

        if ( email ) {
            if(email.value==""){
                email.focus();
            }   
        }  

  }

</script>

<script>

$("#validate_btn").on('click', function(){
                  //Form validation

                   var flag = 0;   

                  if(!isvalid("#fname")){
                  markinviled("#fname");
                  flag = 1;
                  }
                  else{
                  markvalid("#fname");
                  flag = 0;
                  }

                  if(!isvalid("#email")){
                  markinviled("#email");
                    flag = 1;
                  }
                  else{
                  markvalid("#email");
                    flag = 0;
                  }
                  var email = $('#email').val();
                   if(IsEmail(email)==false){
                    markinviled("#email");
                     flag = 1;
                     
                  } 


                  if(!isvalid("#adr")){
                  markinviled("#adr");
                  flag = 1;
                  }
                  else{
                  markvalid("#adr");
                  flag = 0;
                  }

                  if(!isvalid("#country")){
                  markinviled("#country");
                  flag = 1;
                  }
                  else{
                  markvalid("#country");
                  flag = 0;
                  }


                  function markinviled(selector){
                      
                      $(selector).css("border-color","red");
                  }

                  function markvalid(selector){
                      
                      $(selector).css("border-color","gray");
                  }
     });
     function isvalid(selector){
            if($(selector).val()==""){
             
            return false;
            }
            else{
              return true;
            }
} 
 function IsEmail(email) {
  var regex = /^([a-zA-Z0-9_\.\-\+])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
  if(!regex.test(email)) {
    return false;
  }else{
    return true;
  }  
 }

$("#biling-form input,#biling-form select").blur(function(){
updateAddress("");



});


function updateAddress(xs){

    $.ajax({
      type: "POST",
      url: "saveBillingAddress.php",
      data: $("#biling-form").serialize(),
      dataType: "json",
      encode: true,
    }).done(function (data) {
      console.log(data);
      if(xs =="reload"){
        location.reload();
      }

    });

}

</script>

<script>
  window.onscroll = function() {myFunction()};

  var header = document.getElementById("payment__container");
  var sticky = header.offsetTop;

  function myFunction() {
    if (window.pageYOffset > sticky) {
      header.classList.add("sticky");
    } else {
      header.classList.remove("sticky");
    }
  }
</script>