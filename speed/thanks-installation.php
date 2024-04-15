<?php
require_once("/var/www/html/adminpannel/env.php") ;
require_once("adminpannel/config.php");
require_once("adminpannel/inc/functions.php");
 
$analyze = $_GET['ref'];

 $user_id = $_SESSION['user_id'];
 
$plan_country = "";

        
        $get_web = $conn->query(" SELECT id FROM `boost_website` WHERE manager_id = '$user_id' ");
        $d_web = $get_web->fetch_assoc();
        
        if($d_web['country'] != "101"){
            $plan_country = "-us";
        }
        
      


        $get_flow = $conn->query(" SELECT * FROM `admin_users` WHERE id = '$user_id' ");
        $d = $get_flow->fetch_assoc();

        $self_install_team =  $d['self_install_team'];

        if($self_install_team == "self"){

            if ( $d['user_type'] == "Dealify" || $d['user_type'] == "AppSumo" || $d['user_type'] == "DealFuel" ) {
                header("location: ".HOST_URL."adminpannel/dashboard.php") ;
            }
            else {
                header("location: ".HOST_URL."plan".$plan_country.".php?sid=".base64_encode($d_web['id'])) ;
            }
                 // header("location: ".HOST_URL."plan".$plan_country.".php?sid=".base64_encode($d_web['id'])) ;
        }

// echo        base64_encode($d_web['id']);

if (isset($_POST['self'])) {
extract($_POST);
  
         




                 $sql = " UPDATE `admin_users` SET self_install_team = 'self' WHERE `id` = '" . $user_id . "'; ";

                if ($conn->query($sql) === TRUE) {

                    if ( $d['user_type'] == "Dealify" || $d['user_type'] == "AppSumo" || $d['user_type'] == "DealFuel" ) {
                        header("location: ".HOST_URL."adminpannel/dashboard.php") ;
                    }
                    else {
                        header("location: ".HOST_URL."plan".$plan_country.".php?sid=".base64_encode($d_web['id'])) ;
                    }
                    
                }

}
elseif(isset($_POST['wait'])){
      extract($_POST);
       

        $sql = " UPDATE `admin_users` SET self_install_team = 'wait' WHERE `id` = '" . $user_id . "'; ";

        if ($conn->query($sql) === TRUE) {

            $self_install_team = "wait";

        }

}




    
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Installation</title>
    <link rel="icon" type="image/x-icon" href="img/favicon.ico" />
    <?php require_once('inc/style-script.php'); ?>

<!-- Google Tag Manager -->
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','GTM-MK5VN7M');</script>
<!-- End Google Tag Manager -->
 
</head>
<style type="text/css">
    /* The message box is shown when the user clicks on the password field */
#message {
  display:none;
  background: #f1f1f1;
  color: #000;
  position: relative;
  padding: 20px;
  margin-top: 10px;
}

#message p {
  padding: 10px 35px;
  font-size: 18px;
}

/* Add a green text color and a checkmark when the requirements are right */
.valid {
  color: green;
}

.valid:before {
  position: relative;
  left: -35px;
  content: "✔";
}

/* Add a red text color and an "x" when the requirements are wrong */
.invalid {
  color: red;
}

.invalid:before {
  position: relative;
  left: -35px;
  content: "✖";
}
</style>
<body>
    <div class="singup_wrapper customize_wrapper">
        <div class="glass"></div>
        <div class="glass"></div>
        <div class="glass"></div>
        <div class="glass"></div>
        <div class="glass"></div>
        <div class="glass"></div>
        <div class="glass"></div>
        <div class="glass"></div>
        <div class="container singup_container basic__details">
 

            <div class="signup" <?php if($self_install_team != ""){echo "style='display:none'";} ?> >
                    <div class="signup_logo">
                            <a href="<?=HOST_URL?>signup.php" ><img src="<?=$bunny_image?>website_speedy_logo_21.svg"></a>
                    </div>
                
  
                     <form method="post" class="self__install__or__not">

                        <div class="form-group">
                            <div class="row flex-col">

                                <div class="col-12">
                                    <label>Thanks, Our team will be in touch with you soon to assist you with Installation process. 
