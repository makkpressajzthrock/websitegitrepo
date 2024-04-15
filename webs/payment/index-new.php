<?php 
 
session_start();
 include('../adminpannel/session.php');
require '../adminpannel/smtp-send-grid/vendor/autoload.php';
// Include configuration file  
require_once 'config.php'; 
 
// Include the database connection file 
$country = "231";
// $country = "<script>countryID</script>";
include_once 'dbConnect.php'; 

require_once("../adminpannel/config.php") ;
require_once('../adminpannel/inc/functions.php') ;

require_once("Common.php") ;
 
// Fetch plans from the database 
$sqlQ = "SELECT * FROM plans"; 
$stmt = $db->prepare($sqlQ); 
$stmt->execute(); 
$result = $stmt->get_result(); 

$count_site = $_POST['count_site'];
$_SESSION["count_site"] = $count_site;
$subscription = $_POST['subscription'];
$managerId=$_SESSION['user_id'];

// if(isset($_SESSION['user_id']) && !empty($_SESSION["user_id"]))
// {

//     print_r($_SESSION);die;
// }
// else{
//  if (isset($_SERVER["HTTP_REFERER"])) {
//         $_SESSION['error'] = "Plan Not loading please try again.";
//         header("Location: " . $_SERVER["HTTP_REFERER"]);

//         die;
//     }
// }

// $common = new Common();
// $allCountries = $common->getCountries($conn);



$sqlPrice = "SELECT `s_price` FROM `plans` WHERE `id` = $subscription";
$resultPrices = mysqli_query($conn, $sqlPrice);
$priceValue = mysqli_fetch_assoc($resultPrices);
$price = $priceValue['s_price'];

$sqlCountry = "SELECT `name` FROM `list_countries` WHERE `id` = $country";
$resultCountry = mysqli_query($conn, $sqlCountry);
$countryName = mysqli_fetch_assoc($resultCountry);
$cName = $countryName['name'];

// print_r($_SESSION);die;


$sqlTax = "SELECT * FROM `add-tax` WHERE `country_name` ='".$cName."'";
$resultTax = mysqli_query($conn, $sqlTax);
$priceTax = mysqli_fetch_assoc($resultTax);
$tax = $priceTax['tax_rate'];
$total_tax = $price*$tax/100;
$total_price = $price + $total_tax;

$sele2 = "SELECT * FROM `billing-address` WHERE `manager_id` = '".$managerId."'";
$sele_con2 = mysqli_query($conn, $sele2);
$sele_run2 = mysqli_fetch_assoc($sele_con2);




if(isset($_POST['billing-submit'])){

$managerId=$_SESSION['user_id'];
$fName = $_POST['firstname'];
$email = $_POST['email'];
$address = $_POST['address'];
$country = $_POST['country'];
$state = $_POST['state'];
$city = $_POST['city'];
$zip = $_POST['zip'];
$plan_type = "Subscription";
$sqlCountry = "SELECT `name` FROM `list_countries` WHERE `id` = $country";
$resultCountry = mysqli_query($conn, $sqlCountry);
$countryName = mysqli_fetch_assoc($resultCountry);
$cName = $countryName['name'];


// $sele = "SELECT * FROM `billing-address` WHERE `manager_id` = '".$managerId."'";
// $sele_con = mysqli_query($conn, $sele);
// $sele_run = mysqli_fetch_assoc($sele_con);

// print_r($sele_run);
// die();
if($sele_run>0){

$update_sql= "UPDATE `billing-address` SET `full_name`='$fName' ,`email`='$email',`address`='$address',`country`='$country' ,`state`='$state',`city`='$city',`zip`='$zip',`plan_type`='$plan_type' WHERE `manager_id`=$managerId";

 $results = mysqli_query($conn,$update_sql);

}
else{
$sql = "INSERT INTO `billing-address` (manager_id, full_name, email, address, country,  state, city, zip,  plan_type) VALUES('".$managerId."','".$fName."','".$email."','".$address."','".$cName."','".$state."','".$city."','".$zip."', '".$plan_type."')";
$result = mysqli_query($conn, $sql);
}
// header("Location: " . $_SERVER["HTTP_REFERER"]);
 
}



 
?>


<script src="https://js.stripe.com/v3/"></script>
<script src="js/checkout_ind.js" STRIPE_PUBLISHABLE_KEY="<?php echo STRIPE_PUBLISHABLE_KEY; ?>" defer></script>
<link rel="stylesheet" type="text/css" href="css/style.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.3/jquery.min.js" integrity="sha512-STof4xm1wgkfm7heWqFJVn58Hm3EtS31XFaagaa8VMReCXAkQnJZ+jEy8PCC/iT18dFy95WcExNHFTqLyp72eQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="../adminpannel/js/scripts.js"></script>

