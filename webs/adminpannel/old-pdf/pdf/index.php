

<?php



//index.php

$message = '';

$connect = new PDO("mysql:host=localhost;dbname=thatdevs_funpa", "thatdevs_funpa", "v8(1nMF)w7)L");



function fetch_customer_data($connect,$campains_id)
{
  $output = '';
  $query = "SELECT * FROM campains where id='$campains_id' ";
  $statement = $connect->prepare($query);
  $statement->execute();
  $result = $statement->fetchAll();


  $output .='
  <div class="container">
  <div class="order__summary__card">
      <div class="heading" style="padding: 10px 0px 20px;">
        <div class="col-md-12 text-center"> 
        <img src="https://www.thatdevsite.com/funpastanew/public/assets/images/logo.png" style="width:130px" />
                        <h3>Order Summary</h3>
                </div>        
            </div>            

     <div class="col-md-12">
           <div class="row order-summary" style="display: block; width: 100%; margin: 0px; padding: 0px"> 
                <div class="" style="width: 70%; padding: 0px; display: inline-block; padding: 0px; position:relative"><label>Order Number : F'.$campains_id.'</label></div>
                <div class="text-right" style="width: 50%; margin: 0px; padding: 0px; display: inline-block; margin-right:100px;"><label>Order Date : '.$result[0]['updated_at'].'</label></div>
                <div class="" style="padding:0px; display:block; width:100%; margin-bottom:30px;position:absolute;top:20px;left:15px;"><label>Account Number : '.$result[0]['account_number'].'</label></div>
           </div>
          </div>';

// Description Table End
      $output .='
        <div class="col-md-12" style="padding: 0px 30px;">
                 <div class="row">
                    <div class="col-12">
            ';

   $amount_rcv = 0; 
   $profit = 0;


          $output .='';
          $output .='<b style="margin-bottom: 8px; display: inline-block;line-height:1.5">Total Sales : </b><span style="display:inline-block;line-height:1.5"> $'.str_replace('.00', '',number_format($result[0]['grand_total'],2)).'</span><br>';

   if($result[0]['tax'] !=0){
          $output .='<b  class="order_tax" style="margin-bottom: 8px; display: inline-block;line-height:1.5" >Tax : </b><span style="display: inline-block;line-height:1.5"> '.$result[0]['tax'].'%</span><br>';
                                         $withTax = ($result[0]['grand_total']/100)*$result[0]['tax']; 
          $output .='<b>Total Tax : </b> $'. (number_format($withTax,2)) .'<br>';
                                         
                                         $withTax = $withTax + $result[0]['grand_total'];

          $output .=' <b style="margin-bottom: 8px; display: inline-block;" >Total Sales With Tax : </b> $'. number_format($withTax,2) .'<br>';
    }                                     

          $output .="<b>Profit Earned</b> : $".str_replace('.00', '',number_format(($withTax-$result[0]['owed_to_funpasta']),2))." (".$result[0]['discount']."%)";
          $output .='';
          $output .='<br> <b style="display: inline-block; margin-top: 6px;">Amount owed to Fun Pasta : </b>';


          $output .='$'.str_replace('.00', '',number_format($result[0]['owed_to_funpasta'],2));


     $output .="
        </div>

<br>
                                        <div class='order-summary-notes'>
                                        <p>A copy of your order summary and detail has been emailed to you.<br>
                                        Please print PAGE ONE of order summary and mail it with a check (written to The Pasta Shoppe) to :<br>
                                        The Pasta Shoppe<br>
                                        PO Box 159245<br>
                                        Nashville, TN 37215</p>
                                        <p>Or call 800-247-0188 to pay by credit card.  Your order will not be processed until payment is received.</p>
                                        
                                        </div>        

        </div></div>

</div>        
            ";

  $output .='<div class="col-md-12" style="height: auto; margin-top:20px;">
           <label class="head">Leader Details </label>
             <div class="row leader_details" style="border: 1px solid #ddd;margin: 6px 0px 20px;padding: 8px 0px; height: 160px;"> 
        <div class=" col-md-12" style="display: block; width:100% ;margin: 0px; padding: 0px; height: 160px;">  ';



//  Leaders Detail                

  $queryUser = "SELECT * FROM users where id='".$result[0]['leader_id']."' ";
  $statementUser = $connect->prepare($queryUser);
  $statementUser->execute();
  $user = $statementUser->fetchAll();




      $output .='<div class=" col-md-4" style="margin-left:20px;width: 28%; display: inline-block; vertical-align: top;"><div>
                  <label class="col-12"><b>First Name </b> : '.$user[0]['first_name'].'</label><br>
                  <label class="col-12"><b>Last Name </b> : '.$user[0]['last_name'].'</label><br>
                  <label class="col-12"><b>Group Name </b> : '.$result[0]['campains_name'].'</label><br>
                  <label class="col-12"><b>Shipping Type </b> : '.$user[0]['shipping_type'].'</label><br>
                  </div></div>
                  ';


//  Leaders Detail End
//  Binning Address

            $output .='<div class=" col-md-4" style="width:30%; display: inline-block; vertical-align: top;"><div>';
 

      $output .='
                  <b style="display: inline-block; margin-bottom:6px">Billing Address </b><br> 
                  <span>'.$result[0]['b_first_name'].' '.$result[0]['b_last_name'].'</span> <br> 

                  <span>'.$result[0]['b_address_1'].' '.$result[0]['b_address_2'].'</span> <br>
                                                    
                  <span>'.$result[0]['b_city'].', '.$result[0]['b_state'].' '.$result[0]['b_zip'].'</span>  
                                                   
                  <span>';


    if($result[0]['b_tax_exempt'] !='') {
      $output .='<b>Tax Exempt</b> : '.$result[0]['b_tax_exempt'].' ';
    }

      $output .='</span> ';

 
            $output .='</div></div>';

//  Binning Address End
//  Shipping Address

            $output .='<div class=" col-md-4" style="width:30%; display: inline-block; vertical-align: top; word-break: break-all; hyphens: auto;"><div>';


 

      $output .='
                  <b style="display: inline-block; margin-bottom:6px">Shipping address</b><br> 
                  <span style="word-break: break-all; display: block;" >'.$result[0]['s_first_name'].' '.$result[0]['s_last_name'].'</span> 

                  <span style="word-break: break-all; display: block;" >'.$result[0]['s_address_1'].' '.$result[0]['s_address_2'].'</span> 
                                                    
                  <span style="word-break: break-all; display: block;" >'.$result[0]['s_city'].', '.$result[0]['s_state'].' '.$result[0]['s_zip'].'</span>  
                                                   
                  <span style="word-break: break-all; display: block;" >';


    if($result[0]['s_tax_exempt'] !='') {
      $output .='<b>Tax Exempt</b> : '.$result[0]['s_tax_exempt'].' ';
    }

      $output .='</span> ';

 
            $output .='</div></div>';    



  $output .='</div></div></div>';


// Shipping Address End
// Description


            $output .='
            <div class="desc" style=" page-break-before: always;">
                <div class="col-md-12">
                 <div class=" summary"> 
                    <div class="col-12">
                    <label>Seller Summary :</label>
                    </div>
                                            
                  </div>

                </div>            
            ';



// Description End
// Description Table            
      $output .=' 
                <div class="data-container" style="width: 98%; margin: 0px auto;"> 
                    <table class="table" style="padding: 5px 0px;border: 1px solid #ddd;width: 97.7%;margin: auto;">
                        <thead style="font-size: 14px;">
                        <tr>
                            <th>First Name</th>
                            <th>Last Name</th>
                            <th>Subgroup</th>
                            <th>Quantity</th>
                            <th>Amount</th>
                         </tr>
                        </thead>
                        <tbody>';



  $queryCO = "SELECT * FROM campaign_orders where  campaign_id='".$result[0]['id']."' ";
  $statementCO = $connect->prepare($queryCO);
  $statementCO->execute();
  $campains = $statementCO->fetchAll();




    $orderCount = 0;
    $Subtotal_qty = 0;
    $Subtotal_amt = 0;
    foreach ($campains as $orders) {   


  $queryD = "SELECT sum(faqs.price*campaign_orders_items.qty) as total_price,sum(campaign_orders_items.qty) as qty FROM faqs ,campaign_orders_items WHERE faqs.id = campaign_orders_items.product_id and campaign_orders_items.campaign_id ='".$orders['id']."' order by faqs.id;";

 $grand_total = 0;
  $statementD = $connect->prepare($queryD);
  $statementD->execute();
  $Data = $statementD->fetchAll();
        
    foreach ($Data as $order_itm) {
    if($order_itm['qty'] >0) {

    $Subtotal_amt += $order_itm['total_price'] ;
    $Subtotal_qty += $order_itm['qty'] ;

      $output .=' 
                    <tr>
                    <td>'.$orders['first_name'].'</td>
                    <td>'.$orders['last_name'].'</td>
                    <td>'.$orders['subgroup'].'</td>
                    <td>'.$order_itm['qty'].'</td>
                    <td>$'.str_replace('.00', '',number_format($order_itm['total_price'],2)).'</td>
                    </tr>

            ';

  }
  }
  }
                                       



    $output .=' 

          </tbody>
            <tfoot>
                        <tr>
                        <td colspan="2"></td>
                        <td>Subtotal</td>
                        <td>'.$Subtotal_qty.'</td>
                        <td>$'.$Subtotal_amt.'</td>
                        </tr>
                    </tfoot>
               </table>
             </div>
             </div>
            ';







  return $output;
}


  $queryData = "SELECT * FROM campains where  order_status = 2 and invoice_sent = 0 limit 1 ";
  $statementData = $connect->prepare($queryData);
  $statementData->execute();
  $order_Data = $statementData->fetchAll();

 if(empty($order_Data))
 {
     die;
 }

