<?php
// session_start();
require_once("/var/www/html/adminpannel/env.php") ;
include('adminpannel/config.php');
require_once("adminpannel/inc/functions.php");
require 'adminpannel/smtp-send-grid/vendor/autoload.php';


if(isset($_POST['contact_submit'])){

foreach ($_POST as $post_key => $post_value) {

$_POST[$post_key] = $conn->real_escape_string($post_value);


}

extract($_POST);
if(trim($website_url)==""){
    $_SESSION['error'] = "Website URL Is required.";
    header("location: ".HOST_URL) ;
    die;
}

if($customer_status == 'yes'){

$customer_status = 1;

}else{

$customer_status = 0;

}

$name = $name." ".$lname;

$insert =  "INSERT INTO `contact_info`( `name`, `email`, `phone`, `country`, `customer_status`, `website_url`, `category`, `message`,traffic,cms) VALUES ( '$name', '$email', '$phone', '$country', '$customer_status', '$website_url', 'Claim your Free Demo',  '$message','$traffic','$contact_person')";

$insert = $conn->query($insert);

if($insert === TRUE){

// echo 'Done!';



    $emailContent = "
        <div>
            
            <label><b>Name :</b> $name</label><br>
            <label><b>Email :</b> $email</label><br>
            <label><b>Phone Number :</b> $phone</label><br>
            <label><b>Country/Region :</b> $country</label><br>
            <label><b>Website URL :</b> $website_url</label><br>
            <label><b>Monthly Website Traffic :</b> $traffic</label><br>
            <label><b>Website CMS :</b> $contact_person</label><br>
            
        </div>
    ";

$smtpDetail = getSMTPDetail($conn);

    $email_sender = "support@websitespeedy.com";
  

$emailss = new \SendGrid\Mail\Mail(); 
$emailss->setFrom($smtpDetail["from_email"],$smtpDetail["from_name"]);
$emailss->setSubject("Claim your Free Demo");
$emailss->addTo($email_sender,"Website Speddy");
$emailss->addContent("text/html",$emailContent);
$sendgrid = new \SendGrid($smtpDetail["password"]);

                    if (!$sendgrid->Send($emailss)) {
                     $_SESSION['error'] = "Something went wrong please try again.";
                       
                    }else{
                      $_SESSION['success'] = "<span>Thank You</span> for submitting your request, Our team will contact you soon.";
                                            
                    }


}
}
 // $_SESSION['success'] = "Request Sent our team will contact you soon.";
?>


  

<div class="contact__page contact__form__only" id="slide-to-form">
        <div class="section__wrapper">
            <div class="contact__inner__wrapper">

            <div class="inner__padding__bg">
                <div class="heading">  
                    <h2>Claim Your <span>Free Demo</span> Now</h2>
                    <p>One of our Experts will get in touch with you to provide a complete Performance Audit of your website and help you resolve the issues that are slowing down your website and growth</p>
                </div>
                
                <div class="main__content">
             
   <div class="alert-status">
    <?php
            if ( !empty($_SESSION['error']) ) {
                echo '<div class="alert alert-danger alert-dismissible fade show thank__you__message" role="alert"><div>'.$_SESSION['error'].'</div></div>' ;
                unset($_SESSION['error']) ;
                echo '<script> 
                        $("body").addClass("blur");
                    </script>';
            }

            if ( !empty($_SESSION['success']) ) {
                echo '<div class="alert alert-success alert-dismissible fade show thank__you__message" role="alert"><div>'.$_SESSION['success'].'</div></div>' ;
                unset($_SESSION['success']) ;
                echo '<script> 
                        $("body").addClass("blur");
                    </script>';
            }
    ?>
  </div>                               

