<?php
// ini_set('display_errors', 1); ini_set('display_startup_errors', 1); error_reporting(E_ALL);
// include('config.php');

// require_once('inc/functions.php') ;

// $additional=501;
// $user_id=501;
// $additional_website_url="https://getbootstrap.com/";

        $additional = mysqli_insert_id($conn);
        $data = google_page_speed_insight($additional_website_url[$i],"desktop") ;

        if ( is_array($data) ) 
        {
            $lighthouseResult = $data["lighthouseResult"] ;
            $requestedUrl = $lighthouseResult["requestedUrl"] ;
            $finalUrl = $lighthouseResult["finalUrl"] ;
            $userAgent = $lighthouseResult["userAgent"] ;
            $fetchTime = $lighthouseResult["fetchTime"] ;
            $environment = $conn->real_escape_string(serialize($lighthouseResult["environment"])) ;
            $runWarnings = $conn->real_escape_string(serialize($lighthouseResult["runWarnings"])) ;
            $configSettings = $conn->real_escape_string(serialize($lighthouseResult["configSettings"])) ;
            $audits = $conn->real_escape_string(serialize($lighthouseResult["audits"])) ;
            $categories = $conn->real_escape_string(serialize($lighthouseResult["categories"])) ;
            $categoryGroups = $conn->real_escape_string(serialize($lighthouseResult["categoryGroups"])) ;
            $i18n = $conn->real_escape_string(serialize($lighthouseResult["i18n"])) ;


            // mobile details
            $mobile_data = google_page_speed_insight($additional_website_url[$i],"mobile") ;

            if ( is_array($mobile_data) ) 
            {
                $mobile_lighthouseResult = $mobile_data["lighthouseResult"] ;

                $mobile_environment = $conn->real_escape_string(serialize($mobile_lighthouseResult["environment"])) ;
                $mobile_runWarnings = $conn->real_escape_string(serialize($mobile_lighthouseResult["runWarnings"])) ;
                $mobile_configSettings = $conn->real_escape_string(serialize($mobile_lighthouseResult["configSettings"])) ;
                $mobile_audits = $conn->real_escape_string(serialize($mobile_lighthouseResult["audits"])) ;
                $mobile_categories = $conn->real_escape_string(serialize($mobile_lighthouseResult["categories"])) ;
                $mobile_categoryGroups = $conn->real_escape_string(serialize($mobile_lighthouseResult["categoryGroups"])) ;
                $mobile_i18n = $conn->real_escape_string(serialize($mobile_lighthouseResult["i18n"])) ;

            }
            else {
                $mobile_lighthouseResult = $mobile_environment = $mobile_runWarnings = $mobile_configSettings = $mobile_audits = $mobile_categories = $mobile_categoryGroups = $mobile_i18n = null ;
            }


            if ($additional){
                $sql = " INSERT INTO pagespeed_report ( website_id , parent_website , requestedUrl , finalUrl , userAgent , fetchTime , environment , runWarnings , configSettings , audits , categories , categoryGroups , i18n , mobile_environment , mobile_runWarnings , mobile_configSettings , mobile_audits , mobile_categories , mobile_categoryGroups , mobile_i18n ) VALUES ( '$additional' , '$user_id' , '$requestedUrl' , '$finalUrl' , '$userAgent' , '$fetchTime' , '$environment' , '$runWarnings' , '$configSettings' , '$audits' , '$categories' , '$categoryGroups' , '$i18n' , '$mobile_environment' , '$mobile_runWarnings' , '$mobile_configSettings' , '$mobile_audits' , '$mobile_categories' , '$mobile_categoryGroups' , '$mobile_i18n' ) " ;
                // echo "sql ".$sql."<br>";    
                if($conn->query($sql)==true){       
                // echo "success";

            }

            }

        }

        
    
