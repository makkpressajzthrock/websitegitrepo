<?php 
 
session_start();
 include('../adminpannel/session.php');

require_once("../adminpannel/config.php") ;
require_once('../adminpannel/inc/functions.php') ;

require_once("Common.php") ;
 
 

if(isset($_SESSION['user_id']) && !empty($_SESSION["user_id"]))
{}
else{
 if (isset($_SERVER["HTTP_REFERER"])) {
        $_SESSION['error'] = "Plan Not loading please try again.";
        header("Location: " . $_SERVER["HTTP_REFERER"]);

        die;
    }
}

$common = new Common();
$allCountries = $common->getCountries($conn);
 
$managerId=$_SESSION['user_id'];

$sele2 = "SELECT * FROM `billing-address` WHERE `manager_id` = '".$managerId."'";
$sele_con2 = mysqli_query($conn, $sele2);
$sele_run2 = mysqli_fetch_assoc($sele_con2);

 

?>
 
<link rel="stylesheet" type="text/css" href="css/style.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.3/jquery.min.js" integrity="sha512-STof4xm1wgkfm7heWqFJVn58Hm3EtS31XFaagaa8VMReCXAkQnJZ+jEy8PCC/iT18dFy95WcExNHFTqLyp72eQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="../adminpannel/js/scripts.js"></script>

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

    <div class="container" id="main-billing" >

      
            <h3>Billing Address</h3>
            <form id="biling-form" action="<?php if($sele_run2["country"]=="101"){echo 'payment-in.php';}else{ echo 'payment-us.php';} ?> " method="POST">

           <input type="hidden" name="change_id" id="change_id" value="<?php echo $_POST['change_id']; ?>" />
           <input type="hidden" name="sid_id" id="sid_id" value="<?php echo $_POST['sid_id']; ?>" />

                <input type="hidden" id="subscr_plan" class="form-control" name="subscription" placeholder="Plan" required="" value="<?php echo $_POST['subscription'];  ?>">
                 <input type="hidden" id="subscr_plan" class="form-control" name="count_site" placeholder="site-count" required="" value="<?php echo $_POST['count_site'];  ?>">
                  
            <div class="form-group"> 
            <label for="fname"><i class="fa fa-user"></i> Full Name</label>
            <input type="text" id="fname" class="form-control" value="<?php echo $sele_run2['full_name'];  ?>" name="firstname" placeholder="Full Name" required>
            </div>
            <div class="form-group">
            <label for="email"><i class="fa fa-envelope"></i> Email</label>
            <input type="text" id="email" class="form-control" value="<?php echo $sele_run2['email'];  ?>"  name="email" placeholder="john@example.com" required>
            </div>
            <div class="form-group">
            <label for="adr"><i class="fa fa-address-card-o"></i> Address</label>
            <input type="text" id="adr" class="form-control" value="<?php echo $sele_run2['address'];  ?>"   name="address" placeholder="542 W. 15th Street" required>
            </div>
           <div class="form-group">
                                    <label for="country">Country</label>
                                    <select class="form-control"  data-id-country="<?=$sele_run2['country']?>" id="country" data-country="country" class="form-control" name="country" required>
                                        <option  name="option" value="">
                               Select  Country
                                   </option>
                                        <?php
                                            $list_countries = getTableData( $conn , " list_countries " , " 1 " , "" , 1 ) ;
                                            foreach ($list_countries as $key => $country_data) {
                                            
                                                $selected = ($country_data["id"] == $row["country"]) ? "selected" : "" ;
                                            
                                                ?>
                                        <option  <?php if($sele_run2['country']==$country_data["id"]){

                                     echo "selected"; 
                            } ?>  value="<?=$country_data["id"]?>" <?=$selected?> ><?=$country_data["name"]?></option>
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
                <input type="submit" name="billing-submit" value="Continue to checkout" id="validate_btn" class="btn btn-success">
          </form>
        </div>
    
        

</div>
</div>
</div>


<script>


  $("#state").html('<option value=""> Select State </option>');
                $("#city").html('<option value=""> Select City</option>') ;
    
$("#country").change(function(){

        var country = $(this).val() ;
        if(country=="101"){
          $("#biling-form").attr("action","payment-in.php");
        }
        else{
          $("#biling-form").attr("action","payment-us.php");
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