<form method="POST" id="contactForm" onsubmit="return validation();">

                        <div class="field__wrapper">
                            <label for="name">First Name</label>
                            <input type="text" id="name" name="name" required="">
                        </div>
                        <div class="field__wrapper">
                            <label for="lname">Last Name</label>
                            <input type="text" id="lname" name="lname" required="">
                        </div>
                        <div class="field__wrapper">
                            <label for="email">Email</label>
                            <input type="email" id="email" name="email" required="">
                        </div>
                        <div class="field__wrapper">
                            <label for="phoneno">Phone Number</label>
                            <input type="text" id="phoneno" name="phone" required="">
                        </div>
                        <div class="field__wrapper">
                            <label for="country">Country</label>
                            <select name="country" id="country" required="">
                                <option value="">Please Select</option>
                            <?php

                            $sql =  "SELECT * FROM list_countries";

$query = $conn->query($sql);

while($row = $query->fetch_assoc()){

?>

<option value="<?=$row['sortname']?>"><?=$row['name']?></option>

<?php

}

// print_r($query_data);

                            ?>
                            </select>
                        </div>
                        <div class="field__wrapper">
                            <label for="url">Website URL*</label>
                            <input type="url" id="url" name="website_url" required="">
                        </div>

                        <div class="field__wrapper">
                            <label for="traffic">Monthly Website Traffic</label>
                            <select name="traffic" id="traffic" required>
                                <option value="">Please Select</option>
                                <option value="0-50K">0-50K</option>
                                <option value="50K-100K">50K-100K</option>
                                <option value="100K-300K">100K-300K</option>
                                <option value="300K-1M">300K-1M</option>
                                <option value="1M+">1M+</option>
                            </select>
                        </div>

                        <div class="field__wrapper">
                            <label for="selectCMS">Type of Website</label>
                            <select name="contact_person" id="selectCMS" required>
                                <option value="">Please Select</option>
                                <option value="Shopify">Shopify</option>
                                <option value="Bigcommerce">Bigcommerce</option>
                                <option value="Shift4Shop">Shift4Shop</option>
                                <option value="EKM">EKM</option>
                                <option value="Neto">Neto</option>
                                <option value="AmeriCommerce">AmeriCommerce</option>
                                <option value="WIX">WIX</option>
                                <option value="Custom/Saas">Custom/Saas</option>
                            </select>
                        </div>

                        <p class="note-tag">Website Speedy may use the information you provide to contact you about our products and services. You may unsubscribe at any time. For more information, view our <a href="<?=HOST_URL?>privacy-policy.php" target="_blank">Privacy Policy</a>.</p>

                                          <!-- <div class="g-recaptcha" id="rcaptcha"  data-sitekey="6Leoz1gkAAAAAH_zR0uTCDhMlnWFnzGXFPWqvRXR"></div>  -->


                        <div class="field__wrapper full__width">
                            <label for="ans_">Captcha: <span class="captchaErrs" style="display: none; color: red;">Incorrect Captcha Code</span></label>
                            <div class="group">
                                <p id="question_"></p>  
                                <input id="ans_" type="text"> 
                                <div id="reset_" class="btn">Reset</div>             
                            </div> 
                            <input type="hidden" id="checkFill_">
                        </div> 


                        <div class="field__wrapper full__width">
                            <button type="submit" class="btn" name="contact_submit">Claim Your Free Demo</button>
                        </div>

</form>
        


                    </div>

                </div>

            </div>
        </div>

        
    </div>

<script>


                    function validation(){



                    var name =  $('#name').val();

                    name.trim();

                    var email =  $('#email').val();

                    email.trim();

                    var phone = $('#phoneno').val();


                    phone.trim();

                    var country = $('#country').val();



                    var website_url = $('#url').val();

                    website_url.trim();


                    if(name == null || name == ''){

                    alert('Enter a name!');
                    return false;

                    }

                    if(email == null || email == ''){

                    alert('Enter an email!');
                    return false;
                    }

                    if(phone == null || phone == ''){

                    alert('Enter a phone number');
                    return false;

                    }

                    if(country == 'Please Select' || country == null){

                    alert("Select a country");
                    return false;

                    }

                    if(website_url == null || website_url == ''){

                    alert('Enter a website url');

                    return false;

                    }

                    


                    }

                    
                setTimeout(() => {
                    var msg = document.getElementsByClassName('thank__you__message');
                    var body = document.getElementsByTagName('body');
                    if (typeof msg[0] != 'undefined') {
                        msg[0].style.display = "none";
                        body[0].classList.remove('blur');
                    } 
                    
                }, 5000);
                   