<style>
   /* .panel{
    display: none;
   } */

</style>
<!-- Billing Data -->
<div class="payment_addon_s_wrapper">
          <div class="glass"></div>
        <div class="glass"></div>
        <div class="glass"></div>
        <div class="glass"></div>
        <div class="glass"></div>
        <div class="glass"></div>
        <div class="glass"></div>
        <div class="glass"></div>

        

    <div class="payment_addon_s">

    <div class="container">

    <!-- -------------------Billing address--------------------- -->
    <h3>Billing Address</h3>
        <form id="biling-form" action="" method="POST">

        <input type="hidden" name="change_id" id="change_id" value="<?php echo $_POST['change_id']; ?>" />
        <input type="hidden" name="sid_id" id="sid_id" value="<?php echo $_POST['sid_id']; ?>" />

            <input type="hidden" id="subscr_plan" class="form-control" name="subscription" placeholder="Plan" required="" value="<?php echo $_POST['subscription'];  ?>">
            <input type="hidden" id="subscr_plan" class="form-control" name="count_site" placeholder="site-count" required="" value="<?php echo $_POST['count_site'];  ?>">
            
        <div class="form-group"> 
        <label for="fname"><i class="fa fa-user"></i> Full Name</label>
        <input type="text" id="fname" class="form-control" value="" name="firstname" placeholder="Full Name" required>
        </div>
        <div class="form-group">
        <label for="email"><i class="fa fa-envelope"></i> Email</label>
        <input type="text" id="email" class="form-control" value=""  name="email" placeholder="john@example.com" required>
        </div>
        <div class="form-group">
        <label for="adr"><i class="fa fa-address-card-o"></i> Address</label>
        <input type="text" id="adr" class="form-control" value=""   name="address" placeholder="542 W. 15th Street" required>
        </div>
        <div class="form-group">
                                <label for="country">Country</label>
                                <select class="form-control"  data-id-country="<?=$sele_run2['country']?>" id="country" data-country="country" id="select-country" name="country" required>
                                    <option  name="option" value="" selected>
                        Select  Country
                            </option>
                                    <?php
                                        $list_countries = getTableData( $conn , " list_countries " , " 1 " , "" , 1 ) ;
                                        foreach ($list_countries as $key => $country_data) {
                                        
                                            $selected = ($country_data["id"] == $row["country"]) ? "selected" : "" ;
                                        
                                            ?>
                                    <option  <?php if($sele_run2['country']==$country_data["id"]){

                                echo "selected"; 
                        } ?>  value="<?=$country_data["id"]?>"  ><?=$country_data["name"]?></option>
                                    <?php
                                        }
                                        ?>
                                </select>

                            </div>
                            <div class="form-group">
                                <label for="state">State</label>
                                <select class="form-control" id="state" data-id-state="<?=$sele_run2['state']?>" data-state="state" class="form-control" name="state" required>
                                    <?php
                                        $list_states = getTableData( $conn , " list_states " , " countryId = '".$row["country"]."' " , "" , 1 ) ;



                                        // print_r($list_states);

                                        foreach ($list_states as $key => $state_data) {
                                        
                                        $selected = ($state_data["id"] == $row["state"]) ? "selected" : "" ;
                                    
                                            ?>
                                    <option  <?php if($sele_run2['state']==$state_data["id"]){ echo "selected";    } ?>

                                
                                    value="<?=$state_data["id"]?>"  <?=$selected?> ><?=$state_data["statename"]?></option>
                                    <?php
                                
                                        }

                                        ?>
                                </select>

                            </div>
                            <div class="form-group">
                                <label for="city">City</label>
                                <select class="form-control" id="city"  data-city="city" data-id-city="<?=$sele_run2['city']?>" class="form-control" name="city" required>
                                    <?php
                                        $list_cities = getTableData( $conn , " list_cities " , " state_id = '".$row["state"]."' " , "" , 1 ) ;
                                        foreach ($list_cities as $key => $city_data) {
                                        
                                            $selected = ($city_data["id"] == $row["city"]) ? "selected" : "" ;
                                        
                                            ?>
                                    <option <?php if($sele_run2['city']==$city_data['id']){

                                echo "selected"; 
                        } ?>    value="<?=$city_data["id"]?>" <?=$selected?>  ><?=$city_data["cityName"]?></option>
                                    <?php
                                        }
                                        ?>
                                </select>
                            </div>
        <div class="form-group">
            <label for="zip">Zip</label>
            <input type="text" maxlength="10" minlength="3" id="zip" class="form-control" name="zip" value="<?php echo $sele_run2['zip'];  ?>" placeholder="10001" required>
        </div>  
            <!-- <input type="submit" name="billing-submit" value="Continue to checkout" id="validate_btn" class="btn btn-success"> -->
        </form>

    <!-- -------------------Billing address--------------------- -->

    </div>


