<!-- <div class="payment__header confirm__page" >
            <div class="logo-cus"><a href="https://websitespeedy.com/adminpannel/" ><img    style="width: 175px;" src="https://websitespeedy.com/adminpannel/img/sitelogo_s.png" alt="Ecommercespeedy Logo"></a></div>
            <div class="breadcrumb__wrapper" >
               <span onclick="history.back()" class="select__plan disbaled" >Select Plan</span>
               <span class="toogleBtn disbaled" id="paymentDetais" >Payment Details</span>
               <span class="toggleBtn " id="orderConfim" >Order Confirmation</span>
            </div>
            <div class="help__wrapper" >
               <a href="https://help.websitespeedy.com/faqs" target="_blank" >Help</a>
            </div>
         </div>   -->
<?php
   use Dompdf\Dompdf; 
   
// PHP code for PDF generation and email sending
         if($subscrData['plan_id'] =='29' || $subscrData['plan_id'] =='30'){
             
            if (!empty($subscr_id)) {
               // Content generation for the PDF and email     

              

               $emailContent = getEmailContent($conn, 'Thanks Register For All Plan');

					// set email variable values ----------------
					$emailVariables = array("planName" => $plan_name, "planValidity" => $plan_interval, "planPageView"=> $page_view );

					// replace variable values from message body ------
					foreach ($emailVariables as $key1 => $value1) {
						$emailContent["body"] = str_replace('{{' . $key1 . '}}', $value1, $emailContent["body"]);
					}

					// get SMTP detail ---------------
					$smtpDetail = getSMTPDetail($conn);
               // echo "<pre>"; print_r($smtpDetail); die;
					// print_r($emailVariables) ; print_r($emailContent['body']) ; die() ;
					// ------------------------------------------------------------------------------------

					// send mail ----------------------------------------------------------------
               $emailsss = new \SendGrid\Mail\Mail(); 
               $emailsss->setFrom($smtpDetail["from_email"],$smtpDetail["from_name"]);
               $emailsss->setSubject($emailContent["subject"]);
               $emailsss->addTo($customer_email,"Website Speedy");
               $emailsss->addContent("text/html",$emailContent["body"]);
               $sendgrid = new \SendGrid($smtpDetail["password"]);
               $response = $sendgrid->send($emailsss);
               //  if($response){
               //    // echo 'sent';
               // }else{
               //     echo 'not sent';

               //  }


               

               // $conn->query("INSERT INTO email_logs (user_id, email_subject, email_message, created_at) VALUES('$user_id', '".$conn->real_escape_string($emailContent["subject"])."', '".$conn->real_escape_string($emailContent["body"])."', CURDATE())");

            }

         }else{
            
            if (!empty($subscr_id)) {
               // Content generation for the PDF and email

               $data = file_get_contents('https://websitespeedy.com/adminpannel/img/sitelogo_s.png');
               $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);


               $output = "<table style='max-width: 600px;margin: auto;border-collapse: separate;width: 100%;padding: 24px 20px; margin: 30px auto; font-family:arial ; border-radius:8px; box-shadow: rgba(0, 0, 0, 0.1) 0px 0px 5px 0px, rgba(0, 0, 0, 0.1) 0px 0px 1px 0px; color:#000;'>
               <thead><tr><td colspan='2' style='text-align:center;'>
               <img src='$base64' style='max-width:180px;margin-bottom: 15px; width:100%;'>

               </td></tr></thead>
               <tr><td style='padding:24px 0 8px 0; color:#000;font-size: 16px; font-weight:700;' colspan='2'>Payment Information</td></tr>
               <tr><td style='padding:8px 8px 8px 15px; width:50%;'>Reference Number:</td><td> $subscr_id</td></tr>
               <tr><td style='padding:8px 8px 8px 15px; width:50%;'>Subscription ID:</td><td> $stripe_subscription_id</td></tr>
               <tr><td style='padding:8px 8px 8px 15px; width:50%;'>Paid Amount:</td><td>$$paid_amount USD</td></tr>
               <tr><td style='padding:8px 8px 8px 15px; width:50%;'>Status: </td><td>$subscr_status</td></tr>
               <tr><td style='padding:16px 0 8px 0; color:#000;font-size: 16px; font-weight:700;' colspan='2'>Subscription Information</td></tr>
               <tr><td style='padding:8px 8px 8px 15px; width:50%;'>Plan Name:</td><td> $plan_name</td></tr>
               <tr><td style='padding:8px 8px 8px 15px; width:50%;'>Amount: </td><td>$$plan_amount USD</td></tr>
               <tr><td style='padding:8px 8px 8px 15px; width:50%;'>Plan Interval:</td><td> $plan_interval</td></tr>
               <tr><td style='padding:8px 8px 8px 15px; width:50%;'>Period Start: </td><td>$plan_period_start</td></tr>
               <tr><td style='padding:8px 8px 8px 15px; width:50%;'>Period End: </td><td>$plan_period_end</td></tr>
               <tr><td style='padding:16px 0 8px 0; color:#000;font-size: 16px; font-weight:700;' colspan='2'>Customer Information</td></tr>
               <tr><td style='padding:8px 8px 8px 15px; width:50%;'>Email:</td><td>$customer_email</td></tr></table>";
               

               // Instantiate and use the Dompdf class
               
               $dompdf = new Dompdf();
               $dompdf->loadHtml($output);
               $dompdf->setPaper('A4', 'portrait');
               $dompdf->render();
               $pdfContent = $dompdf->output();

               // Clear output buffer before streaming the PDF
               ob_end_clean();


               $base64 = 'https://websitespeedy.com/adminpannel/img/sitelogo_s.png' ;


               $output = "<table style='max-width: 600px;margin: auto;border-collapse: separate;width: 100%;padding: 24px 20px; margin: 30px auto; font-family:arial ; border-radius:8px; box-shadow: rgba(0, 0, 0, 0.1) 0px 0px 5px 0px, rgba(0, 0, 0, 0.1) 0px 0px 1px 0px; color:#000;'>
               <thead><tr><td colspan='2' style='text-align:center;'>
               <img src='$base64' style='max-width:180px;margin-bottom: 15px; width:100%;'>

               </td></tr></thead>
               <tr><td style='padding:24px 0 8px 0; color:#000;font-size: 16px; font-weight:700;' colspan='2'>Payment Information</td></tr>
               <tr><td style='padding:8px 8px 8px 15px; width:50%;'>Reference Number:</td><td> $subscr_id</td></tr>
               <tr><td style='padding:8px 8px 8px 15px; width:50%;'>Subscription ID:</td><td> $stripe_subscription_id</td></tr>
               <tr><td style='padding:8px 8px 8px 15px; width:50%;'>Paid Amount:</td><td>$$paid_amount USD</td></tr>
               <tr><td style='padding:8px 8px 8px 15px; width:50%;'>Status: </td><td>$subscr_status</td></tr>
               <tr><td style='padding:16px 0 8px 0; color:#000;font-size: 16px; font-weight:700;' colspan='2'>Subscription Information</td></tr>
               <tr><td style='padding:8px 8px 8px 15px; width:50%;'>Plan Name:</td><td> $plan_name</td></tr>
               <tr><td style='padding:8px 8px 8px 15px; width:50%;'>Amount: </td><td>$$plan_amount  USD</td></tr>
               <tr><td style='padding:8px 8px 8px 15px; width:50%;'>Plan Interval:</td><td> $plan_interval</td></tr>
               <tr><td style='padding:8px 8px 8px 15px; width:50%;'>Period Start: </td><td>$plan_period_start</td></tr>
               <tr><td style='padding:8px 8px 8px 15px; width:50%;'>Period End: </td><td>$plan_period_end</td></tr>
               <tr><td style='padding:16px 0 8px 0; color:#000;font-size: 16px; font-weight:700;' colspan='2'>Customer Information</td></tr>
               <tr><td style='padding:8px 8px 8px 15px; width:50%;'>Email:</td><td>$customer_email</td></tr></table>";

               // Your SMTP configuration retrieval
               $smtp_login = $db->query("SELECT * FROM smtp_login;");
               $data_smtp = $smtp_login->fetch_assoc();

               // SendGrid Email Sending
               $email = new \SendGrid\Mail\Mail();
               $email->setFrom($data_smtp["from_email"], $data_smtp["from_name"]);
               $email->setSubject('Payment Invoice');
               $email->addTo($customer_email, "Website Speedy");

               // Attach the PDF content as an attachment to the email
               $email->addAttachment(
                  base64_encode($pdfContent),
                  "application/pdf",
                  "payment-status.pdf",
                  "attachment"
               );

               // Add HTML content to the email body
               $email->addContent("text/html", $output);

               // Send the email using SendGrid
               $sendgrid = new \SendGrid($data_smtp["password"]);
               $paymentMail = $sendgrid->send($email);
               
               if($paymentMail){
                        
                  $emailContent = getEmailContent($conn, 'Thanks Register For All Plan');

                  // set email variable values ----------------
                  $emailVariables = array("planName" => $plan_name, "planValidity" => $plan_interval, "planPageView"=> $page_view );

                  // replace variable values from message body ------
                  foreach ($emailVariables as $key1 => $value1) {
                     $emailContent["body"] = str_replace('{{' . $key1 . '}}', $value1, $emailContent["body"]);
                  }

                  // get SMTP detail ---------------
                  $smtpDetail = getSMTPDetail($conn);
                  // echo "<pre>"; print_r($smtpDetail); die;
                  // print_r($emailVariables) ; print_r($emailContent['body']) ; die() ;
                  // ------------------------------------------------------------------------------------

                  // send mail ----------------------------------------------------------------
                  $emailsss = new \SendGrid\Mail\Mail(); 
                  $emailsss->setFrom($smtpDetail["from_email"],$smtpDetail["from_name"]);
                  $emailsss->setSubject($emailContent["subject"]);
                  $emailsss->addTo($customer_email,"Website Speedy");
                  $emailsss->addContent("text/html",$emailContent["body"]);
                  $sendgrid = new \SendGrid($smtpDetail["password"]);
                  $response = $sendgrid->send($emailsss);
               }
            }

         } 
   ?>