</script>


<script>


// Stat Captcha code

var randomNum1_;
var randomNum2_;
var maxNum_ = 20;
var total_;

randomNum1_ = Math.ceil(Math.random() * maxNum_);
randomNum2_ = Math.ceil(Math.random() * maxNum_);
total_ = randomNum1_ + randomNum2_;

$("#question_").prepend(randomNum1_ + " + " + randomNum2_ + " =");

$("#reset_").on("click", function() {
  randomNum1_ = Math.ceil(Math.random() * maxNum_);
  randomNum2_ = Math.ceil(Math.random() * maxNum_);
  total_ = randomNum1_ + randomNum2_;
  $("#question_").empty();
  $("#ans_").val('');
  $("#question_").prepend(randomNum1_ + " + " + randomNum2_ + " =");
});



// End Captcha code

    let formFooter = document.getElementById('contactForm');

    let btnFormFooter = formFooter.getElementsByTagName('button');

        formFooter.addEventListener('submit', (evt)=> {

  
 

  var input_ = $("#ans_").val();
 
  if (input_ != total_) {
    $(".captchaErrs").show();    
    setTimeout(function(){
        $(".captchaErrs").hide();
    },4000);
    $("#reset_").click();
    evt.preventDefault();
    return false;
  }
  else if($("#checkFill_").val()!=""){
    evt.preventDefault();
    return false;
  }  
  else{

            setTimeout(() => {
                for (let j = 0; j < btnFormFooter.length; j++) {
                    btn[j].setAttribute('disabled', 'true');
                    btn[j].classList.add('no-click');
                    btn[j].innerHTML = '<svg style="background:transparent; height:24px;width: auto;" xmlns="http://www.w3.org/2000/svg" width="135" height="140" viewBox="0 0 135 140" fill="#fff" style="&#10;    background: #000;&#10;"> <rect y="10" width="15" height="120" rx="6"> <animate attributeName="height" begin="0.5s" dur="1s" values="120;110;100;90;80;70;60;50;40;140;120" calcMode="linear" repeatCount="indefinite"/> <animate attributeName="y" begin="0.5s" dur="1s" values="10;15;20;25;30;35;40;45;50;0;10" calcMode="linear" repeatCount="indefinite"/> </rect> <rect x="30" y="10" width="15" height="120" rx="6"> <animate attributeName="height" begin="0.25s" dur="1s" values="120;110;100;90;80;70;60;50;40;140;120" calcMode="linear" repeatCount="indefinite"/> <animate attributeName="y" begin="0.25s" dur="1s" values="10;15;20;25;30;35;40;45;50;0;10" calcMode="linear" repeatCount="indefinite"/> </rect> <rect x="60" width="15" height="140" rx="6"> <animate attributeName="height" begin="0s" dur="1s" values="120;110;100;90;80;70;60;50;40;140;120" calcMode="linear" repeatCount="indefinite"/> <animate attributeName="y" begin="0s" dur="1s" values="10;15;20;25;30;35;40;45;50;0;10" calcMode="linear" repeatCount="indefinite"/> </rect> <rect x="90" y="10" width="15" height="120" rx="6"> <animate attributeName="height" begin="0.25s" dur="1s" values="120;110;100;90;80;70;60;50;40;140;120" calcMode="linear" repeatCount="indefinite"/> <animate attributeName="y" begin="0.25s" dur="1s" values="10;15;20;25;30;35;40;45;50;0;10" calcMode="linear" repeatCount="indefinite"/> </rect> <rect x="120" y="10" width="15" height="120" rx="6"> <animate attributeName="height" begin="0.5s" dur="1s" values="120;110;100;90;80;70;60;50;40;140;120" calcMode="linear" repeatCount="indefinite"/> <animate attributeName="y" begin="0.5s" dur="1s" values="10;15;20;25;30;35;40;45;50;0;10" calcMode="linear" repeatCount="indefinite"/> </rect> </svg>'
                    
                }
            }, 200);

        }

        })
</script>