<div class="container">
<?php 

$currency = (($country == '101')? '₹' :'$');
  ?>
        <div class="logo-cus"><img src="https://ecommerceseotools.com/ecommercespeedy/adminpannel/img/sitelogo.png" alt="Ecommercespeedy Logo"></div>
    <div class="panel-heading">
        <h3 class="panel-title"> Enter your payment details</h3>
        
        <div class="payble_amt">Payble Amount: <?php echo  "$currency <span class='payble_amt_val'>".$total_price."</span>"; ?></div>
        
        <!-- Plan Info -->
        <div style="display: none;">
            <b>Select Plan:</b>
             

        </div>
    </div>
    <div class="panel-body">
        <!-- Display status message -->
        <div id="paymentResponse" class="hidden"></div>
     
        <!-- Display a subscription form -->
        <form id="subscrFrm">
              <input type="hidden" name="change_id" id="change_id" value="<?php echo $_POST['change_id']; ?>" />
                <input type="hidden" id="subscr_plan" class="form-control" name="subscription" placeholder="Plan" required="" value="<?php echo $_POST['subscription'];  ?>">

            <div class="form-group">
                <label>NAME</label>
                <input type="text" id="name" class="form-control" value="<?php echo $fName; ?>"  placeholder="Enter name" required="" autofocus="">
            </div>
            <div class="form-group">
                <label>EMAIL</label>
                <input type="email" id="email" class="form-control" value="<?php echo $email; ?>" placeholder="Enter email" required="">
            </div>
            
            <div class="form-group">
                <label>CARD INFO</label>
                <div id="card-element" class="form-control">
                    <!-- Stripe.js will create card input elements here -->
                </div>
            </div>
            
            <div class="form-group">
                <label>Coupon Code <small class="text-danger" style="color: red;" id="coupon_err"></small></label>
                <div class="col-12">
                    <div class="row">
                        <div class="col-10">
                            <input type="text" id="coupon_code" class="form-control">
                        </div>
                        <div class="col-2">
                            <button type="button" id="apply_coupon" class="form-control">Apply</button>
                        </div>
                    </div>
                </div>
               <div class="col-12" id="applyed_coupon">

               </div>                

            </div>

             <div class="form-group d__none">
              <input type="hidden" id="price_cal" class="form-control" value="<?php echo $price; ?>"  >
              <input type="hidden" id="tax_price" class="form-control" value="<?php echo $total_tax; ?>"  >
              <input type="hidden" id="t_Price" class="form-control" value="<?php echo $total_price; ?>"  >
              <input type="hidden" id="coupon_id" class="form-control" value=""  >
              <input type="hidden" id="sid_id"  class="form-control" value="<?php echo $_POST['sid_id']; ?>" />   
            </div>

            <!-- Form submit button -->
            <button id="submitBtn" class="btn btn-success" >
                <div class="spinner hidden" id="spinner"><svg version="1.1" id="L7" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 100 100" enable-background="new 0 0 100 100" xml:space="preserve"> <path fill="#fff" d="M31.6,3.5C5.9,13.6-6.6,42.7,3.5,68.4c10.1,25.7,39.2,38.3,64.9,28.1l-3.1-7.9c-21.3,8.4-45.4-2-53.8-23.3 c-8.4-21.3,2-45.4,23.3-53.8L31.6,3.5z"> <animateTransform attributeName="transform" attributeType="XML" type="rotate" dur="2s" from="0 50 50" to="360 50 50" repeatCount="indefinite"></animateTransform> </path> <path fill="#fff" d="M42.3,39.6c5.7-4.3,13.9-3.1,18.1,2.7c4.3,5.7,3.1,13.9-2.7,18.1l4.1,5.5c8.8-6.5,10.6-19,4.1-27.7 c-6.5-8.8-19-10.6-27.7-4.1L42.3,39.6z"> <animateTransform attributeName="transform" attributeType="XML" type="rotate" dur="1s" from="0 50 50" to="-360 50 50" repeatCount="indefinite"></animateTransform> </path> <path fill="#fff" d="M82,35.7C74.1,18,53.4,10.1,35.7,18S10.1,46.6,18,64.3l7.6-3.4c-6-13.5,0-29.3,13.5-35.3s29.3,0,35.3,13.5 L82,35.7z"> <animateTransform attributeName="transform" attributeType="XML" type="rotate" dur="2s" from="0 50 50" to="360 50 50" repeatCount="indefinite"></animateTransform> </path> </svg>