include('csv.php');

echo '<link rel="stylesheet" href="pdf.css">';
echo '<link rel="stylesheet" href="bootstrap.min.css">';
echo fetch_customer_data($connect,$order_Data[0]['id']);


  echo $queryUser = "SELECT * FROM users where id='".$order_Data[0]['leader_id']."' ";
  $statementUser = $connect->prepare($queryUser);
  $statementUser->execute();
  $user = $statementUser->fetchAll();


$leader_email = $user[0]['email'];
$leader_name = $user[0]['first_name'].' '.$user[0]['last_name'];

// break here
//  $leader_email = 'rohan@makkpress.com';
 
 
  include('pdf.php');
  $file_name = 'orders/order_F'.$order_Data[0]['id']. '.pdf';
  $file_name_csv = 'orders/order_F'.$order_Data[0]['id']. '.csv';  
  $html_code = '<link rel="stylesheet" href="bootstrap.min.css">';
  $html_code .= fetch_customer_data($connect,$order_Data[0]['id']);
 
  $pdf = new Pdf( );
  $pdf->set_option('isRemoteEnabled', TRUE);
  
  $pdf->load_html($html_code);
  $pdf->render();
  $file = $pdf->output();
  file_put_contents($file_name, $file);
 
//   die;
  require 'class/class.phpmailer.php';
  $mail = new PHPMailer;
  $mail->IsSMTP();                //Sets Mailer to send message using SMTP
  $mail->Host = 'smtp.gmail.com';   //Sets the SMTP hosts of your Email hosting, this for Godaddy
  $mail->Port = 587;                //Sets the default SMTP server port
  $mail->SMTPAuth = true;             //Sets SMTP authentication. Utilizes the Username and Password variables
  $mail->Username = 'suryanshtest78@gmail.com';         //Sets SMTP username
  $mail->Password = 'lxgatoszpbjwvyqm';         //Sets SMTP password
  $mail->SMTPSecure = 'tls';              //Sets connection prefix. Options are "", "ssl" or "tls"
  $mail->From = 'infao@webslesson.info';      //Sets the From email address for the message
  $mail->FromName = 'Fun Pasta';      //Sets the From name of the message
  $mail->AddAddress($leader_email, $leader_name);   //Adds a "To" address
  $mail->WordWrap = 50;             //Sets word wrapping on the body of the message to a given number of characters
  $mail->IsHTML(true);              //Sets message type to HTML       
  $mail->AddAttachment($file_name);             //Adds an attachment from a path on the filesystem
  $mail->AddAttachment($file_name_csv); 
  $mail->Subject = 'Order has been Placed F'.$order_Data[0]['id'];      //Sets the Subject of the message
  $mail->Body = 'Dear : <b>'.$leader_name.'</b> <br> Your Order has been Placed successfully. <br><br> Order Id : <b>F'.$order_Data[0]['id'].'</b>';        //An HTML or plain text message body
  if($mail->Send())               //Send an Email. Return true on success or false on error
  {
    // $message = '<label class="text-success">Customer Details has been send successfully...</label>';
  }
  // unlink($file_name);


  $mail_admin = new PHPMailer;
  $mail_admin->IsSMTP();                //Sets Mailer to send message using SMTP
  $mail_admin->Host = 'smtp.gmail.com';   //Sets the SMTP hosts of your Email hosting, this for Godaddy
  $mail_admin->Port = 587;                //Sets the default SMTP server port
  $mail_admin->SMTPAuth = true;             //Sets SMTP authentication. Utilizes the Username and Password variables
  $mail_admin->Username = 'suryanshtest78@gmail.com';         //Sets SMTP username
  $mail_admin->Password = 'lxgatoszpbjwvyqm';         //Sets SMTP password
  $mail_admin->SMTPSecure = 'tls';              //Sets connection prefix. Options are "", "ssl" or "tls"
  $mail_admin->From = 'infao@webslesson.info';      //Sets the From email address for the message
  $mail_admin->FromName = 'Fun Pasta';      //Sets the From name of the message
  $mail_admin->AddAddress('JTapp@pastashoppe.com'); 
  $mail_admin->WordWrap = 50;             //Sets word wrapping on the body of the message to a given number of characters
  $mail_admin->IsHTML(true);              //Sets message type to HTML       
  $mail_admin->AddAttachment($file_name);             //Adds an attachment from a path on the filesystem
  $mail_admin->AddAttachment($file_name_csv); 
  $mail_admin->Subject = 'Received New Order : '.$order_Data[0]['id'];      //Sets the Subject of the message
  $mail_admin->Body = 'New Order from : <b>'.$leader_email.' </b>';        //An HTML or plain text message body
  if($mail_admin->Send())               //Send an Email. Return true on success or false on error
  {
    // $message = '<label class="text-success">Customer Details has been send successfully...</label>';
  }




  $queryOrderUpdate = "update campains set invoice_sent = 1 where id = ".$order_Data[0]['id']." "; 
  $statementOrderUpdate = $connect->prepare($queryOrderUpdate);
  $statementOrderUpdate->execute();

?>