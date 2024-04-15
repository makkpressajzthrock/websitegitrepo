<?php

require_once('config.php');
require_once('inc/functions.php');
 require 'smtp-send-grid/vendor/autoload.php';
    extract($_POST);

 

 $sele_sql="SELECT * FROM generate_script_request WHERE  website_id ='".base64_decode($id)."' AND manager_id = '".$_SESSION['user_id']. "'  and status = 0";

$result=$conn -> query($sele_sql);
if ($result->num_rows > 0) {
    echo 2;
 
} else {



    if (isset($_POST["id"])) {
        $start_date = date_create($start_date);
        $start_date = date_format($start_date, "Y/m/d H:i:s");
        // echo $start_date;
        $end_date = date_create($end_date);
        $end_date = date_format($end_date, "Y/m/d H:i:s");
        // echo $end_date;

         $sql = "INSERT INTO generate_script_request (manager_id, website_id, website_url, traffic, platform, email,timezone,country_code,mob,suitable_date,suitable_time_from,suitable_time_to,script,start_date,end_date) VALUES('".$_SESSION['user_id']."','".base64_decode($id)."','".$website_url."','".$traffic."','".$platform."','".$email."','".$tz."','".$country_code."','".$contact."','".$suitable."','".$from."','".$to."','".$script."','".$start_date."','".$end_date."')";

// Start send email to admin

                    $emailContent = getEmailContent($conn, 'Admin Emails');
                    $body = "
                        <tr><td><b>Request From Button : $step</b> </td></tr>
                        <tr><td>Email : $email </td></tr>
                        <tr><td>Website Url : $website_url </td></tr>
                        <tr><td>Country Code : $country_code </td></tr>
                        <tr><td>Contact Number : $contact </td></tr> 
                        <tr><td>Time Zone : $tz </td></tr> 
                        <tr><td>Suitable Date And Time For Contact <br>
                            Date : $suitable <br>
                            From : $from <br>
                            To : $to
                        </td></tr> 

                    ";
                 
                        $emailContent = str_replace('{{body}}', $body, $emailContent["body"]);
                 

                    // get SMTP detail ---------------
                    $smtpDetail = getSMTPDetail($conn);
 
                    $emailsss = new \SendGrid\Mail\Mail(); 
                    $emailsss->setFrom($smtpDetail["from_email"],$smtpDetail["from_name"]);
                    $emailsss->setSubject("Parameter add request from ".$email);
                    $emailsss->addTo("service@websitespeedy.com","Website Speddy");
                    $emailsss->addContent("text/html",$emailContent);
                    $sendgrid = new \SendGrid($smtpDetail["password"]);
                    $sendgrid->Send($emailsss);

                    $conn->query("INSERT INTO email_logs (user_id, email_subject, email_message, created_at) VALUES('".$_SESSION['user_id']."', 'Parameter Add Request', '".$conn->real_escape_string($emailContent)."', CURDATE())");  
// End email to admin

        if ($conn->query($sql)) { 
            echo json_encode("saved"); 
        }
        else{
             echo json_encode("11"); 
        }

    }


}
   
