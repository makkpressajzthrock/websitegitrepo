<?php
require_once 'config.php'; 
 
// Include the database connection file 
$country = "IND";
include_once 'dbConnect.php'; 

require_once("../adminpannel/config.php") ;
require_once('../adminpannel/inc/functions.php') ;

$subscription = $resultCart['subscription_id'];
$sqlPrice = "SELECT * FROM `plans` WHERE `id` = $subscription";
$resultPrices = mysqli_query($conn, $sqlPrice);
$priceValue = mysqli_fetch_assoc($resultPrices);
$price = $priceValue['s_price'];
$price = number_format((float)$price, 2, '.', '');

$total_tax = 0;
 $sqlTax = "SELECT * FROM `add-tax` WHERE `country_name` ='India'";
$resultTax = mysqli_query($conn, $sqlTax);
$priceTax = mysqli_fetch_assoc($resultTax);
$tax = $priceTax['tax_rate'];
$total_tax = $price*$tax/100;
$total_price = $price + $total_tax;

$total_price = number_format((float)$total_price, 2, '.', '');
$total_tax = number_format((float)$total_tax, 2, '.', '');




?>

  

            <div class="order_summary">
                <h4>Order Summary</h4>

            <div class="table__format" >
                <div class="order">
                  <p class="text"><span><?=$priceValue['name'];?></span> / <span><?=ucfirst($priceValue['interval']);?></span></p>
                  <p class="amount">₹ <?=$price?></p>
                </div>

                <div class="order discount"></div>

              <?php
               if($total_tax!=0){
              ?>  
                <div class="order gst">
                  <p class="text"><span><?=$priceTax['tax_name'];?></span> (<span><?=ucfirst($priceTax['tax_rate']);?></span>%)</p>
                  <p class="amount gst" default="₹ <?=$total_tax?>" >₹ <?=$total_tax?></p>
                </div>
              <?php  
              }
              ?>

            </div>

                <div class="total">
                  <span>Total Amount :</span>₹ 
                  <span class="payble_amt_val"><?=$total_price?></span>
                  
                </div>
              </div>              




            <div class="sm__line__flex">
                <div class="form-group mb-small">
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
                    <label>GST Number <small class="text-danger">(Optional)</small></label>
                    <div class="col-12">
                        <div class="">
                            <div class="col-12">
                                <input type="text" id="gstNumber" class="form-control">
                            </div>
                        </div>
                    </div>

                </div>

            </div>