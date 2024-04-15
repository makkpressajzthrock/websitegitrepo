<?php



require_once('../config.php');
require_once('../inc/functions.php') ;
require_once('../smtp/PHPMailerAutoload.php');
require '../smtp-send-grid/vendor/autoload.php';

// $url = "/var/www/html/ecommercespeedy/script/ecmrx/";

 $sele = "select * from  user_subscriptions where user_id=395 ";
$run= mysqli_query($conn,$sele);
while($run_qr=mysqli_fetch_array($run))
{

 $user_id=$run_qr['user_id'];
$plan_id=$run_qr['plan_id'];
$user_subs_id=$run_qr['id'];
$boost_website_id = "";
 

  $sele2 = "select * from  boost_website where manager_id='$user_id' and subscription_id= '$user_subs_id' ";
 $run2=mysqli_query($conn,$sele2);
while($run_qr2=mysqli_fetch_array($run2)){

 // print_r($run_qr2);

$boost_website_id=$run_qr2['id'];
$manager_id=$run_qr2['manager_id'];


}

 
  $sele4 ="select * from  plans where id='$plan_id'";
$run4=mysqli_query($conn,$sele4);
$run_qr4=mysqli_fetch_array($run4);

// print_r($run_qr4);

$pageview= str_replace(',', '', $run_qr4['page_view']);
// echo '<br>';
// die;



 $sele5 ="select * from  admin_users where id='$manager_id'";
$run5=mysqli_query($conn,$sele5);
$run_qr5=mysqli_fetch_assoc($run5);




  
  $date_start = $run_qr["plan_period_start"] ;   
 $date_expire =$run_qr["plan_period_end"] ;   

$visits_count2 = 0;
  $sele7 = "select count(*) as count from  website_visits where visit_date BETWEEN ('$date_start') AND ('$date_expire') and website_id='$boost_website_id' AND manager_id='$manager_id'";
 $run7=mysqli_query($conn,$sele7);
 if($run_qr7=mysqli_fetch_array($run7)){
  $visits_count2 += $run_qr7['count'];
}



// print_r($visits_count2);


// print_r($pageview);
// die();
// echo $boost_website_id;
// echo "<br>";
if($boost_website_id =="381"){
  $visits_count2 = 30001;
}

if($visits_count2 >= $pageview){

  $sele8 ="select * from  script_log where site_id = '$boost_website_id'";
$run8=mysqli_query($conn,$sele8);
while($run_qr8=mysqli_fetch_assoc($run8))
{

$script_site_id= $run_qr8['site_id'];
 $script_url= $run_qr8['url'];

}
// print_r($script_url);
// die;
$old_url=[];
$rename_url=[];
  

$exp_url = explode(",", $script_url);


// $arr = explode("/", $idd);
foreach ($exp_url as $key => $value) {
 
 
   $url='/var/www/html'.$value;
// echo "<br>"; 
  $myfile = fopen($url, "w") or die("Unable to open file!");
  $txt = '';
  fwrite($myfile, $txt); 
  fclose($myfile)

}
die;
// print_r($new_url);

for ($i=0; $i <count($rename_url) ; $i++) { 
 $renameFile= rename($old_url[$i], $rename_url[$i]);

}







$name=$run_qr5['firstname'].''.$run_qr5['lastname'];
$email=$run_qr5['email'];
// $email="karan.makkpress@gmail.com";
echo "email : ".$email."<hr>" ;
	$encode= base64_encode($manager_id);

    $user_id =  $run_qr5["id"]; 
    $get_flow = $conn->query(" SELECT id FROM `boost_website` WHERE manager_id = '$user_id' ");
    $d = $get_flow->fetch_assoc();


$upgrade_plans = HOST_URL."plan.php?sid=".base64_encode($d['id']);
 // print_r($upgrade_plans);
 // die();
// get email content from database ----------

$emailContent = getEmailContent( $conn , 'pageview will complete please upgrade plan');


// set email variable values ----------------
$emailVariables = array("name" => $name , "upgrade-plan" => $upgrade_plans );


// replace variable values from message body ------
foreach($emailVariables as $key1 => $value1) {
    $emailContent["body"] = str_replace('{{'.$key1.'}}', $value1, $emailContent["body"]);
}

// get SMTP detail ---------------
$smtpDetail = getSMTPDetail($conn) ;
// print_r($smtpDetail) ;
 // print_r($emailContent) ; die() ;
// ------------------------------------------------------------------------------------

// send mail ----------------------------------------------------------------


$emailss = new \SendGrid\Mail\Mail(); 
$emailss->setFrom($smtpDetail["from_email"],$smtpDetail["from_name"]);
$emailss->setSubject($emailContent["subject"]);
$emailss->addTo($email,"Website Speddy");
$emailss->addContent("text/html",$emailContent["body"]);
$sendgrid = new \SendGrid($smtpDetail["password"]);
var_dump($sendgrid->Send($emailss));





// $mail = new PHPMailer(); 
// // $mail->SMTPDebug=3;
// $mail->IsSMTP(); 
// $mail->SMTPAuth = true; 
// $mail->SMTPSecure = $smtpDetail["smtp_secure"]; 
// $mail->Host = $smtpDetail["host"];
// $mail->Port = $smtpDetail["port"]; 
// $mail->IsHTML(true);
// $mail->CharSet = 'UTF-8';
// $mail->Username = $smtpDetail["email"] ;
// $mail->Password = $smtpDetail["password"] ;
// $mail->SetFrom($smtpDetail["from_email"],$smtpDetail["from_name"]);
// $mail->addReplyTo($smtpDetail["from_email"],$smtpDetail["from_name"]);
// $mail->Subject = $emailContent["subject"];
// $mail->Body = $emailContent["body"] ;
// $mail->AddAddress($email);
// $mail->SMTPOptions=array('ssl'=>array( 'verify_peer'=>false, 'verify_peer_name'=>false, 'allow_self_signed'=>false ));
// var_dump($mail->Send() );

}else{
  echo "pageview not reached.";
}



}
 

?>