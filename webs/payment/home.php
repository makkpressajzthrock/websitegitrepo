<?php 
include('session.php');

require_once("../adminpannel/config.php") ;
require_once('../adminpannel/inc/functions.php') ;


if ( !checkUserLogin() ) {
    header("location: ".HOST_URL."login.php") ;
    die() ;
}

$countrys = getTableData( $conn , "list_countries" , "" ,"",1) ;


?>

    <?php require_once('../adminpannel/inc/style-and-script.php') ; ?>

    <link rel="stylesheet" type="text/css" href="css/style.css">
    </head>

<body>


<div class="row">
  <div class="col-75">
    <div class="container">
   
      
        <div class="row">
          <div class="col-50">
            <h3>Billing Address</h3>
            <label for="fname"><i class="fa fa-user"></i> Full Name</label>
            <input type="text" id="fname" name="firstname" placeholder="Full Name" required>
            <label for="email"><i class="fa fa-envelope"></i> Email</label>
            <input type="text" id="email" name="email" placeholder="john@example.com" required>
            <label for="adr"><i class="fa fa-address-card-o"></i> Address</label>


            <input type="text" id="adr" name="address" placeholder="542 W. 15th Street" required>
            <label><i class="fa fa-institution"></i> Country</label>
            <select name="country" id="country" class="country form-control" required>
              <option>Select</option>
            <?php
             foreach ($countrys as $country) {
               echo "<option value='".$country['id']."'>".$country['name']."</option>";
              }

             ?>
           </select>

                <label><i class="fa fa-institution"></i> State</label>
                 <select name="state" id="state" class="state form-control" required>
                  <option>Select</option>
                 </select>

            <div class="row">
              <div class="col-50">
            <label><i class="fa fa-institution"></i> City</label>
            <select name="city" id="city" class="city form-control" required>
                            <option>Select</option>
            </select>
              </div>
              <div class="col-50">
                <label for="zip">Zip</label>
                <input type="text" id="zip" name="zip" placeholder="10001" required>
              </div>
            </div>
          </div>

          <div class="col-50">
           
          </div>
          
        </div>
    
        <input type="submit" value="Continue to checkout" class="btn btn-success">
 
    </div>
  </div>
  <!-- <div class="col-25"> -->
<!--     <div class="container">
      <h4>Cart <span class="price" style="color:black"><i class="fa fa-shopping-cart"></i> <b>4</b></span></h4>
      <p><a href="#">Product 1</a> <span class="price">$15</span></p>
      <p><a href="#">Product 2</a> <span class="price">$5</span></p>
      <p><a href="#">Product 3</a> <span class="price">$8</span></p>
      <p><a href="#">Product 4</a> <span class="price">$2</span></p>
      <hr>
      <p>Total <span class="price" style="color:black"><b>$30</b></span></p>
    </div> -->
  <!-- </div> -->
</div>

</body>
<script>
  $('.country').on("change", function(){
          var id = this.value;
          $.ajax({
              type: "POST",
              url: "ajax_get_state.php",
              data: {
                  state_id: id
              },
              cache: false,
              success: function(result){
                console.log("result="+result);
                  $('#state').html(result);
                
              }
          });
      });
  $('.state').on("change", function(){
          var id = this.value;
          $.ajax({
              type: "POST",
              url: "ajax_get_state.php",
              data: {
                  city_id: id
              },
              cache: false,
              success: function(result){
                console.log("result="+result);
                  $('#city').html(result);
                  // $('#city').html("<option>Select a State first</option>")
              }
          });
      });
</script>
</html>
