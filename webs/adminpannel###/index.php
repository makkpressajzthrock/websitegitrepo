<?php 


require_once("/var/www/html/adminpannel/env.php") ;
include('config.php');
require_once('inc/functions.php') ;
 

// echo "<pre>";
// print_r($_SESSION);
// print_r($_POST); 

if ( checkUserLogin() ) {
    if ( $_SESSION['role'] == "manager" || $_SESSION['role'] == "team" ) {
        header("location: ".HOST_URL."adminpannel/dashboard.php") ;
    }
    else {
        header("location: ".HOST_URL."adminpannel/home.php") ;
    }
    die() ;
}
// die;

if( isset($_POST['login'])) {

    // print_r($_POST) ;
    $redirect = HOST_URL."adminpannel/" ;

    foreach ($_POST as $key => $value) {
        $_POST[$key] = $conn->real_escape_string($value) ;
    }
 
    extract($_POST) ;

    if ( empty($username) || empty($password) ) {
        $_SESSION['error'] = "Please fill all fields!" ;
    }
    else {

        // check email.
        $query = $conn->query(" SELECT * FROM `admin_users` WHERE email LIKE '$username' ") ;

        if ( $query->num_rows > 0 ) {
            // code...
            $data = $query->fetch_assoc() ;
            // print_r($data) ;

            // check passward .
            $hashpassword = md5($password);
            if ( $hashpassword == $data["password"] ) 
            {

                  $sqlUP = "UPDATE admin_users SET token ='' WHERE id='".$data["id"]."'";
                  $conn->query($sqlUP); 


                if ( $data["status"] == 0 ) {
                   echo $_SESSION["confirm-email"]=$data["email"];
                   
                  $redirect = HOST_URL."signup.php?resend-code=".$data["email"] ;
                              $_SESSION['error'] = "Please Verify Email." ;


                }
              else if ( $data["flow_step"] == 1 ) {
                     $_SESSION['user_id'] = $data["id"] ;
                    $_SESSION['role'] = $data["userstatus"] ;

                       $first_data = getTableData( $conn , " boost_website " , " manager_id = '".$data["id"]."' " , " ORDER BY `boost_website`.`id` ASC " ) ;
                 
                  $plan_country = "";
                  if($data['country'] != "101"){
                    $plan_country = "-us";
                  }
      
                  if($data["sumo_new"] == 0 && $data["sumo_new"]=="register"){
                    $redirect = HOST_URL."plan".$plan_country.".php?sid=".base64_encode($first_data['id']);
                  }else{
                    $redirect = HOST_URL."adminpannel/dashboard.php";

                  }
// die;

                }
                           
                else if ( $data["active_status"] == 1 ) {
                     $_SESSION['user_id'] = $data["id"] ;
                    $_SESSION['role'] = $data["userstatus"] ;

                    if ( $data["userstatus"] == "manager" || $data['userstatus'] == 'team') {

                         $first_data = getTableData( $conn , " boost_website " , " manager_id = '".$data["id"]."' " , " ORDER BY `boost_website`.`id` ASC " ) ;

                         if($first_data['id'] != ''){

                       
                         $redirect = HOST_URL."adminpannel/project-dashboard.php?project=".base64_encode($first_data["id"]) ;
                      
                    
                         }
                         else{
                            $first_data = getTableData( $conn , " boost_website " , " manager_id = '".$data["parent_id"]."' " , " ORDER BY `boost_website`.`id` ASC " ) ;

                             // $redirect = HOST_URL."adminpannel/project-dashboard.php?project=".$first_data["id"] ;

                            $redirect = HOST_URL."customize-flow.php";

                         }


                        //$redirect = HOST_URL."adminpannel/dashboard.php" ;
                    }
                    else {


                        $redirect = HOST_URL."adminpannel/home.php" ;



                    }
                }
                else {
                    $_SESSION['error'] = "your account has been disabled !" ;
                }
            }
            else {
                $_SESSION['error'] = "Invalid email & password!" ;
            }
        }
        else {
            $_SESSION['error'] = "Email doesn't exist!" ;
        }
    }


// echo $redirect;
// die;
    header("location: ".$redirect) ;
    die() ;  
}

?>

<?php require_once("inc/style-and-script.php") ; ?>
<html>
    <head>

  

        <title>Admin Dashboard</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

 

        <link rel="stylesheet" type="text/css" href="css/style.css">
        <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.min.js" ></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js" ></script>
         <script src='https://www.google.com/recaptcha/api.js'></script>


<?php 
if(preg_match('/www/', $_SERVER['HTTP_HOST']))
{
  $url = str_replace("www.","",$_SERVER['HTTP_HOST']);
  header("location: https://$url$_SERVER[REQUEST_URI]");
  die();
}

 $actual_link = "https://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
 $actual_link = explode("?", $actual_link)
?>
<link rel="canonical" href="<?=$actual_link[0]?>" />

        

    </head>
    <body class="custum_liquid">

<!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-MK5VN7M"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->
 <!-- Preview Code Start -->

<?php 
if (!isset($_SESSION['password'])) {
    // code...


 if(isset($_POST['submit'])){

        $password = $_POST['passwrod'];

     if ( $password == null || empty($password) ) {
       $errormsg = "Please fill required values." ;
    }
    else{

        $passvalue="shopifyspeedy.com";

         if($password==$passvalue){

        $_SESSION['password'] = $passvalue ;
         
         ?>

    <script>

   $("#passhide").addClass("d-none");

     
            </script>
         <?php 
         }
         else{
            $errormsg = "Wrong password !" ;
         }
    }

}
 }

?>
 


</div>




<!-- Preview Code End -->

