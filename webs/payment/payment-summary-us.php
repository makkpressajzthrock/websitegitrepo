<?php

//  error_reporting(E_ALL);
// ini_set('display_errors', '1');
require_once 'config.php'; 
 $country = "US";
include_once 'dbConnect.php'; 

require_once("../adminpannel/config.php") ;
require_once('../adminpannel/inc/functions.php') ;
// echo "<pre>";
// print_r($_POST);
// echo "<br>";
// print_r($_SESSION); 

//123
$price  = $_SESSION['price'];
$subscription = $resultCart['subscription_id'];

//123
if($subscription=='4' || $subscription=='15' ){
  $sqlPrice = "SELECT id, name,other_country_price,s_type,interval_plan,s_price, JSON_UNQUOTE(JSON_EXTRACT(list_of_price, '$.\"$price\"')) AS price    FROM plans    WHERE JSON_UNQUOTE(JSON_EXTRACT(list_of_price, '$.\"$price\"')) IS NOT NULL AND id = $subscription";
  $resultPrices = $conn->query($sqlPrice);
  $priceValue = mysqli_fetch_assoc($resultPrices);
  // echo "planid $subscription ";
  // print_r($priceValue);die;
  $price = $priceValue['price'];
  

  $cName = "No";
  $sqlTax = "SELECT * FROM `add-tax` WHERE `country_name` ='".$cName."'";
  $resultTax = mysqli_query($conn, $sqlTax);
  $priceTax = mysqli_fetch_assoc($resultTax);
  $tax = 0;
  $total_tax = $price*$tax/100;
  $total_price = $price + $total_tax;

  $total_price = number_format((float)$total_price, 2, '.', '');
  $price = number_format((float)$price, 2, '.', '');
}else{
$sqlPrice = "SELECT * FROM `plans` WHERE `id` = $subscription";
$resultPrices = mysqli_query($conn, $sqlPrice);
$priceValue = mysqli_fetch_assoc($resultPrices);
// // print_r($resultPrices);
// echo "<pre>";
// print_r($priceValue ); die;
$price = $priceValue['other_country_price'];

$cName = "No";
$sqlTax = "SELECT * FROM `add-tax` WHERE `country_name` ='".$cName."'";
$resultTax = mysqli_query($conn, $sqlTax);
$priceTax = mysqli_fetch_assoc($resultTax);
$tax = 0;
$total_tax = $price*$tax/100;
$total_price = $price + $total_tax;

$total_price = number_format((float)$total_price, 2, '.', '');
$price = number_format((float)$price, 2, '.', '');
}


// print_r($priceValue['id'] ); die;
?>

<?php //print_r($priceValue['id'] ); die; ?>

            <!-- //123 cif/else -->
          <?php if($priceValue['id'] =='30' || $priceValue['id'] =='29' ){ ?>

            <div class="order_summary">
                <h4>Order Summary</h4>

                <div class="table__format" >
                  <div class="order">
                    <input type="hidden" id ="plan_name" value="<?php echo $priceValue['name'];?>">
                    <input type="hidden" id ="plan_type" value="<?php echo $priceValue['interval_plan'];?>">
                    <p class="text"><span><?php if($priceValue['name']=='Free'){echo "Basic Plan";}else{echo $priceValue['name'];}?></span> / <span><?=ucfirst($priceValue['interval']);?></span></p>
                    <p class="amount">$<?=$price?>&nbsp;USD</p>
                  </div>
  
                  <div class="order discount"></div>


                  <div class="total">
                    <span>Total Amount :</span>$<span class="payble_amt_val"> <?=$total_price?>&nbsp;USD</span>
                  
                  </div>
                </div>
              </div>
            <?php  } else{?>
              <div class="order_summary">
                <h4>Order Summary</h4>

                <div class="table__format" >
                  <div class="order">
                    <input type="hidden" id ="plan_name" value="<?php echo $priceValue['name'];?>">
                    <input type="hidden" id ="plan_type" value="<?php echo $priceValue['interval_plan'];?>">
                    <p class="text"><span><?php if($priceValue['name']=='Free'){echo "Basic Plan";}else{echo $priceValue['name'];}?></span> / <span><?=ucfirst($priceValue['interval_plan']);?></span></p>
                    <p class="amount">$<?=$price?>&nbsp;USD</p>
                  </div>
  
                  <div class="order discount"></div>


                  <div class="total">
                    <span>Total Amount :</span>$<span class="payble_amt_val"> <?=$total_price?>&nbsp;USD</span>
                  
                  </div>
                </div>
              </div>

            <div class="sm__line__flex single" >
              <div class="form-group mb-small" style="width:75%;">
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


                <div class="form-group mb-small">
                    <label>VAT Number <small class="text-danger">(Optional)</small></label>
                    <div class="col-12">
                        <div class="">
                            <div class="col-12">
                                <input type="text" id="vatNumber" class="form-control">
                            </div>
                        </div>
                    </div>

                </div>

            </div>   

            <?php } ?>