We will contact you via email or phone. Please make sure to check Spam folder in case you do not get an email in next 12 hours.</label>
                                                           
                                </div>

                             
                        </div>

                    
                       
 
            
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary" name="wait">Thanks, I will wait for the team to contact me</button>
                            <br>
                            <button type="submit" class="btn btn-primary" name="self">I want try self Installation</button>                            

                        </div>
                     </form>
                 


            </div>

        </div>
    </div>



        <div class="container singup_container  will_wait"  <?php if($self_install_team == ""){echo "style='display:none'";} ?>>
 

            <div class="signup">
                    <div class="signup_logo">
                            <a href="<?=HOST_URL?>signup.php" ><img src="./img/signup_logo.png"></a>
                    </div>
                
  
                     

                        <div class="form-group">
                            <div class="row flex-col">

                                <div class="col-12">
                                    <label>Great We will get in touch with you soon, in the mean time you can -</label>
                                                           
                                </div>

                             
                        </div>

                    
                       
 
            
                        <div class="form-group">
                            <a href="<?=HOST_HELP_URL?>" target="_blank">1. Explore our Knowledge base </a> <br>
                            <a href="<?=HOST_URL?>why-website-speed-matters.php" target="_blank">2. Learn Why Speed Matters</a><br> 
                            3. Follow us on Social<br>
                            <div class="social__links">
                            <a href="https://www.facebook.com/websitespeedy" target="_blank"><svg height="30px" width="30px" version="1.1" id="Layer_1" viewBox="0 0 512 512" xml:space="preserve"> <path style="fill:#385C8E;" d="M134.941,272.691h56.123v231.051c0,4.562,3.696,8.258,8.258,8.258h95.159  c4.562,0,8.258-3.696,8.258-8.258V273.78h64.519c4.195,0,7.725-3.148,8.204-7.315l9.799-85.061c0.269-2.34-0.472-4.684-2.038-6.44  c-1.567-1.757-3.81-2.763-6.164-2.763h-74.316V118.88c0-16.073,8.654-24.224,25.726-24.224c2.433,0,48.59,0,48.59,0  c4.562,0,8.258-3.698,8.258-8.258V8.319c0-4.562-3.696-8.258-8.258-8.258h-66.965C309.622,0.038,308.573,0,307.027,0  c-11.619,0-52.006,2.281-83.909,31.63c-35.348,32.524-30.434,71.465-29.26,78.217v62.352h-58.918c-4.562,0-8.258,3.696-8.258,8.258  v83.975C126.683,268.993,130.379,272.691,134.941,272.691z"/> </svg></a>
                            <a href="https://www.instagram.com/websitespeedy/" target="_blank"><svg xmlns="http://www.w3.org/2000/svg" width="30px" height="30px" viewBox="0 0 32 32" fill="none"> <rect x="2" y="2" width="28" height="28" rx="6" fill="url(#paint0_radial_87_7153)"/> <rect x="2" y="2" width="28" height="28" rx="6" fill="url(#paint1_radial_87_7153)"/> <rect x="2" y="2" width="28" height="28" rx="6" fill="url(#paint2_radial_87_7153)"/> <path d="M23 10.5C23 11.3284 22.3284 12 21.5 12C20.6716 12 20 11.3284 20 10.5C20 9.67157 20.6716 9 21.5 9C22.3284 9 23 9.67157 23 10.5Z" fill="white"/> <path fill-rule="evenodd" clip-rule="evenodd" d="M16 21C18.7614 21 21 18.7614 21 16C21 13.2386 18.7614 11 16 11C13.2386 11 11 13.2386 11 16C11 18.7614 13.2386 21 16 21ZM16 19C17.6569 19 19 17.6569 19 16C19 14.3431 17.6569 13 16 13C14.3431 13 13 14.3431 13 16C13 17.6569 14.3431 19 16 19Z" fill="white"/> <path fill-rule="evenodd" clip-rule="evenodd" d="M6 15.6C6 12.2397 6 10.5595 6.65396 9.27606C7.2292 8.14708 8.14708 7.2292 9.27606 6.65396C10.5595 6 12.2397 6 15.6 6H16.4C19.7603 6 21.4405 6 22.7239 6.65396C23.8529 7.2292 24.7708 8.14708 25.346 9.27606C26 10.5595 26 12.2397 26 15.6V16.4C26 19.7603 26 21.4405 25.346 22.7239C24.7708 23.8529 23.8529 24.7708 22.7239 25.346C21.4405 26 19.7603 26 16.4 26H15.6C12.2397 26 10.5595 26 9.27606 25.346C8.14708 24.7708 7.2292 23.8529 6.65396 22.7239C6 21.4405 6 19.7603 6 16.4V15.6ZM15.6 8H16.4C18.1132 8 19.2777 8.00156 20.1779 8.0751C21.0548 8.14674 21.5032 8.27659 21.816 8.43597C22.5686 8.81947 23.1805 9.43139 23.564 10.184C23.7234 10.4968 23.8533 10.9452 23.9249 11.8221C23.9984 12.7223 24 13.8868 24 15.6V16.4C24 18.1132 23.9984 19.2777 23.9249 20.1779C23.8533 21.0548 23.7234 21.5032 23.564 21.816C23.1805 22.5686 22.5686 23.1805 21.816 23.564C21.5032 23.7234 21.0548 23.8533 20.1779 23.9249C19.2777 23.9984 18.1132 24 16.4 24H15.6C13.8868 24 12.7223 23.9984 11.8221 23.9249C10.9452 23.8533 10.4968 23.7234 10.184 23.564C9.43139 23.1805 8.81947 22.5686 8.43597 21.816C8.27659 21.5032 8.14674 21.0548 8.0751 20.1779C8.00156 19.2777 8 18.1132 8 16.4V15.6C8 13.8868 8.00156 12.7223 8.0751 11.8221C8.14674 10.9452 8.27659 10.4968 8.43597 10.184C8.81947 9.43139 9.43139 8.81947 10.184 8.43597C10.4968 8.27659 10.9452 8.14674 11.8221 8.0751C12.7223 8.00156 13.8868 8 15.6 8Z" fill="white"/> <defs> <radialGradient id="paint0_radial_87_7153" cx="0" cy="0" r="1" gradientUnits="userSpaceOnUse" gradientTransform="translate(12 23) rotate(-55.3758) scale(25.5196)"> <stop stop-color="#B13589"/> <stop offset="0.79309" stop-color="#C62F94"/> <stop offset="1" stop-color="#8A3AC8"/> </radialGradient> <radialGradient id="paint1_radial_87_7153" cx="0" cy="0" r="1" gradientUnits="userSpaceOnUse" gradientTransform="translate(11 31) rotate(-65.1363) scale(22.5942)"> <stop stop-color="#E0E8B7"/> <stop offset="0.444662" stop-color="#FB8A2E"/> <stop offset="0.71474" stop-color="#E2425C"/> <stop offset="1" stop-color="#E2425C" stop-opacity="0"/> </radialGradient> <radialGradient id="paint2_radial_87_7153" cx="0" cy="0" r="1" gradientUnits="userSpaceOnUse" gradientTransform="translate(0.500002 3) rotate(-8.1301) scale(38.8909 8.31836)"> <stop offset="0.156701" stop-color="#406ADC"/> <stop offset="0.467799" stop-color="#6A45BE"/> <stop offset="1" stop-color="#6A45BE" stop-opacity="0"/> </radialGradient> </defs> </svg></a>
                            <a href="https://www.linkedin.com/company/websitespeedy/" target="_blank"><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="30px" height="30px" viewBox="0 -2 44 44" version="1.1"> <g id="Icons" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd"> <g id="Color-" transform="translate(-702.000000, -265.000000)" fill="#007EBB"> <path d="M746,305 L736.2754,305 L736.2754,290.9384 C736.2754,287.257796 734.754233,284.74515 731.409219,284.74515 C728.850659,284.74515 727.427799,286.440738 726.765522,288.074854 C726.517168,288.661395 726.555974,289.478453 726.555974,290.295511 L726.555974,305 L716.921919,305 C716.921919,305 717.046096,280.091247 716.921919,277.827047 L726.555974,277.827047 L726.555974,282.091631 C727.125118,280.226996 730.203669,277.565794 735.116416,277.565794 C741.21143,277.565794 746,281.474355 746,289.890824 L746,305 L746,305 Z M707.17921,274.428187 L707.117121,274.428187 C704.0127,274.428187 702,272.350964 702,269.717936 C702,267.033681 704.072201,265 707.238711,265 C710.402634,265 712.348071,267.028559 712.41016,269.710252 C712.41016,272.34328 710.402634,274.428187 707.17921,274.428187 L707.17921,274.428187 L707.17921,274.428187 Z M703.109831,277.827047 L711.685795,277.827047 L711.685795,305 L703.109831,305 L703.109831,277.827047 L703.109831,277.827047 Z" id="LinkedIn"> </path> </g> </g> </svg></a>
                            <a href="https://www.youtube.com/channel/UC044W4qzCU9wiF1DJhl3puA" target="_blank"><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="30px" height="30px" viewBox="0 -7 48 48" version="1.1"> <g id="Icons" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd"> <g id="Color-" transform="translate(-200.000000, -368.000000)" fill="#CE1312"> <path d="M219.044,391.269916 L219.0425,377.687742 L232.0115,384.502244 L219.044,391.269916 Z M247.52,375.334163 C247.52,375.334163 247.0505,372.003199 245.612,370.536366 C243.7865,368.610299 241.7405,368.601235 240.803,368.489448 C234.086,368 224.0105,368 224.0105,368 L223.9895,368 C223.9895,368 213.914,368 207.197,368.489448 C206.258,368.601235 204.2135,368.610299 202.3865,370.536366 C200.948,372.003199 200.48,375.334163 200.48,375.334163 C200.48,375.334163 200,379.246723 200,383.157773 L200,386.82561 C200,390.73817 200.48,394.64922 200.48,394.64922 C200.48,394.64922 200.948,397.980184 202.3865,399.447016 C204.2135,401.373084 206.612,401.312658 207.68,401.513574 C211.52,401.885191 224,402 224,402 C224,402 234.086,401.984894 240.803,401.495446 C241.7405,401.382148 243.7865,401.373084 245.612,399.447016 C247.0505,397.980184 247.52,394.64922 247.52,394.64922 C247.52,394.64922 248,390.73817 248,386.82561 L248,383.157773 C248,379.246723 247.52,375.334163 247.52,375.334163 L247.52,375.334163 Z" id="Youtube"> </path> </g> </g> </svg></a>
                            </div>                           

                        </div>
                     
                 


            </div>

        </div>
    </div>    


</body>

</html>