</div>
                <span id="buttonText">Proceed</span>
            </button>
        </form>
        
        <!-- Display processing notification -->
        <div id="frmProcess" class="hidden">
            <span class="ring"></span> Processing...
        </div>
    </div>
</div>
 
</div>
</div>
</div>


<script>

$(document).ready(function() {
  $("#submitBtn").click(function(e){
    var name = $("#fname").val();
    var email = $("#email").val();
    var country = $("#country").val();
    var address = $("#adr").val();
    var state = $("#state").val();
    var city = $("#city").val();
    var zip = $("#zip").val();
    
    console.log(country);
    var request = $.ajax({
    url: "update-payment.php",
    type: "POST",
    data: {name : "Rohan", email : "delhi", address: "", country: "", state: "", city: "", zip: "", plan_type:"Subscription", manager_id:},
    dataType: "json"
    });

    // request.done(function(msg) {
    // $("#log").html( msg );
    // });

    request.fail(function(jqXHR, textStatus) {
    alert( "Request failed: " + textStatus );
    });

  });

});



// -----------------------------------
    $("#state").html('<option value=""> Select State </option>') ;
    $("#city").html('<option value=""> Select City</option>') ;
    
$("#country").change(function(){

        var country = $(this).val() ;
 

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
 <?php  if (isset($sele_run2['country']))

 {

?>

selected_url() ;

 <?php } ?>
 
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
</script>
<script>
    $("#biling-form").submit(function(){
       
 $("#main-billing").css("display", "none");
 $(".panel").css("display", "block");
 
});

// Discount Calculation


let cart_Price = $("#t_Price").val();


    $("#apply_coupon").click(function(){

        var subscr_plan = $("#subscr_plan").val();
        var coupon_code = $("#coupon_code").val();

        if(coupon_code == ""){
            $("#coupon_err").html("Please enter a coupon code.");
            setTimeout(function(){$("#coupon_err").html('');},3000);
        }
        else{


          
               $.ajax({
                    url:"check_coupon.php",
                    method:"POST",
                    dataType:"JSON",
                    data:{subscr_plan:subscr_plan , coupon_code:coupon_code, 'location':'India'}
                }).done(function(response)
                {
                    console.log(response);
                    if ( response.status == 1 ) {
                         console.log(response.status);


                         var final_p = 0;
                         var final_dis = 0;

                         if(response.type=="Percentage")
                         {
                            final_dis = cart_Price*response.discount/100;
                            final_p = cart_Price - (cart_Price*response.discount/100);
                         }
                         else{
                             final_p = cart_Price-response.discount;
                             final_dis = response.discount;
                         }

                         $("#applyed_coupon").html(`<div class="applied-discount"><div class='icon'></div><div><label>${response.tag}</label><label class="remove_coupon">X</label></div></div>
                            <table class="table">
                                <tr><td>Subscription Price</td><td>$${cart_Price}</td></tr>
                                <tr><td>Coupon Applied (${coupon_code})</td><td>-$${final_dis}</td></tr>
                                <tr><td>dddPayble Amount</td><td>$${final_p}</td></tr>
                            </table>`
                            );
                         $("#coupon_id").val(response.coupon_id);
                         // $('#t_Price').val(final_p);
                         $(".payble_amt_val").html(final_p);


                         $("#coupon_code").attr("disabled",true);
                         $("#apply_coupon").attr("disabled",true);

                    }
                    else{
                     $("#coupon_err").html(response.message);   
                     setTimeout(function(){$("#coupon_err").html('');},3000);   
                    }


                }).fail(function(){
                    console.log("error") ;
                });

        }



    });


$("body").on("click",".remove_coupon",function(){
    $("#applyed_coupon").html("");
    $("#coupon_code").attr("disabled",false);
    $("#apply_coupon").attr("disabled",false);  
    $("#coupon_id").val("");
    
    // $('#t_Price').val(cart_Price);
    $(".payble_amt_val").html(cart_Price); 

});

</script>