<div class="register-page">
  <div class="register-form">
  <div class="logo-header">
						<a href="<?=HOST_URL?>" ><img src="./img/signup_logo.webp" alt="Website Speedy Logo"></a>
				</div>


    <div class="form-design">
            <h2 class="heading">Login</h2>
           <?php include("inc/alert-status.php") ; ?>
            <form action="#" method="post" id="my_captcha_form" class="form">
                <!-- <h3 style="text-align: center; color: #000;">Admin Login here</h3> -->
                <div class="form-input">
                <label class="lable-design" for="email" class="message ">Email address</label>
                    <input class="input-design" type="text" name="username" required="required" placeholder="Email" autofocus required></input>
                </div>
                <div class="form-input pass__field">
                <label class="lable-design" for="email">Password</label>
                    <input class="input-design" id="password" type="password" name="password" required="required" placeholder="Password" required></input>
                    <div class="icon-show-pass" onclick="show(this)">
                      <svg class="hide" width="30px" height="30px" viewBox="0 0 24 24" fill="none">
                        <path d="M12 16.01C14.2091 16.01 16 14.2191 16 12.01C16 9.80087 14.2091 8.01001 12 8.01001C9.79086 8.01001 8 9.80087 8 12.01C8 14.2191 9.79086 16.01 12 16.01Z" stroke="#000000" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M2 11.98C8.09 1.31996 15.91 1.32996 22 11.98" stroke="#000000" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M22 12.01C15.91 22.67 8.09 22.66 2 12.01" stroke="#000000" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                      </svg>
                      <svg class="show" width="30px" height="30px" viewBox="0 0 24 24" fill="none">
                        <path d="M14.83 9.17999C14.2706 8.61995 13.5576 8.23846 12.7813 8.08386C12.0049 7.92926 11.2002 8.00851 10.4689 8.31152C9.73758 8.61453 9.11264 9.12769 8.67316 9.78607C8.23367 10.4444 7.99938 11.2184 8 12.01C7.99916 13.0663 8.41619 14.08 9.16004 14.83" stroke="#000000" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M12 16.01C13.0609 16.01 14.0783 15.5886 14.8284 14.8384C15.5786 14.0883 16 13.0709 16 12.01" stroke="#000000" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M17.61 6.39004L6.38 17.62C4.6208 15.9966 3.14099 14.0944 2 11.99C6.71 3.76002 12.44 1.89004 17.61 6.39004Z" stroke="#000000" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M20.9994 3L17.6094 6.39" stroke="#000000" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M6.38 17.62L3 21" stroke="#000000" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M19.5695 8.42999C20.4801 9.55186 21.2931 10.7496 21.9995 12.01C17.9995 19.01 13.2695 21.4 8.76953 19.23" stroke="#000000" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                      </svg>
                    </div>
                </div>

               
                  <div class="g-recaptcha" id="rcaptcha" data-sitekey="<?=RECAPTCHA_SITE_KEY?>"></div> 
                 

                <div class="form-input">
                    <button type="submit" class="submit-button" name="login" id="login" >Login</button>
                </div>
            </form>
            <div class="login_buttons" >
                <a href="<?=HOST_URL?>signup.php" class="login_btn reginter_btn">Sign Up</a>
                <a href="forgetpassword.php" class="login_btn forget_btn">Forget Password</a>
            </div>
    </div>



    <div class="footer-design">
      <p class="copyright">Copyright © 2023 <a href="<?=HOST_URL?>">Websitespeedy</a> All rights reserved.</p>
    </div>
  </div>
  <div class="register-detail">
    <!-- <img src="https://websitespeedycdn.b-cdn.net/speedyweb/images/speed-icon.png" class="posotion"> -->
    <div class="design">
        <div class="register-detail-design">
          <div class="item">
            <img src="https://websitespeedycdn.b-cdn.net/speedyweb/images/Web-Vitals.webp">
            <p class="heading">Enhanced User Experience</p>
            <p class="text">Improve user satisfaction on Mobile & desktop with faster loading website.</p>
          </div>
          <div class="item">
          	<img src="https://websitespeedycdn.b-cdn.net/speedyweb/images/seo-new.png">
            <p class="heading">Improved SEO Rankings</p>
            <p class="text">Search engines favor faster websites in their rankings.</p>
          </div>
          <div class="item">
          	<img src="https://websitespeedycdn.b-cdn.net/speedyweb/images/conversion-rate-1.webp">
            <p class="heading">Cost Savings</p>
            <p class="text">Reduce ad costs, bounce rate & boosts marketing efficiency with faster website.</p>
          </div>
          <div class="item">
            <img src="https://websitespeedycdn.b-cdn.net/speedyweb/images/ads.webp">
            <p class="heading">Uplifted Ad Performance & Conversions</p>
            <p class="text">Improved ad quality scores leads to higher sales through Google & Facebook Ads</p>
          </div>
        </div>
        <div class="register-review_admin">
        <div class="rating__wrpper">
                    <div class="stars">
                                <img loading="lazy" height="20" width="120" src="//websitespeedycdn.b-cdn.net/speedyweb/images/review-stars-five-image-200w.webp" class="fit__content__img" alt="five star rating">
                                    <span>Rated 5 Star Across the Platforms</span>
                    </div>
                    <div class="logos">
                        <a href="//www.capterra.com/p/10005566/Website-Speedy/" aria-label="capterra" target="_blank"><img src="//websitespeedycdn.b-cdn.net/speedyweb/images/capterra-trans-new.webp" alt="Serchen logo" class="capterra"></a>
                        <a href="//www.trustpilot.com/review/websitespeedy.com" aria-label="trustpilot" target="_blank"><img  src="//websitespeedycdn.b-cdn.net/speedyweb/images/trust-pilot-logo.png" alt="Trust Pilot logo" class="trust-pilot"></a>
                        <a href="https://sourceforge.net/software/product/Website-Speedy/" aria-label="sourceforge" target="_blank">
                        <span style="display:none">sourceforge</span>    
                        <svg class="sourceforge" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Layer_1" x="0px" y="0px" viewBox="0 0 653 102.6" style="enable-background:new 0 0 653 102.6;" xml:space="preserve">
                        <style type="text/css">
                            .st0{fill:#ff6600;}
                            .st1{fill:#000000;}
                            .st2{fill:#000000;}
                        </style>
                        <path class="st0" d="M66.9,54.5c0-19.1-6.8-27.8-10.4-31.1c-0.7-0.6-1.8-0.1-1.7,0.9c0.7,10.8-12.9,13.5-12.9,30.4h0     c0,0,0,0.1,0,0.1c0,10.3,7.8,18.7,17.4,18.7c9.6,0,17.4-8.4,17.4-18.7c0,0,0-0.1,0-0.1h0c0-4.8-1.8-9.4-3.6-12.8     c-0.4-0.7-1.4-0.4-1.3,0.2C75.1,56.7,66.9,65.7,66.9,54.5z"></path>
                        <g>
                            <path class="st0" d="M46.2,94.8c-0.4,0-0.9-0.2-1.2-0.5L0.5,49.8c-0.6-0.6-0.6-1.7,0-2.4l47-47C47.8,0.2,48.2,0,48.6,0h13.5         c0.8,0,1.3,0.5,1.5,1c0.2,0.5,0.2,1.2-0.4,1.8L19.1,47c-0.9,0.9-0.9,2.3,0,3.2L54,85.2c0.6,0.6,0.6,1.7,0,2.4l-6.7,6.8         C47,94.6,46.6,94.8,46.2,94.8z"></path>
                        </g>
                        <g>
                            <path class="st0" d="M55.1,102.6c-0.8,0-1.3-0.5-1.5-1c-0.2-0.5-0.2-1.2,0.4-1.8l44.2-44.2c0.4-0.4,0.7-1,0.7-1.6         c0-0.6-0.2-1.2-0.7-1.6L63.2,17.4c-0.6-0.6-0.6-1.7,0-2.4l6.8-6.8c0.3-0.3,0.7-0.5,1.2-0.5S72,8,72.3,8.3l44.4,44.5         c0.3,0.3,0.5,0.7,0.5,1.2s-0.2,0.9-0.5,1.2l-47,47c-0.3,0.3-0.7,0.5-1.2,0.5H55.1z"></path>
                        </g>
                        <g>
                            <g>
                                <path class="st1" d="M167.2,32c-0.2,0.4-0.5,0.6-1,0.6c-0.3,0-0.7-0.2-1.2-0.7c-0.5-0.5-1.2-1-2-1.5c-0.9-0.6-1.9-1.1-3.2-1.5             c-1.3-0.5-2.9-0.7-4.8-0.7c-1.9,0-3.5,0.3-5,0.8c-1.4,0.5-2.6,1.3-3.6,2.2s-1.7,2-2.2,3.2c-0.5,1.2-0.8,2.5-0.8,3.8             c0,1.8,0.4,3.2,1.1,4.4c0.7,1.1,1.7,2.1,3,2.9c1.2,0.8,2.6,1.5,4.2,2c1.6,0.6,3.2,1.1,4.8,1.6c1.6,0.5,3.2,1.1,4.8,1.8             c1.6,0.6,2.9,1.5,4.2,2.4s2.2,2.2,3,3.6c0.7,1.4,1.1,3.2,1.1,5.3c0,2.2-0.4,4.2-1.1,6.1c-0.7,1.9-1.8,3.6-3.2,5             c-1.4,1.4-3.2,2.5-5.2,3.4c-2.1,0.8-4.4,1.2-7,1.2c-3.4,0-6.4-0.6-8.8-1.8c-2.5-1.2-4.6-2.9-6.5-5l1-1.6c0.3-0.4,0.6-0.5,1-0.5             c0.2,0,0.5,0.1,0.8,0.4c0.3,0.3,0.8,0.7,1.2,1.1c0.5,0.4,1.1,0.9,1.8,1.4c0.7,0.5,1.5,1,2.4,1.4c0.9,0.4,1.9,0.8,3.1,1.1             c1.2,0.3,2.5,0.4,4,0.4c2.1,0,3.9-0.3,5.5-0.9c1.6-0.6,3-1.5,4.1-2.5s2-2.4,2.6-3.8c0.6-1.5,0.9-3.1,0.9-4.7             c0-1.8-0.4-3.3-1.1-4.5c-0.7-1.2-1.7-2.2-3-3c-1.2-0.8-2.6-1.5-4.2-2c-1.6-0.5-3.2-1.1-4.8-1.6c-1.6-0.5-3.2-1.1-4.8-1.7             c-1.6-0.6-2.9-1.4-4.2-2.4c-1.2-1-2.2-2.2-3-3.7c-0.7-1.5-1.1-3.3-1.1-5.6c0-1.7,0.3-3.4,1-5c0.7-1.6,1.6-3,2.9-4.3             c1.3-1.2,2.8-2.2,4.7-3c1.9-0.7,4-1.1,6.4-1.1c2.7,0,5.1,0.4,7.3,1.3c2.1,0.9,4.1,2.2,5.9,3.9L167.2,32z"></path>
                                <path class="st2" d="M152.9,78.8c-3.5,0-6.6-0.6-9.1-1.9c-2.5-1.2-4.8-3-6.7-5.1l-0.3-0.3l1.3-2c0.6-0.7,1.1-0.8,1.5-0.8             c0.4,0,0.8,0.2,1.2,0.6c0.3,0.3,0.8,0.7,1.3,1.1c0.5,0.4,1.1,0.9,1.7,1.4c0.7,0.5,1.4,0.9,2.3,1.3c0.9,0.4,1.9,0.8,3,1             c1.1,0.3,2.4,0.4,3.9,0.4c2,0,3.8-0.3,5.3-0.9c1.5-0.6,2.8-1.4,3.9-2.4c1-1,1.9-2.2,2.4-3.6c0.6-1.4,0.8-2.9,0.8-4.5             c0-1.7-0.3-3.1-1-4.2c-0.7-1.1-1.6-2-2.8-2.8c-1.2-0.8-2.5-1.4-4-1.9c-1.5-0.5-3.1-1.1-4.8-1.6c-1.7-0.5-3.3-1.1-4.8-1.7             c-1.6-0.7-3.1-1.5-4.3-2.5c-1.3-1-2.3-2.4-3.1-3.9c-0.8-1.6-1.2-3.5-1.2-5.8c0-1.8,0.3-3.6,1-5.3c0.7-1.7,1.7-3.2,3-4.5             c1.3-1.3,3-2.3,4.9-3.1c1.9-0.8,4.2-1.2,6.6-1.2c2.8,0,5.3,0.4,7.5,1.3c2.2,0.9,4.2,2.3,6.1,4.1l0.3,0.3l-1.1,2.1             c-0.6,1.1-1.7,1.4-3.1,0.1c-0.5-0.4-1.1-0.9-2-1.4c-0.8-0.5-1.9-1-3.1-1.5c-1.2-0.4-2.7-0.7-4.6-0.7c-1.8,0-3.4,0.3-4.8,0.8             c-1.3,0.5-2.5,1.2-3.4,2.1c-0.9,0.9-1.6,1.9-2.1,3c-0.5,1.1-0.7,2.4-0.7,3.6c0,1.6,0.3,3,1,4c0.7,1.1,1.6,2,2.8,2.8             c1.2,0.8,2.5,1.4,4,2c1.5,0.5,3.1,1.1,4.8,1.6c1.6,0.5,3.3,1.1,4.8,1.8c1.6,0.7,3.1,1.5,4.3,2.5c1.3,1,2.3,2.3,3.1,3.8             c0.8,1.5,1.2,3.4,1.2,5.6c0,2.2-0.4,4.4-1.2,6.4c-0.8,2-1.9,3.7-3.4,5.2c-1.5,1.5-3.3,2.6-5.4,3.5             C158.1,78.3,155.6,78.8,152.9,78.8z M138.4,71.3c1.7,1.9,3.7,3.4,6,4.5c2.4,1.2,5.3,1.8,8.6,1.8c2.5,0,4.8-0.4,6.8-1.2             c2-0.8,3.6-1.9,5-3.2c1.3-1.3,2.4-3,3.1-4.8c0.7-1.8,1.1-3.8,1.1-5.9c0-2-0.4-3.7-1-5.1c-0.7-1.3-1.6-2.5-2.8-3.4             c-1.2-0.9-2.5-1.7-4-2.4c-1.5-0.6-3.1-1.2-4.7-1.8c-1.6-0.5-3.2-1.1-4.8-1.6c-1.6-0.6-3-1.3-4.3-2.1c-1.3-0.8-2.3-1.9-3.1-3.1             c-0.8-1.2-1.2-2.8-1.2-4.7c0-1.4,0.3-2.8,0.8-4.1c0.5-1.3,1.3-2.5,2.3-3.4c1-1,2.3-1.8,3.8-2.3c1.5-0.6,3.3-0.8,5.2-0.8             c1.9,0,3.6,0.2,5,0.7c1.3,0.5,2.5,1,3.3,1.6c0.9,0.6,1.6,1.1,2.1,1.6c0.6,0.5,0.8,0.5,0.8,0.5c0.1,0,0.3,0,0.4-0.3l0.7-1.3             c-1.6-1.5-3.4-2.7-5.3-3.5c-2.1-0.8-4.4-1.2-7-1.2c-2.3,0-4.4,0.4-6.2,1.1c-1.8,0.7-3.3,1.7-4.5,2.8c-1.2,1.2-2.1,2.5-2.8,4.1             c-0.6,1.5-0.9,3.1-0.9,4.8c0,2.1,0.4,3.9,1.1,5.3c0.7,1.4,1.6,2.6,2.8,3.5c1.2,0.9,2.5,1.7,4,2.3c1.5,0.6,3.1,1.2,4.7,1.7             c1.6,0.5,3.2,1,4.8,1.6c1.6,0.6,3,1.2,4.3,2.1c1.3,0.8,2.4,1.9,3.1,3.2c0.8,1.3,1.2,2.9,1.2,4.9c0,1.8-0.3,3.4-0.9,5             c-0.6,1.6-1.5,2.9-2.7,4c-1.2,1.1-2.6,2-4.3,2.7c-1.7,0.6-3.6,1-5.7,1c-1.5,0-2.9-0.2-4.2-0.5c-1.2-0.3-2.3-0.7-3.2-1.1             c-0.9-0.4-1.8-0.9-2.5-1.5c-0.7-0.5-1.3-1-1.8-1.4c-0.5-0.4-0.9-0.8-1.2-1.1c-0.3-0.3-0.5-0.3-0.5-0.3c-0.1,0-0.3,0-0.5,0.3             L138.4,71.3z"></path>
                            </g>
                            <g>
                                <path class="st1" d="M226.7,51.6c0,4-0.6,7.6-1.8,10.9c-1.2,3.3-2.9,6.1-5.1,8.4c-2.2,2.3-4.8,4.1-7.8,5.4             c-3,1.3-6.4,1.9-10.1,1.9c-3.6,0-7-0.6-10-1.9c-3-1.3-5.6-3-7.8-5.4c-2.2-2.3-3.9-5.1-5.1-8.4c-1.2-3.3-1.8-6.9-1.8-10.9             c0-4,0.6-7.6,1.8-10.9c1.2-3.3,2.9-6.1,5.1-8.4c2.2-2.3,4.8-4.1,7.8-5.4c3-1.3,6.4-1.9,10-1.9c3.7,0,7.1,0.6,10.1,1.9             c3,1.3,5.6,3,7.8,5.4c2.2,2.3,3.9,5.1,5.1,8.4C226.1,44,226.7,47.6,226.7,51.6z M222.8,51.6c0-3.6-0.5-6.9-1.5-9.8             c-1-2.9-2.4-5.3-4.2-7.3c-1.8-2-4-3.5-6.6-4.6c-2.6-1.1-5.4-1.6-8.5-1.6c-3.1,0-5.9,0.5-8.5,1.6c-2.6,1.1-4.8,2.6-6.6,4.6             c-1.8,2-3.3,4.4-4.3,7.3c-1,2.9-1.5,6.1-1.5,9.8c0,3.6,0.5,6.9,1.5,9.8c1,2.9,2.4,5.3,4.3,7.3c1.8,2,4,3.5,6.6,4.6             c2.6,1.1,5.4,1.6,8.5,1.6c3.1,0,6-0.5,8.5-1.6c2.6-1,4.8-2.6,6.6-4.6c1.8-2,3.2-4.4,4.2-7.3C222.3,58.5,222.8,55.3,222.8,51.6z"></path>
                                <path class="st2" d="M202,78.7c-3.7,0-7.2-0.7-10.2-1.9c-3.1-1.3-5.8-3.1-8-5.5c-2.2-2.4-4-5.2-5.2-8.6c-1.2-3.3-1.9-7.1-1.9-11.1             c0-4,0.6-7.8,1.9-11.1c1.2-3.3,3-6.2,5.2-8.6c2.2-2.4,4.9-4.2,8-5.5c3.1-1.3,6.5-2,10.2-2c3.8,0,7.2,0.7,10.3,1.9             c3.1,1.3,5.8,3.1,8,5.5c2.2,2.4,4,5.3,5.2,8.6c1.2,3.3,1.8,7,1.8,11.1c0,4.1-0.6,7.8-1.8,11.1c-1.2,3.3-3,6.2-5.2,8.6             c-2.2,2.4-4.9,4.2-8,5.5C209.2,78.1,205.7,78.7,202,78.7z M202,25.7c-3.5,0-6.8,0.6-9.8,1.9c-2.9,1.2-5.5,3-7.6,5.2             c-2.1,2.2-3.8,5-4.9,8.2c-1.2,3.2-1.8,6.8-1.8,10.7c0,3.9,0.6,7.5,1.8,10.7c1.2,3.2,2.8,5.9,4.9,8.2c2.1,2.2,4.7,4,7.6,5.2             c2.9,1.2,6.2,1.8,9.8,1.8c3.6,0,6.9-0.6,9.8-1.8c2.9-1.2,5.5-3,7.6-5.2c2.1-2.2,3.8-5,4.9-8.1c1.2-3.2,1.8-6.8,1.8-10.7             c0-3.9-0.6-7.5-1.8-10.7c-1.2-3.2-2.8-5.9-4.9-8.2c-2.1-2.2-4.7-4-7.6-5.2C208.9,26.3,205.6,25.7,202,25.7z"></path>
                            </g>
                            <g>
                                <path class="st1" d="M256.4,74.9c2.5,0,4.7-0.4,6.7-1.3c2-0.9,3.6-2.1,5-3.6c1.4-1.5,2.4-3.4,3.1-5.4c0.7-2.1,1.1-4.3,1.1-6.8             V25.7h3.7v32.1c0,2.9-0.5,5.5-1.4,8c-0.9,2.5-2.2,4.6-3.9,6.5c-1.7,1.8-3.8,3.3-6.2,4.3c-2.4,1-5.2,1.6-8.2,1.6             c-3,0-5.8-0.5-8.2-1.6c-2.4-1.1-4.5-2.5-6.2-4.3c-1.7-1.8-3-4-3.9-6.5c-0.9-2.5-1.4-5.2-1.4-8V25.7h3.8v32c0,2.4,0.4,4.7,1.1,6.8             c0.7,2.1,1.8,3.9,3.1,5.4c1.4,1.5,3,2.7,5,3.6C251.6,74.5,253.9,74.9,256.4,74.9z"></path>
                                <path class="st2" d="M256.4,78.8c-3.1,0-5.9-0.5-8.4-1.6c-2.5-1.1-4.7-2.6-6.4-4.5c-1.7-1.9-3.1-4.2-4-6.7             c-0.9-2.5-1.4-5.3-1.4-8.2V25.1h5v32.7c0,2.3,0.4,4.5,1,6.6c0.7,2,1.7,3.8,3,5.2c1.3,1.5,2.9,2.6,4.8,3.5c1.9,0.8,4,1.3,6.4,1.3             c2.4,0,4.6-0.4,6.4-1.2c1.9-0.8,3.5-2,4.8-3.5c1.3-1.5,2.3-3.2,3-5.2c0.7-2,1-4.2,1-6.6V25.1h5v32.7c0,2.9-0.5,5.7-1.4,8.2             c-0.9,2.5-2.3,4.8-4,6.7c-1.7,1.9-3.9,3.4-6.4,4.5C262.3,78.3,259.5,78.8,256.4,78.8z M237.3,26.3v31.5c0,2.8,0.4,5.4,1.3,7.8             c0.9,2.4,2.1,4.5,3.8,6.3c1.6,1.8,3.6,3.2,6,4.2c2.3,1,5,1.5,8,1.5c2.9,0,5.6-0.5,8-1.5c2.3-1,4.4-2.4,6-4.2             c1.6-1.8,2.9-3.9,3.8-6.3c0.9-2.4,1.3-5,1.3-7.8V26.3h-2.5v31.5c0,2.5-0.4,4.8-1.1,7c-0.7,2.2-1.8,4.1-3.3,5.7             c-1.4,1.6-3.2,2.9-5.2,3.8c-2,0.9-4.4,1.4-6.9,1.4c-2.6,0-4.9-0.5-6.9-1.4c-2-0.9-3.8-2.2-5.2-3.8c-1.4-1.6-2.5-3.5-3.2-5.7             c-0.7-2.1-1.1-4.5-1.1-7V26.3H237.3z"></path>
                            </g>
                            <g>
                                <path class="st1" d="M297.5,51.3c1,0,0.9,0,0.9,0l2.2,0c2.3,0,4.4-0.3,6.2-0.8c1.8-0.6,3.4-1.3,4.6-2.4c1.3-1,2.2-2.3,2.9-3.7             c0.7-1.4,1-3.1,1-4.9c0-3.7-1.2-6.4-3.6-8.2c-2.4-1.8-5.9-2.7-10.6-2.7h-9.5v22.7v2.8v23.5h-3.7V25.7h13.2c6,0,10.5,1.2,13.4,3.5             c3,2.3,4.4,5.7,4.4,10.2c0,2-0.3,3.8-1,5.4c-0.7,1.6-1.7,3.1-3,4.3c-1.3,1.2-2.8,2.3-4.6,3c-1.8,0.8-3.9,1.3-6.1,1.6             c0.6,0.4,1.1,0.9,1.6,1.5l17.9,22.4h-3.3c-0.4,0-0.7-0.1-1-0.2c-0.3-0.1-0.6-0.4-0.8-0.7l-16.6-21c-0.4-0.5-0.9-0.9-1.3-1.1             c-0.5-0.2-3.4-0.3-4.4-0.3C296.3,51.6,296.7,51.3,297.5,51.3z"></path>
                                <path class="st2" d="M325,78.2h-4.5c-0.5,0-0.9-0.1-1.3-0.3c-0.4-0.2-0.7-0.5-1-0.9l-16.6-21c-0.4-0.5-0.7-0.8-1.1-1             c-0.4-0.1-2.8-0.3-4.1-0.3h-0.6v-2.6c0-0.9,0.2-1.4,1.8-1.4c0.9,0,1,0,1,0l2.2,0c2.2,0,4.2-0.3,6-0.8c1.7-0.5,3.2-1.3,4.4-2.3             c1.2-1,2.1-2.1,2.7-3.5c0.6-1.4,0.9-2.9,0.9-4.6c0-3.5-1.1-6-3.4-7.7c-2.3-1.7-5.7-2.6-10.2-2.6h-8.9v48.9h-5V25.1h13.9             c6.1,0,10.7,1.2,13.8,3.6c3.1,2.4,4.7,6,4.7,10.7c0,2.1-0.4,4-1.1,5.7c-0.7,1.7-1.8,3.2-3.1,4.5c-1.3,1.3-3,2.3-4.8,3.2             c-1.5,0.6-3.1,1.1-4.9,1.4c0.2,0.2,0.4,0.4,0.6,0.7L325,78.2z M296.9,53.5c1.1,0,3.4,0.1,4,0.4c0.6,0.3,1.1,0.7,1.6,1.3l16.6,21             c0.2,0.3,0.4,0.5,0.6,0.6c0.2,0.1,0.4,0.2,0.7,0.2h2l-17.1-21.4c-0.4-0.6-0.9-1-1.4-1.3l-1.5-0.9l1.8-0.2c2.2-0.2,4.2-0.7,5.9-1.5             c1.7-0.8,3.2-1.7,4.5-2.9c1.2-1.2,2.2-2.5,2.8-4.1c0.6-1.6,1-3.3,1-5.2c0-4.3-1.4-7.5-4.2-9.7c-2.8-2.2-7.2-3.3-13-3.3h-12.6V77             h2.5V28h10.1c4.7,0,8.4,0.9,10.9,2.8c2.6,1.9,3.9,4.8,3.9,8.7c0,1.9-0.4,3.6-1,5.1c-0.7,1.5-1.7,2.8-3.1,3.9             c-1.3,1.1-2.9,1.9-4.8,2.5c-1.9,0.6-4,0.9-6.4,0.9l-2.2,0c-0.1,0-0.2,0-0.9,0C297.3,51.9,297,51.9,296.9,53.5z"></path>
                            </g>
                            <g>
                                <path class="st1" d="M367.6,68.8c0.2,0,0.5,0.1,0.6,0.3l1.5,1.6c-1.1,1.1-2.2,2.2-3.5,3.1c-1.3,0.9-2.7,1.7-4.2,2.3             c-1.5,0.6-3.2,1.1-4.9,1.5c-1.8,0.4-3.8,0.5-5.9,0.5c-3.6,0-6.9-0.6-9.9-1.9c-3-1.3-5.6-3-7.7-5.4c-2.1-2.3-3.8-5.1-5-8.4             c-1.2-3.3-1.8-6.9-1.8-10.9c0-3.9,0.6-7.5,1.9-10.8c1.2-3.3,3-6,5.2-8.4c2.2-2.3,4.9-4.1,8-5.4c3.1-1.3,6.6-1.9,10.3-1.9             c1.9,0,3.6,0.1,5.2,0.4c1.6,0.3,3,0.7,4.4,1.2c1.4,0.5,2.6,1.2,3.8,2c1.2,0.8,2.4,1.7,3.5,2.7l-1.1,1.6c-0.2,0.3-0.5,0.4-0.9,0.4             c-0.2,0-0.5-0.1-0.8-0.4c-0.3-0.3-0.8-0.6-1.3-1c-0.5-0.4-1.2-0.8-1.9-1.2c-0.7-0.5-1.6-0.9-2.7-1.2c-1-0.4-2.2-0.7-3.6-1             c-1.3-0.3-2.9-0.4-4.6-0.4c-3.2,0-6.1,0.5-8.7,1.6c-2.6,1.1-4.9,2.6-6.8,4.7c-1.9,2-3.4,4.5-4.5,7.3s-1.6,6.1-1.6,9.7             c0,3.7,0.5,6.9,1.6,9.8c1.1,2.9,2.5,5.3,4.4,7.3c1.9,2,4.1,3.5,6.6,4.6c2.5,1.1,5.3,1.6,8.2,1.6c1.9,0,3.5-0.1,5-0.4             c1.5-0.2,2.8-0.6,4-1.1c1.2-0.5,2.4-1.1,3.4-1.8c1.1-0.7,2.1-1.5,3.1-2.5c0.1-0.1,0.2-0.2,0.3-0.2             C367.3,68.9,367.5,68.8,367.6,68.8z"></path>
                                <path class="st2" d="M351.1,78.8c-3.7,0-7.1-0.7-10.1-1.9c-3.1-1.3-5.7-3.1-7.9-5.5c-2.2-2.4-3.9-5.2-5.1-8.6             c-1.2-3.3-1.8-7.1-1.8-11.1c0-4,0.6-7.7,1.9-11c1.3-3.3,3.1-6.2,5.3-8.6c2.3-2.4,5.1-4.3,8.2-5.6c3.2-1.3,6.7-2,10.6-2             c1.9,0,3.7,0.1,5.3,0.4c1.6,0.3,3.1,0.7,4.5,1.2c1.4,0.5,2.7,1.2,3.9,2c1.2,0.8,2.4,1.7,3.6,2.8l0.4,0.4l-1.4,2.1             c-0.2,0.3-0.6,0.7-1.4,0.7c-0.4,0-0.7-0.2-1.2-0.5c-0.3-0.3-0.8-0.6-1.3-0.9c-0.5-0.4-1.1-0.8-1.9-1.2c-0.7-0.4-1.6-0.8-2.6-1.2             c-1-0.4-2.2-0.7-3.5-0.9c-1.3-0.2-2.8-0.4-4.5-0.4c-3.1,0-5.9,0.5-8.5,1.6c-2.5,1.1-4.8,2.6-6.6,4.5c-1.8,1.9-3.3,4.3-4.3,7.1             c-1,2.8-1.6,6-1.6,9.4c0,3.6,0.5,6.8,1.5,9.6c1,2.8,2.4,5.2,4.2,7.1c1.8,1.9,3.9,3.4,6.4,4.4c2.4,1,5.1,1.5,8,1.5             c1.8,0,3.5-0.1,4.9-0.4c1.4-0.2,2.7-0.6,3.9-1.1c1.2-0.5,2.3-1.1,3.3-1.7c1-0.7,2-1.5,3-2.4c0.2-0.2,0.3-0.2,0.5-0.3             c0.5-0.3,1.3-0.2,1.7,0.3l1.9,2l-0.4,0.4c-1.1,1.2-2.3,2.2-3.6,3.2c-1.3,0.9-2.7,1.8-4.3,2.4c-1.5,0.7-3.2,1.2-5.1,1.5             C355.3,78.6,353.3,78.8,351.1,78.8z M352.2,25.7c-3.7,0-7.1,0.6-10.1,1.9c-3,1.2-5.7,3-7.8,5.3c-2.2,2.3-3.9,5-5.1,8.2             c-1.2,3.2-1.8,6.7-1.8,10.6c0,3.9,0.6,7.5,1.8,10.7c1.2,3.2,2.8,5.9,4.9,8.2c2.1,2.2,4.6,4,7.5,5.2c2.9,1.2,6.1,1.8,9.6,1.8             c2.1,0,4-0.2,5.8-0.5c1.7-0.3,3.4-0.8,4.8-1.5c1.5-0.6,2.8-1.4,4-2.3c1.1-0.8,2.1-1.7,3-2.6l-1.1-1.2c-0.1-0.1-0.2-0.1-0.3,0             c-0.1,0-0.2,0.1-0.3,0.2c-1,0.9-2.1,1.8-3.2,2.5c-1.1,0.7-2.3,1.4-3.5,1.9c-1.3,0.5-2.7,0.9-4.1,1.1c-1.5,0.2-3.2,0.4-5.1,0.4             c-3,0-5.9-0.6-8.5-1.6c-2.6-1.1-4.9-2.7-6.8-4.7c-1.9-2-3.4-4.6-4.5-7.5c-1.1-2.9-1.6-6.3-1.6-10c0-3.6,0.5-6.9,1.6-9.9             c1.1-2.9,2.6-5.5,4.6-7.5c2-2.1,4.3-3.7,7-4.8c2.7-1.1,5.7-1.7,8.9-1.7c1.7,0,3.3,0.1,4.7,0.4c1.4,0.3,2.6,0.6,3.7,1             c1.1,0.4,2,0.8,2.8,1.3c0.8,0.5,1.4,0.9,1.9,1.3c0.5,0.4,1,0.7,1.3,1c0.3,0.3,0.5,0.3,0.5,0.3c0.3,0,0.4-0.1,0.4-0.2l0.8-1.2             c-1-0.9-2-1.6-3-2.3c-1.2-0.8-2.4-1.4-3.7-1.9c-1.3-0.5-2.8-0.9-4.3-1.2C355.7,25.9,354,25.7,352.2,25.7z"></path>
                            </g>
                            <g>
                                <path class="st1" d="M410.3,25.7v3.1H383v21h22.7v3H383v21.6h27.3v3.1h-31.1V25.7H410.3z"></path>
                                <path class="st2" d="M410.9,78.2h-32.3V25.1h32.3v4.3h-27.3v19.7h22.7v4.3h-22.7v20.4h27.3V78.2z M379.8,77h29.9v-1.9h-27.3V52.2             h22.7v-1.8h-22.7V28.2h27.3v-1.9h-29.9V77z"></path>
                            </g>
                            <g>
                                <path class="st1" d="M456.8,25.1V33h-23.5v15.7h19.8v7.9h-19.8v21.6h-9.9v-53H456.8z"></path>
                            </g>
                            <g>
                                <path class="st1" d="M514.3,51.6c0,3.9-0.6,7.5-1.9,10.8c-1.3,3.3-3.1,6.2-5.5,8.6c-2.3,2.4-5.2,4.3-8.5,5.7c-3.3,1.4-7,2-11,2             c-4,0-7.7-0.7-11-2c-3.3-1.4-6.1-3.2-8.5-5.7c-2.4-2.4-4.2-5.3-5.5-8.6s-1.9-6.9-1.9-10.8s0.6-7.5,1.9-10.8             c1.3-3.3,3.1-6.2,5.5-8.6c2.4-2.4,5.2-4.3,8.5-5.7c3.3-1.4,7-2,11-2c4,0,7.7,0.7,11,2.1c3.3,1.4,6.1,3.3,8.5,5.7             c2.3,2.4,4.2,5.3,5.5,8.6C513.6,44.1,514.3,47.7,514.3,51.6z M504.2,51.6c0-2.9-0.4-5.5-1.2-7.8c-0.8-2.3-1.9-4.3-3.3-5.9             c-1.4-1.6-3.2-2.8-5.3-3.7c-2.1-0.9-4.4-1.3-7-1.3c-2.6,0-4.9,0.4-7,1.3c-2.1,0.9-3.8,2.1-5.3,3.7c-1.5,1.6-2.6,3.6-3.4,5.9             c-0.8,2.3-1.2,4.9-1.2,7.8s0.4,5.5,1.2,7.8c0.8,2.3,1.9,4.3,3.4,5.9c1.5,1.6,3.2,2.8,5.3,3.7c2.1,0.9,4.4,1.3,7,1.3             c2.6,0,4.9-0.4,7-1.3c2.1-0.9,3.8-2.1,5.3-3.7c1.4-1.6,2.5-3.6,3.3-5.9C503.8,57.1,504.2,54.5,504.2,51.6z"></path>
                            </g>
                            <g>
                                <path class="st1" d="M534.9,50.4l2.3,0c1.9,0,3.5-0.2,4.9-0.7c1.4-0.5,2.5-1.1,3.4-1.9c0.9-0.8,1.6-1.8,2-2.9             c0.4-1.1,0.7-2.4,0.7-3.7c0-2.7-0.9-4.8-2.7-6.2c-1.8-1.4-4.5-2.2-8.1-2.2H531v17.6v7.1v20.7h-9.9v-53h16.2c3.6,0,6.7,0.4,9.3,1.1             c2.6,0.7,4.7,1.8,6.3,3.1c1.6,1.3,2.9,3,3.6,4.8c0.8,1.9,1.2,3.9,1.2,6.2c0,1.8-0.3,3.5-0.8,5.1c-0.5,1.6-1.3,3-2.3,4.3             c-1,1.3-2.2,2.4-3.7,3.4c-1.5,1-3.1,1.8-5,2.3c1.2,0.7,2.3,1.7,3.2,3l13.3,19.6h-8.9c-0.9,0-1.6-0.2-2.2-0.5             c-0.6-0.3-1.1-0.8-1.5-1.5c0,0-11.1-17-11.1-17c-0.3-0.4-0.9-1.3-1.5-1.4c-1.2,0-2.4,0-3.5,0c0,0,0-6,0-6.4             C533.8,50.4,534.9,50.4,534.9,50.4z"></path>
                            </g>
                            <g>
                                <path class="st1" d="M591.4,70.9c2.2,0,4.2-0.2,5.8-0.6c1.6-0.4,3.2-1,4.7-1.7v-12h-6.6c-0.6,0-1.1-0.2-1.5-0.5             c-0.4-0.4-0.6-0.8-0.6-1.3v-5.6h17.6V73c-1.3,1-2.7,1.8-4.2,2.5c-1.5,0.7-3,1.3-4.7,1.8c-1.7,0.5-3.4,0.8-5.3,1             c-1.9,0.2-3.9,0.3-6.1,0.3c-3.9,0-7.4-0.7-10.7-2c-3.3-1.3-6.1-3.2-8.4-5.6c-2.4-2.4-4.2-5.3-5.6-8.6c-1.3-3.3-2-7-2-10.9             c0-4,0.6-7.6,1.9-11c1.3-3.3,3.1-6.2,5.5-8.6c2.4-2.4,5.3-4.3,8.7-5.6c3.4-1.3,7.2-2,11.4-2c4.3,0,8.1,0.6,11.2,1.9             c3.2,1.3,5.8,3,8,5l-2.9,4.5c-0.6,0.9-1.3,1.4-2.2,1.4c-0.6,0-1.2-0.2-1.8-0.6c-0.8-0.5-1.6-0.9-2.4-1.4c-0.8-0.5-1.7-0.9-2.7-1.2             c-1-0.3-2.1-0.6-3.3-0.8c-1.2-0.2-2.7-0.3-4.3-0.3c-2.6,0-5,0.4-7.1,1.3c-2.1,0.9-3.9,2.1-5.4,3.8c-1.5,1.6-2.6,3.6-3.4,5.9             c-0.8,2.3-1.2,4.9-1.2,7.7c0,3.1,0.4,5.8,1.3,8.2c0.9,2.4,2.1,4.4,3.6,6s3.4,2.9,5.5,3.8S588.9,70.9,591.4,70.9z"></path>
                            </g>
                            <g>
                                <path class="st1" d="M645.7,56.8h-16.1v13.4H653v7.9h-33.4v-53H653V33h-23.5v16.3H648v5.8C648,55.1,647.9,56.8,645.7,56.8z"></path>
                            </g>
                        </g>
                        </svg></a>
                        <a href="//www.softwaresuggest.com/website-speedy" aria-label="softwaresuggest" target="_blank"><img src="//websitespeedycdn.b-cdn.net/speedyweb/images/software-suggest_1.webp" alt="Software Suggest logo" class="software-suggest_1"></a>
                    </div>
                </div>
        </div>
            
        </div>
    </div>
  </div>
</div>

<script>
function show(e) {
  var x = document.getElementById("password");
  if (x.type === "password") {
    x.type = "text";
    e.classList.add('swap');
  } else {
    x.type = "password";
    e.classList.remove('swap')
  }
}
</script>        

  
<script>

document.getElementById("my_captcha_form").addEventListener("submit",function(evt)
  {
  
  var response = grecaptcha.getResponse();
  if(response.length == 0) 
  { 
    //reCaptcha not verified
    alert("please select the captcha!"); 
    evt.preventDefault();
    return false;
  }
  //captcha verified
  //do the rest of your validations here
//   let btn = document.getElementById("my_captcha_form").getElementsByTagName('button')
//         setTimeout(() => {
//                 for (let j = 0; j < btn.length; j++) {
//                     btn[j].setAttribute('disabled', 'true');
//                     btn[j].classList.add('no-click');
//                     btn[j].innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" style="background:transparent; height:24px;width: auto;" viewBox="0 0 105 105" fill="#fff" style="&#10;    background: #000;&#10;"> <circle cx="12.5" cy="12.5" r="12.5"> <animate attributeName="fill-opacity" begin="0s" dur="1s" values="1;.2;1" calcMode="linear" repeatCount="indefinite"/> </circle> <circle cx="12.5" cy="52.5" r="12.5" fill-opacity=".5"> <animate attributeName="fill-opacity" begin="100ms" dur="1s" values="1;.2;1" calcMode="linear" repeatCount="indefinite"/> </circle> <circle cx="52.5" cy="12.5" r="12.5"> <animate attributeName="fill-opacity" begin="300ms" dur="1s" values="1;.2;1" calcMode="linear" repeatCount="indefinite"/> </circle> <circle cx="52.5" cy="52.5" r="12.5"> <animate attributeName="fill-opacity" begin="600ms" dur="1s" values="1;.2;1" calcMode="linear" repeatCount="indefinite"/> </circle> <circle cx="92.5" cy="12.5" r="12.5"> <animate attributeName="fill-opacity" begin="800ms" dur="1s" values="1;.2;1" calcMode="linear" repeatCount="indefinite"/> </circle> <circle cx="92.5" cy="52.5" r="12.5"> <animate attributeName="fill-opacity" begin="400ms" dur="1s" values="1;.2;1" calcMode="linear" repeatCount="indefinite"/> </circle> <circle cx="12.5" cy="92.5" r="12.5"> <animate attributeName="fill-opacity" begin="700ms" dur="1s" values="1;.2;1" calcMode="linear" repeatCount="indefinite"/> </circle> <circle cx="52.5" cy="92.5" r="12.5"> <animate attributeName="fill-opacity" begin="500ms" dur="1s" values="1;.2;1" calcMode="linear" repeatCount="indefinite"/> </circle> <circle cx="92.5" cy="92.5" r="12.5"> <animate attributeName="fill-opacity" begin="200ms" dur="1s" values="1;.2;1" calcMode="linear" repeatCount="indefinite"/> </circle> </svg>'                    
//                     }
//             }, 200);
    
//     });
  
});







</script>

<script>
  sessionStorage.clear();
</script>




    </body>
</html>