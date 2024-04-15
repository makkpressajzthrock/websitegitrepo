<?php

require_once("/var/www/html/adminpannel/env.php") ;

$meetinglink = "https://makkpress.trafft.com/booking/e/website-speedy-team?t=s&uuid=d27b7a38-772b-43fd-9491-429044a60c32";

// print_r($_SERVER); 

// if ( $_SERVER["HTTP_HOST"] == "164.92.96.37" ) {
//     header("location: ".HOST_URL.$_SERVER["REQUEST_URI"]);
//     die();
// }

// bunnyCDN urls 
$bunny_css = "//websitespeedycdn.b-cdn.net/speedyweb/css/" ;
$bunny_js = "//websitespeedycdn.b-cdn.net/speedyweb/js/" ;
$bunny_image = "//websitespeedycdn.b-cdn.net/speedyweb/images/" ;
$bunny_video = "//websitespeedycdn.b-cdn.net/speedyweb/video/" ;
// END bunnyCDN urls 

if ( $_SERVER["SCRIPT_NAME"] != "/404.php" ) {

    if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') {
        $url = "https://";   
    } 
    else {
        $url = "http://";   
    } 

    $url.= $_SERVER['HTTP_HOST']; 

    $current_url = $url.$_SERVER['REQUEST_URI'];    

    if ( strpos($current_url, '.php/') ) {

        $file_name = $_SERVER["REQUEST_URI"] ; // PHP_SELF
        $count_slash = substr_count($file_name,"/");

        $substr = substr($current_url, strrpos($current_url, '.php/'));
        // echo "substr : ".$substr."<hr>";

        if ( strlen($substr) == 5 ) {
            // die($url.$_SERVER["SCRIPT_NAME"]) ;
            header("location: ".$url.$_SERVER["SCRIPT_NAME"]);
        }
        elseif ( strlen($substr) > 5 ) {
            // die("location: /404.php") ;
            header("location: /404.php");
        }

    }
}

?>

<?php 
include 'redirect.php';
?>


<?php

$currentUrl = $_SERVER['REQUEST_URI'];

if ($currentUrl === "/blogs/google's-pagespeed-insights-tool-apply-recommendations.php") {
    // Redirect to the new page
    $redirectUrl = HOST_URL."blogs/google-pagespeed-insights-tool-apply-recommendations.php";
    header("Location: $redirectUrl");
    exit();
}

if ($currentUrl === "/blogs/google%27s-pagespeed-insights-tool-apply-recommendations.php") {
    // Redirect to the new page
    $redirectUrl = HOST_URL."blogs/google-pagespeed-insights-tool-apply-recommendations.php";
    header("Location: $redirectUrl");
    exit();
}

if ($currentUrl === "/blogs/tips-to-get-100%25-improved-google-page-speed-score.php") {
    // Redirect to the new page
    $redirectUrl = HOST_URL."blogs/tips-to-get-100-percent-improved-google-page-speed-score.php";
    header("Location: $redirectUrl");
    exit();
}

if ($currentUrl === "/blogs/the-ultimate-guide-to-shopify-speed-optimization-to-increase-organic-traaffic.php") {
    // Redirect to the new page
    $redirectUrl = HOST_URL."blogs/the-ultimate-guide-to-bigcommerce-speed-optimization-to-increase-organic-traffic.php";
    header("Location: $redirectUrl");
    exit();
}

if ($currentUrl === "/blogs/the-ultimate-guide-to-bigcommerce-speed-optimization-to-increase-organi-traffic.php") {
    // Redirect to the new page
    $redirectUrl = HOST_URL."blogs/the-ultimate-guide-to-bigcommerce-speed-optimization-to-increase-organic-traffic.php";
    header("Location: $redirectUrl");
    exit();
}

if ($currentUrl === "/case-study//nailary-case-study.php") {
    // Redirect to the new page
    $redirectUrl = HOST_URL."case-study/nailary-case-study.php";
    header("Location: $redirectUrl");
    exit();
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name='robots' content='index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1'>
    <meta name="google-site-verification" content="rfxSRamLmW3fdJrSYcW79JD-EfaNihV8G3nL7rZZE3k" >
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="<?=$bunny_image?>favicon.ico" > 
    
<!-- Google Tag Manager -->
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','GTM-MK5VN7M');</script>
<!-- End Google Tag Manager -->

<script src="https://www.dwin1.com/58969.js" type="text/javascript" defer="defer"></script>

<?php 
if(preg_match('/www/', $_SERVER['HTTP_HOST']))
{
  $url = str_replace("www.","",$_SERVER['HTTP_HOST']);
  header("location: https://$url$_SERVER[REQUEST_URI]");
  die();
}

 $actual_link = "https://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
 $actual_links = explode("?", $actual_link);
?>

<!-- OG Tags -->

<?php
$currentUrl = $_SERVER['REQUEST_URI'];

if ($currentUrl === '/website-speed-optimiation-cost.php') {
    $canonicalLink = HOST_URL.'website-speed-optimiation-cost-us.php';
} else {
    $canonicalLink = $actual_links[0]; 
}
?>

<link rel="canonical" href="<?=$canonicalLink?>" >

<!-- This Script is the exclusive property of Website Speedy, Copyright © 2023. All rights reserved. -->

    <!-- Meta Data-->
    <?php 

        if($actual_link == rtrim(HOST_URL,"/") || $actual_link == HOST_URL )
        {
            ?>
        <title>Website Speed Optimization Tool | Boost Site Speed - Website Speedy</title>    
                <meta name="description" content="Website Speedy - Best page speed optimization tool. Improve Google page speed score & fix core web vital issues. Achieve lightning-fast speed easily. Try now.">
                <meta name="keywords" content="Website speed optimization tool, fix core Web Vitals, improve google page speed score, improve mobile site speed, improve page load time, make website faster">
                <meta property="og:image" content="<?=$bunny_image?>websitespeedy-og-img.png">  
                <meta property="og:title" content="Website Speed Optimization Tool | Boost Site Speed - Website Speedy">
                <meta property="og:description" content="Website Speedy - Best page speed optimization tool. Improve Google page speed score & fix core web vital issues. Achieve lightning-fast speed easily. Try now.">

            <?php 
        }
        elseif($actual_link== HOST_URL.'website-speed-optimiation-cost.php' || $actual_link ==  HOST_URL.'website-speed-optimiation-cost.php/')
        {
            ?>
        <title>Optimize Your Website Speed and Performance | Plans & Pricing</title>    
            <meta name="description" content="Improve your site speed with WebsiteSpeedy. Choose the perfect website speed optimization plan with Website Speedy. Boost site speed and user experience now." >
            <meta name="keywords" content="Optimize website speed, Improve site speed, Website speed optimization, boost site speed" >
            <?php 
        }
        elseif($actual_link== HOST_URL.'website-speed-optimiation-cost-us.php' || $actual_link ==  HOST_URL.'website-speed-optimiation-cost-us.php/')
        {
            ?>
        <title>Website Speedy Pricing | Super, Booster, and Power Plans</title>    
            <meta name="description" content="Website Speedy plan starts at just $20/mo. Choose Your Plan or Start Your 7 Days FREE Trial. No Credit Card Required. 100% satisfaction guaranteed." >
            <meta name="keywords" content="Optimize website speed, Improve site speed, Website speed optimization, boost site speed" >
            <?php 
        }
        elseif($actual_link== HOST_URL.'why-website-speed-matters.php' || $actual_link ==  HOST_URL.'why-website-speed-matters.php/')
        {
            ?>
        <title>Discover Why Website Speed Matters Most - Website Speedy</title>    
            <meta name="description" content="Here you discover why website speed matters most. A slow-loading website causes high bounce rates, low conversions, and ranking in Google SERP." >
            <meta name="keywords" content="Boost website speed, Improve page load time, Website load time optimization" >
            <?php 
        }
        elseif($actual_link== HOST_URL.'blogs.php' || $actual_link ==  HOST_URL.'blogs.php/')
        {
            ?>
        <title>Website Speedy Blog | Insights for Websites Speed & Performance</title>    
            <meta name="description" content="Discover web speed performance insights with Website Speedy's blog. Find speed & performance info in our Blogs, case studies, and more." >
            <meta name="keywords" content="improve page load speed, improve website loading speed, Optimize website speed, fix core Web Vitals" >
            <?php 
        }
        elseif($actual_link== HOST_URL.'bigcommerce-speed-optimization.php' || $actual_link ==  HOST_URL.'bigcommerce-speed-optimization.php/')
            {
                ?>
            <title>Bigcommerce Speed Optimization Tool - Increase Bigcommerce Site Speed</title>    
            <meta name="description" content="Speed up Bigcommerce site with our Bigcommerce speed optimization tool. Boost website speed easily with Website Speedy. Easy to use & setup. Try now." >
            <meta name="keywords" content="Bigcommerce Speed Optimization, improve site speed, make website faster, reduce website loading time" >
            <meta property="og:image" content="<?=$bunny_image?>big-commerce-hero.webp">
                <?php 
            }
        elseif($actual_link== HOST_URL.'wix-editor-x-speed-optimization.php' || $actual_link ==  HOST_URL.'wix-editor-x-speed-optimization.php/')
            {
                ?>
            <title>Speed Up WIX Site Load Time | WIX Speed Optimization Tool</title>    
            <meta name="description" content="Speed up WIX/Editor X load time easily with the WIX speed optimization tool. Our tool Increases website speed and optimizes site performance. Easy to use." >
            <meta name="keywords" content="Increase website speed, Boost website speed, site performance optimization" >
                <?php 
            }
        elseif($actual_link== HOST_URL.'shopify-speed-optimization.php' || $actual_link ==  HOST_URL.'shopify-speed-optimization.php/')
            {
                ?>
            <title>Shopify Speed Optimization Tool | Improve Shopify Store Speed</title>    
            <meta name="description" content="Increase Shopify site speed, & boost your revenue with Website Speedy. A SaaS-based Shopify speed optimization tool. Improve site performance with our easy-to-use tool." >
            <meta name="keywords" content="Increase website speed, Boost website speed, site performance optimization" >
            <meta property="og:image" content="<?=$bunny_image?>shopify-banner-image-so-crop.webp">  
                <?php 
            }
        elseif($actual_link== HOST_URL.'squarespace-speed-optimization.php' || $actual_link ==  HOST_URL.'squarespace-speed-optimization.php/')
            {
                ?>
            <title>Squarespace Speed Optimization Tool - Speed Up Squarespace Site</title>    
            <meta name="description" content="Boost Squarespace site speed with our Squarespace speed optimization tool. Website Speedy can improve page speed, fix Core Web Vitals, & reduce render blocking." >
            <meta name="keywords" content="reduce website loading time, improve page speed, Core Web Vitals scores, render blocking" >
            <meta property="og:image" content="<?=$bunny_image?>squarespace-hero.webp">
                <?php 
            }

            elseif($actual_link== HOST_URL.'shift4shop-speed-optimization.php' || $actual_link ==  HOST_URL.'shift4shop-speed-optimization.php/')
                {
                    ?>
                <title>Shift4shop Speed Optimization Tool - Speed Up Shift4shop Site</title>    
                <meta name="description" content="With Shift4shop speed optimization tool, you can quickly optimize Shift4shop website loading time. Website Speedy tool helps you improve page speed easily." >
                <meta name="keywords" content="reduce website loading time, improve page speed, Core Web Vitals scores, render blocking" >
                <meta property="og:image" content="<?=$bunny_image?>shift4shop_Hero_image.webp">
                    <?php 
                }

            elseif($actual_link== HOST_URL.'webwave-speed-optimization.php' || $actual_link ==  HOST_URL.'webwave-speed-optimization.php/')
                {
                ?>
                <title>Webwave Speed Optimization Tool - Improve Webwave Site Speed</title>    
                <meta name="description" content="Boost Webwave website speed with our Webwave speed optimization tool, Website Speedy. It helps in reducing render-blocking and improving core web vital scores." >
                <meta name="keywords" content="reduce website loading time, improve page speed, Core Web Vitals scores, render blocking" >
                <?php 
                }    
            
            elseif($actual_link== HOST_URL.'signup.php' || $actual_link ==  HOST_URL.'signup.php/')
                {
                    ?>
                <title>Create Your Account - Sign Up</title>    
                <meta 
                    name="description" 
                    content="Create your account in seconds and sign up for Website Speedy. Enjoy fast and secure access to all our features, as well as exclusive discounts and offers." >
                <meta name="keywords" content="" >
                    <?php 
                }
            elseif($actual_link== HOST_URL.'adminpannel' || $actual_link ==  HOST_URL.'adminpannel/')
                {
                    ?>
                <title>Website Speedy - Account Login</title>    
                <meta 
                    name="description" 
                    content="Securely log into your Speedy account and access your account information, settings, and more. Get started now  and level up your website speed." >
                <meta name="keywords" content="" >
                    <?php 
            }
            elseif($actual_link== HOST_URL.'cancellation-refund-policy.php' || $actual_link ==  HOST_URL.'cancellation-refund-policy.php/')
                {
                    ?>
                <title>Our Cancellation And Refund Policy</title>    
                <meta 
                    name="description" 
                    content="This is the Cancellation and Refund Policy for Website Speedy. Learn how to easily cancel your subscription if it is not the right fit for you after signing up." >
                <meta name="keywords" content="" >
                    <?php 
            }
            elseif($actual_link== HOST_URL.'speed-guarantee.php' || $actual_link ==  HOST_URL.'speed-guarantee.php/')
                {
                    ?>
                <title>Increase Page Speed With Website Speedy Script</title>    
                <meta 
                    name="description" 
                    content="Speed up your website with Website Speedy & enjoy reliability with our cutting-edge technology. Get started now and boost your search engine rankings." >
                <meta name="keywords" content="improve google page speed score, Optimize website speed, improve site speed, improve page load speed, website speed booster, improve mobile page speed, Boost website speed, speed tools, magento speed optimization service, wordpress speed optimization expert, increase shopify speed, Magento Speed Optimization, wix speed optimization" >
                    <?php 
            }
            elseif($actual_link== HOST_URL.'cookie-policy.php' || $actual_link ==  HOST_URL.'cookie-policy.php/')
                {
                    ?>
                <title>Read Our Website Cookie Policy</title>    
                <meta 
                    name="description" 
                    content="This Cookie Policy explains how WebsiteSpeedy.com uses cookies to enhance your experience and gather information about the usage of their website." >
                <meta name="keywords" content="" >
                    <?php 
            }
            elseif($actual_link== HOST_URL.'faq.php' || $actual_link ==  HOST_URL.'faq.php/')
                {
                    ?>
                <title>Frequently Asked Questions</title>    
                <meta 
                    name="description" 
                    content="Discover answers to all your website speed optimization questions with Website Speedy. Get all of your website speed related questions answered today! " >
                <meta name="keywords" content="" >
                    <?php 
            }
            elseif($actual_link== HOST_URL.'privacy-policy.php' || $actual_link ==  HOST_URL.'privacy-policy.php/')
                {
                    ?>
                <title>Our Privacy Policy</title>    
                <meta 
                    name="description" 
                    content="This Privacy Policy outlines how Websitespeedy.com collects, uses, and shares personal data, and details the rights of our users regarding this data." >
                <meta name="keywords" content="" >
                    <?php 
            }
            elseif($actual_link== HOST_URL.'contact-us.php' || $actual_link ==  HOST_URL.'contact-us.php/')
                {
                    ?>
                <title>Contact Us For Assistance</title>    
                <meta 
                    name="description" 
                    content="We are here to help with any issues you may encounter, answer your questions, and provide suggestions for improving our tool. Contact us today." >
                <meta name="keywords" content="" >
                    <?php 
            }
            elseif($actual_link== HOST_URL.'terms-of-use.php' || $actual_link ==  HOST_URL.'terms-of-use.php/')
                {
                    ?>
                <title>Read Our Terms of Use</title>    
                <meta 
                    name="description" 
                    content="The following terms and conditions, privacy statement, and disclaimer notice apply to our website Speedy and all agreements between the company and the client." >
                <meta name="keywords" content="" >
                    <?php 
            }
            elseif($actual_link== HOST_URL.'adminpannel/forgetpassword.php' || $actual_link ==  HOST_URL.'adminpannel/forgetpassword.php/')
                {
                    ?>
                <title>Recover Your Password</title>    
                <meta 
                    name="description" 
                    content="Forgot your password? Speedy website has you covered! Our easy-to-use password recovery process will help you quickly and securely reset your password." >
                <meta name="keywords" content="" >
                    <?php 
            }
            elseif($actual_link==HOST_URL."blogs/google's-pagespeed-insights-tool-apply-recommendations.php" || $actual_link == HOST_URL."blogs/google's-pagespeed-insights-tool-apply-recommendations.php/")
                {
                    ?>
                <title>Google Pagespeed Insights Tool Recommendations</title>    
                <meta 
                    name="description" 
                    content="This blog post provides an in-depth look at the recommendations from Google Pagespeed Insights Tool & how you can use them to optimize your website." >
                <meta name="keywords" content="improve google page speed score, improve page load speed, Boost website speed, speed tools" />
                <meta name="robots" content="index, follow" >
                    <?php 
            }
            elseif($actual_link==HOST_URL."blogs/understanding-the-impact-of-website-speed-on-seo.php" || $actual_link == HOST_URL."blogs/understanding-the-impact-of-website-speed-on-seo.php/")
                {
                    ?>
                <title>Understand The Impact of SEO on Website</title>    
                <meta 
                    name="description" 
                    content="The blog explores the important impact of SEO on websites, including how it can increase traffic &  improve rankings. Optimize your website with our speedy tool. " >
                <meta name="keywords" content="improve mobile site speed, website speed booster, Boost website speed, improved speed, increase loading speed, improve website load time, page speed load time test" >
                <meta name="robots" content="index, follow" >
                    <?php 
            }
            elseif($actual_link==HOST_URL."blogs/how-does-slow-page-load-time-increase-bounce-rate.php" || $actual_link == HOST_URL."blogs/how-does-slow-page-load-time-increase-bounce-rate.php/")
                {
                    ?>
                <title>Effect On Bounce Rate Due To Slow Page Speed</title>    
                <meta 
                    name="description" 
                    content="A slow page speed can have a significant impact on your website's bounce rate. But with our website speedy tool you can increase your page speed by 3x. " >
                <meta name="keywords" content="improve google page speed score, improve page load time, improve page load speed, improve mobile page speed" >
                <meta name="robots" content="index, follow" >
                    <?php 
            }
            elseif($actual_link==HOST_URL."blogs/how-does-slow-page-speed-affect-web-user-experience.php" || $actual_link == HOST_URL."blogs/how-does-slow-page-speed-affect-web-user-experience.php/")
                {
                    ?>
                <title>Effects Of Slow Page Speed On User Experience</title>    
                <meta 
                    name="description" 
                    content="Read our blog post discussing how page speed can affect your website's bounce rate. Our Speedy Tool reduces the increased bounce rates and enhances page speed. " >
                <meta name="keywords" content="improve mobile page speed, page speed optimization service, improve page speed, page speed load time test" >
                <meta name="robots" content="index, follow" >
                    <?php 
            }
            elseif($actual_link==HOST_URL."blogs/increase-conversion-through-effective-ad-spend-by-reducing-the-bounce-rate.php" || $actual_link == HOST_URL."blogs/increase-conversion-through-effective-ad-spend-by-reducing-the-bounce-rate.php/")
                {
                    ?>
                <title>Increasing Conversions With Ad Spends</title>    
                <meta 
                    name="description" 
                    content="This blog post discusses how to increase conversions with ad spends. Get the most out of your campaigns and optimize your ad spend with our website speedy tool.  " >
                <meta name="keywords" content="improve google page speed score, Optimize website speed, improve site speed, improve page load speed, website speed booster, improve mobile page speed" >
                <meta name="robots" content="index, follow" >
                    <?php 
            }
            elseif($actual_link==HOST_URL."blogs/core-web-vitals-user-experience-seo-signals.php" || $actual_link == HOST_URL."blogs/core-web-vitals-user-experience-seo-signals.php/")
                {
                    ?>
                <title>Understanding Core Web Vitals</title>    
                <meta 
                    name="description" 
                    content="This blog explains the three Core Web Vitals – how they work, why they are important. Speedy tool fix core web vitals to improve website performance." >
                <meta name="keywords" content="fix core Web Vitals, improve google page speed score, core web vitals optimization service, Core Web Vitals scores, improve gtmetrix score" >
                <meta name="robots" content="index, follow" >
                    <?php 
            }
            elseif($actual_link==HOST_URL."blogs/tips-to-get-100-percent-improved-google-page-speed-score.php" || $actual_link == HOST_URL."blogs/tips-to-get-100-percent-improved-google-page-speed-score.php/")
                {
                    ?>
                <title>Maximize Google Page Speed: Proven Tips for 100% Improvement</title>    
                <meta 
                    name="description" 
                    content="Are you searching to make your website faster and extra efficient? Then you want to look at improving your google page speed score. Use our website speedy tool for enhancing your web page speed." >
                <meta name="keywords" content="improve google page speed score, improve mobile site speed, page speed optimization service, website optimization tool, improve gtmetrix score, improve site loading speed" >
                <meta name="robots" content="index, follow">
                    <?php 
            }
            elseif($actual_link==HOST_URL."blogs/web-speed-kpi-definition-and-the-page-load-time.php" || $actual_link == HOST_URL."blogs/web-speed-kpi-definition-and-the-page-load-time.php/")
                {
                    ?>
                <title>Web Speed KPI: Definition & The Page Load Time</title>    
                <meta 
                    name="description" 
                    content="When we explore the history of web speed, Load Time was one of the earliest KPIs used to measure web pages' loading time. Later, other indicators for optimisation of website load time – such as Speed Index and Time To Interactive – emerged to assess user perception of speed better. Load Time was pushed to the side." >
                <meta name="keywords" content="optimize your website, website speed optimization services, improve page load speed, page speed load time, improve mobile page speed, improve page load time" >
                <meta name="robots" content="index, follow" >
                    <?php 
            }
            elseif($actual_link==HOST_URL."blogs/how-improving-milliseconds-can-boost-performance-and-revenue.php" || $actual_link == HOST_URL."blogs/how-improving-milliseconds-can-boost-performance-and-revenue.php/")
                {
                    ?>
                <title>Milliseconds Matter: Supercharge Performance and Revenue</title>    
                <meta 
                    name="description" 
                    content="The success of any online business depends heavily on the performance of its website. As website speed and performance are closely related, it is crucial to ensure that you optimize your website for maximum speed and efficiency." >
                <meta name="keywords" content="optimisation of website load time, page speed load time test, boost website speed, website load time, website speed optimization tools, page load time test">
                <meta name="robots" content="index, follow">
                    <?php 
            }
            elseif($actual_link==HOST_URL."blogs/understanding-the-psychology-behind-customer-bounce-rates.php" || $actual_link == HOST_URL."blogs/understanding-the-psychology-behind-customer-bounce-rates.php/")
                {
                    ?>
                <title>Bounce Rates Unveiled: Decoding Customer Psychology</title>    
                <meta 
                    name="description" 
                    content="When it comes to understanding the customer experience, one of the most important metrics to consider is the customer bounce rate. A customer bounce rate is the percentage of visitors to your website who leave without viewing any other pages.">
                <meta name="keywords" content="improve page load speed, improve website loading speed, website diagnostic tool, boost website speed">
                <meta name="robots" content="index, follow">
                    <?php 
            }
            elseif($actual_link==HOST_URL."blogs/mobile-vs-desktop-pagespeed-scores-a-comparative-analysis.php" || $actual_link == HOST_URL."blogs/mobile-vs-desktop-pagespeed-scores-a-comparative-analysis.php/")
                {
                    ?>
                <title>Mobile vs Desktop PageSpeed: A Comparative Study</title>    
                <meta 
                    name="description" 
                    content="Read this comprehensive analysis that compares page speed scores of mobile and desktop devices and find out how to optimize for both mobile and desktop users.">
                <meta name="keywords" content="improve mobile page speed, Google PageSpeed Insights, Pingdom, GTmetrix, WebPageTest, improve google page speed score ">
                <meta name="robots" content="index, follow" >
                    <?php 
            }
            elseif($actual_link==HOST_URL."blogs/reasons-why-wix-editor-x-speed-optimization-is-important-for-seo.php" || $actual_link == HOST_URL."blogs/reasons-why-wix-editor-x-speed-optimization-is-important-for-seo.php/")
                {
                    ?>
                <title>Why Wix/Editor X Speed Optimization is Important for SEO</title>    
                <meta 
                    name="description" 
                    content="Find out the reasons why  Wix/Editor X speed optimization is critical for SEO, from improving user experience to better rankings on search engine results pages.">
                <meta name="keywords" content="Website load time optimization, Wix speed optimization, improve mobile site speed, website speed booster, Boost website speed, improved speed, increase loading speed, improve website load time, page speed load time test ">
                <meta name="robots" content="index, follow">
                    <?php 
            }
            elseif($actual_link==HOST_URL."agency-partner.php" || $actual_link == HOST_URL."agency-partner.php/")
                {
                    ?>
                <title>Become Agency Partner With Website Speedy | Earn 30% Commission</title>    
                <meta 
                    name="description" 
                    content="Join Website Speedy's agency partner program and earn a 30% commission. Expand your business and offer your clients high-quality website optimization tools." >
                <meta name="keywords" content=" " >
                    <?php 
            }
            elseif($actual_link==HOST_URL."fix-squarespace-core-web-vitals.php" || $actual_link == HOST_URL."fix-squarespace-core-web-vitals.php/")
                {
                    ?>
                <title>Fix Squarespace Core Web Vitals Easily - Website Speedy</title>    
                <meta 
                    name="description" 
                    content="Improve Your Squarespace Core Web Vitals Performance with the help of Website Speedy. This tool improves Google's core web vitals metrics, LCP, FCP, & CLS" >
                <meta name="keywords" content="Squarespace core web vitals, fix core Web Vitals, Core Web Vitals scores" >
                <meta property="og:image" content="<?=$bunny_image?>squarespace-Website-Performance.webp">  
                    <?php 
            }
            elseif($actual_link==HOST_URL."fix-bigcommerce-core-web-vitals.php" || $actual_link == HOST_URL."fix-bigcommerce-core-web-vitals.php/")
                {
                    ?>
                <title>Fix Bigcommerce Store Core Web Vitals Easily - Website Speedy</title>    
                <meta 
                    name="description" 
                    content="Improve Your Bigcommerce Store Core Web Vitals performance with the help of Website Speedy. This tool improves Google's core web vitals metrics, LCP, FCP, & CLS" >
                <meta name="keywords" content="Bigcommerce Core Web Vitals, fix core Web Vitals, Core Web Vitals scores" >
                <meta property="og:image" content="<?=$bunny_image?>big-commerce-conversion-rate.webp">
                    <?php 
            }
            elseif($actual_link==HOST_URL."fix-shopify-core-web-vitals.php" || $actual_link == HOST_URL."fix-shopify-core-web-vitals.php/")
                {
                    ?>
                <title>Easily Improve Shopify Store Core Web Vitals - Website Speedy</title>    
                <meta 
                    name="description" 
                    content="Improve Your Shopify site Core Web Vitals performance with the help of Website Speedy. This tool improves Google's core web vitals metrics, such as LCP, FCP, & CLS." >
                <meta name="keywords" content="Shopify Core Web Vitals, fix core Web Vitals, Core Web Vitals scores" >
                <meta property="og:image" content="<?=$bunny_image?>core-web-vital-one.webp">  
                    <?php 
            }
            elseif($actual_link==HOST_URL."fix-wix-editor-x-core-web-vitals.php" || $actual_link == HOST_URL."fix-wix-editor-x-core-web-vitals.php/")
                {
                    ?>
                <title>Fix Wix/Editor X Core Web Vitals Easily - Website Speedy</title>    
                <meta 
                    name="description" 
                    content="Improve Your Wix/Editor X site Core Web Vitals performance with the help of Website Speedy. This tool improves Google's core web vitals metrics, LCP, FCP, & CLS." >
                <meta name="keywords" content="Wix Editor X Core Web Vitals, fix core Web Vitals, Core Web Vitals scores" >
                <meta property="og:image" content="<?=$bunny_image?>wix-Website-Performance.webp">  
                    <?php 
            }
            elseif($actual_link==HOST_URL."fix-shift4shop-core-web-vitals.php" || $actual_link == HOST_URL."fix-shift4shop-core-web-vitals.php/")
                {
                    ?>
                <title>Fix Shift4shop Core Web Vitals Easily - Website Speedy</title>    
                <meta 
                    name="description" 
                    content="Improve Your Shift4shop Core Web Vitals Performance with the help of Website Speedy. This tool improves Google's core web vitals metrics, LCP, FCP, & CLS" >
                <meta name="keywords" content="shift4shop core web vitals, fix core Web Vitals, Core Web Vitals scores" >
                <meta property="og:image" content="<?=$bunny_image?>shift4shop-Website-performance.webp">  
                    <?php 
            }
            elseif($actual_link==HOST_URL."fix-webflow-core-web-vitals.php" || $actual_link == HOST_URL."fix-webflow-core-web-vitals.php/")
                {
                    ?>
                <title>Webflow Core Web Vitals Optimization | Fix CWV Issues</title>    
                <meta 
                    name="description" 
                    content="Improve Webflow site Core Web Vitals issues with Website Speedy - DIY CWV tool. Fix CLS, INP, & LCP issues. Elevate user experience, ROI & SEO Rankings. " >
                <meta name="keywords" content="" >
                <meta property="og:image" content="">  
                    <?php 
            }
            elseif($actual_link==HOST_URL."fix-hubspot-core-web-vitals.php" || $actual_link == HOST_URL."fix-hubspot-core-web-vitals.php/")
                {
                    ?>
                <title>Hubspot Core Web Vitals Optimization | Fix CWV Issues</title>    
                <meta 
                    name="description" 
                    content="Improve Hubspot site Core Web Vitals issues with Website Speedy - DIY CWV tool. Fix CLS, INP, & LCP issues. Elevate user experience, ROI & SEO Rankings." >
                <meta name="keywords" content="" >
                <meta property="og:image" content="">  
                    <?php 
            }
            elseif($actual_link==HOST_URL."fix-jimdo-core-web-vitals.php" || $actual_link == HOST_URL."fix-jimdo-core-web-vitals.php/")
                {
                    ?>
                <title>Jimdo Core Web Vitals Optimization | Fix CWV Issues</title>    
                <meta 
                    name="description" 
                    content="Improve Jimdo site Core Web Vitals issues with Website Speedy - DIY CWV tool. Fix CLS, INP, & LCP issues. Elevate user experience, ROI & SEO Rankings. " >
                <meta name="keywords" content="" >
                <meta property="og:image" content="">  
                    <?php 
            }
            elseif($actual_link==HOST_URL."fix-bigcartel-core-web-vitals.php" || $actual_link == HOST_URL."fix-bigcartel-core-web-vitals.php/")
                {
                    ?>
                <title>Big Cartel Core Web Vitals Optimization | Fix CWV Issues</title>    
                <meta 
                    name="description" 
                    content="Improve Big Cartel site Core Web Vitals issues with Website Speedy - DIY CWV tool. Fix CLS, INP, & LCP issues. Elevate user experience, ROI & SEO Rankings." >
                <meta name="keywords" content="" >
                <meta property="og:image" content="">  
                    <?php 
            }
            elseif($actual_link==HOST_URL."fix-webnode-core-web-vitals.php" || $actual_link == HOST_URL."fix-webnode-core-web-vitals.php/")
                {
                    ?>
                <title>Webnode Core Web Vitals Optimization | Fix CWV Issues</title>    
                <meta 
                    name="description" 
                    content="Improve Webnode site Core Web Vitals issues with Website Speedy - DIY CWV tool. Fix CLS, INP, & LCP issues. Elevate user experience, ROI & SEO Rankings. " >
                <meta name="keywords" content="" >
                <meta property="og:image" content="">  
                    <?php 
            }
            elseif($actual_link==HOST_URL."fix-tilda-core-web-vitals.php" || $actual_link == HOST_URL."fix-tilda-core-web-vitals.php/")
                {
                    ?>
                <title>Tilda Core Web Vitals Optimization | Fix CWV Issues</title>    
                <meta 
                    name="description" 
                    content="Improve Tilda site Core Web Vitals issues with Website Speedy - DIY CWV tool. Fix CLS, INP, & LCP issues. Elevate user experience, ROI & SEO Rankings." >
                <meta name="keywords" content="" >
                <meta property="og:image" content="">  
                    <?php 
            }
            elseif($actual_link==HOST_URL."fix-webwave-core-web-vitals.php" || $actual_link == HOST_URL."fix-webwave-core-web-vitals.php/")
                {
                    ?>
                <title>WebWave Core Web Vitals Optimization | Fix CWV Issues</title>    
                <meta 
                    name="description" 
                    content="Improve WebWave site Core Web Vitals issues with Website Speedy - DIY CWV tool. Fix CLS, INP, & LCP issues. Elevate user experience, ROI & SEO Rankings." >
                <meta name="keywords" content="" >
                <meta property="og:image" content="">  
                    <?php 
            }
            elseif($actual_link==HOST_URL."fix-clickfunnels-core-web-vitals.php" || $actual_link == HOST_URL."fix-clickfunnels-core-web-vitals.php/")
                {
                    ?>
                <title>ClickFunnels Core Web Vitals Optimization | Fix CWV Issues</title>    
                <meta 
                    name="description" 
                    content="Improve ClickFunnels site Core Web Vitals issues with Website Speedy - DIY CWV tool. Fix CLS, INP, & LCP issues. Elevate user experience, ROI & SEO Rankings." >
                <meta name="keywords" content="" >
                <meta property="og:image" content="">  
                    <?php 
            }
            elseif($actual_link==HOST_URL."fix-duda-core-web-vitals.php" || $actual_link == HOST_URL."fix-duda-core-web-vitals.php/")
                {
                    ?>
                <title>Duda Core Web Vitals Optimization | Fix CWV Issues</title>    
                <meta 
                    name="description" 
                    content="Improve Duda site Core Web Vitals issues with Website Speedy - DIY CWV tool. Fix CLS, INP, & LCP issues. Elevate user experience, ROI & SEO Rankings." >
                <meta name="keywords" content="" >
                <meta property="og:image" content="">  
                    <?php 
            }
            elseif($actual_link==HOST_URL."fix-ecwid-core-web-vitals.php" || $actual_link == HOST_URL."fix-ecwid-core-web-vitals.php/")
                {
                    ?>
                <title>Ecwid Core Web Vitals Optimization | Fix CWV Issues</title>    
                <meta 
                    name="description" 
                    content="Improve Ecwid site Core Web Vitals issues with Website Speedy - DIY CWV tool. Fix CLS, INP, & LCP issues. Elevate user experience, ROI & SEO Rankings." >
                <meta name="keywords" content="" >
                <meta property="og:image" content="">  
                    <?php 
            }
            elseif($actual_link==HOST_URL."fix-prestashop-core-web-vitals.php" || $actual_link == HOST_URL."fix-prestashop-core-web-vitals.php/")
                {
                    ?>
                <title>Prestashop Core Web Vitals Optimization | Fix CWV Issues</title>    
                <meta 
                    name="description" 
                    content="Improve Prestashop site Core Web Vitals issues with Website Speedy - DIY CWV tool. Fix CLS, INP, & LCP issues. Elevate user experience, ROI & SEO Rankings." >
                <meta name="keywords" content="" >
                <meta property="og:image" content="">  
                    <?php 
            }
            elseif($actual_link==HOST_URL."fix-weebly-core-web-vitals.php" || $actual_link == HOST_URL."fix-weebly-core-web-vitals.php/")
                {
                    ?>
                <title>Weebly Core Web Vitals Optimization | Fix CWV Issues</title>    
                <meta 
                    name="description" 
                    content="Improve Weebly site Core Web Vitals issues with Website Speedy - DIY CWV tool. Fix CLS, INP, & LCP issues. Elevate user experience, ROI & SEO Rankings." >
                <meta name="keywords" content="" >
                <meta property="og:image" content="">  
                    <?php 
            }
            elseif($actual_link==HOST_URL."blogs/the-impact-of-core-web-vitals-on-search-engine-optimization.php" || $actual_link == HOST_URL."blogs/the-impact-of-core-web-vitals-on-search-engine-optimization.php/")
                {
                    ?>
                <title>How Core Web Vitals Affect Search Engine Optimization</title>    
                <meta 
                    name="description" 
                    content="Learn how Core Web Vitals are affecting your website's search engine optimization and how to optimize your website user experience to improve your search engine rankings." >
                <meta name="keywords" content="fix core Web Vitals, Website speed optimization tools, Website Speedy" >
                <meta name="robots" content="index, follow" >
                    <?php 
            }
            elseif($actual_link==HOST_URL."blogs/the-ultimate-guide-to-bigcommerce-speed-optimization-to-increase-organi-traffic.php" || $actual_link == HOST_URL."blogs/the-ultimate-guide-to-bigcommerce-speed-optimization-to-increase-organi-traffic.php/")
                {
                    ?>
                <title>Supercharge Bigcommerce: Ultimate Speed Optimization Guide</title>    
                <meta 
                    name="description" 
                    content="Looking to improve your Bigcommerce store's speed? Read some expert tips for optimizing your site's performance and delivering a seamless shopping experience." >
                <meta name="keywords" content=" Bigcommerce site speed optimization, boost website speed, Increase website speed, Bigcommerce site speed, optimize your website,  improve google page speed score,  optimize your website, Core Web Vitals scores, core web vitals optimization service" >
                <meta name="robots" content="index, follow" >
                    <?php 
            }
            elseif($actual_link==HOST_URL."blogs/the-ultimate-guide-to-shopify-speed-optimization-to-increase-organic-traaffic.php" || $actual_link == HOST_URL."blogs/the-ultimate-guide-to-shopify-speed-optimization-to-increase-organic-traaffic.php/")
                {
                    ?>
                <title>Tips to Optimize Page Speed for Shopify Stores</title>    
                <meta 
                    name="description" 
                    content="Looking to improve your Shopify store's speed? Check out these expert tips for optimizing your website's performance and providing a seamless shopping experience." >
                <meta name="keywords" content=" improve shopif y store speed, how to increase website speed, shopify speed optimization, Shopify Core Web Vitals, Website speedy, shopify store speed score, Shopify speed ‍optimization service " >
                <meta name="robots" content="index, follow" >
                    <?php 
            }
            elseif($actual_link==HOST_URL."case-study/giant-teddy-case-study.php" || $actual_link == HOST_URL."case-study/giant-teddy-case-study.php/")
                {
                    ?>
                <title>Giant Teddy Case Study | Website Speedy</title>    
                <meta 
                    name="description" 
                    content="Know how Website Speedy helped a leading online personalized gifts platform to improve their website speed. Click and read the case study" >
                <meta name="keywords" content=" " >
                <meta name="robots" content="index, follow" >
                    <?php 
            }
            elseif($actual_link==HOST_URL."case-study/rakuten-24-case-study.php" || $actual_link == HOST_URL."case-study/rakuten-24-case-study.php/")
                {
                    ?>
                <title>Rakuten Case Study | Website Speedy</title>    
                <meta 
                    name="description" 
                    content="Discover how Rakuten optimized their website for lightning-fast speed. Learn from their case study and unlock the secrets to a speedy website." >
                <meta name="keywords" content=" " >
                <meta name="robots" content="index, follow" >
                    <?php 
            }
            elseif($actual_link==HOST_URL."case-study/farfetch-case-study.php" || $actual_link == HOST_URL."case-study/farfetch-case-study.php/")
                {
                    ?>
                <title>Farfetch Case Study | Website Speedy</title>    
                <meta 
                    name="description" 
                    content="Discover how Farfetch improved website speed and enhanced user experience. Explore the Farfetch case study and learn valuable insights." >
                <meta name="keywords" content=" " >
                <meta name="robots" content="index, follow" >
                    <?php 
            }

            elseif($actual_link==HOST_URL."case-study/harbor-farm-case-study.php" || $actual_link == HOST_URL."case-study/harbor-farm-case-study.php/")
                {
                    ?>
                <title>Harbor Farm Case Study | Website Speedy</title>    
                <meta 
                    name="description" 
                    content="Harbor Farm, a charming family-owned business nestled in Downeast Maine, has been perfecting the art of handcrafting beautiful and fragrant Balsam Christmas wreaths" >
                <meta name="keywords" content=" " >
                <meta name="robots" content="index, follow" >
                    <?php 
            }

            elseif($actual_link==HOST_URL."case-study/powermy-case-study.php" || $actual_link == HOST_URL."case-study/powermy-case-study.php/")
                {
                    ?>
                <title>PowerMy.com Case Study | Website Speedy</title>    
                <meta 
                    name="description" 
                    content="At the start of the website speed optimization process for Powermy, we conducted a thorough assessment of their homepage, collection page, product page" >
                <meta name="keywords" content=" " >
                <meta name="robots" content="index, follow" >
                    <?php 
            }

            elseif($actual_link==HOST_URL."case-study/face-camera-case-study.php" || $actual_link == HOST_URL."case-study/face-camera-case-study.php/")
                {
                    ?>
                <title>Face Camera Case Study | Website Speedy</title>    
                <meta 
                    name="description" 
                    content="At the start of the website speed optimization process for Face Camera, we conducted a thorough assessment of their homepage, collection page, product page" >
                <meta name="keywords" content=" " >
                <meta name="robots" content="index, follow" >
                    <?php 
            }
            elseif($actual_link==HOST_URL."case-study/rise-case-study.php" || $actual_link == HOST_URL."case-study/rise-case-study.php/")
                {
                    ?>
                <title>Rise Case Study | Website Speedy</title>    
                <meta 
                    name="description" 
                    content="At the start of the website speed optimization process for Rise Marketing, we conducted a thorough assessment of their homepage, & services pages" >
                <meta name="keywords" content=" " >
                <meta name="robots" content="index, follow" >
                    <?php 
            }
            elseif($actual_link==HOST_URL."case-study/unique-phuket-case-study.php" || $actual_link == HOST_URL."case-study/unique-phuket-case-study.php/")
                {
                    ?>
                <title>Unique Phuket Case Study | Website Speedy</title>    
                <meta 
                    name="description" 
                    content="At the start of the website speed optimization process for Unique Phuket, we conducted a thorough assessment of their homepage, & services pages" >
                <meta name="keywords" content=" " >
                <meta name="robots" content="index, follow" >
                    <?php 
            }
            elseif($actual_link==HOST_URL."case-study/heyo-case-study.php" || $actual_link == HOST_URL."case-study/heyo-case-study.php/")
                {
                    ?>
                <title>Heyo Case Study | Website Speedy</title>    
                <meta 
                    name="description" 
                    content="At the start of the website speed optimization process for Heyo, we conducted a thorough assessment of their homepage, collection & product pages" >
                <meta name="keywords" content=" " >
                <meta name="robots" content="index, follow" >
                    <?php 
            }
            elseif($actual_link==HOST_URL."case-study/pastels-and-pop-case-study.php" || $actual_link == HOST_URL."case-study/pastels-and-pop-case-study.php/")
                {
                    ?>
                <title>Pastels & Pop Case Study | Website Speedy</title>    
                <meta 
                    name="description" 
                    content="At the start of the website speed optimization process for Pastels & Pop, we conducted a thorough assessment of their homepage, collection & product pages" >
                <meta name="robots" content="index, follow">
                    <?php 
            }
            elseif($actual_link==HOST_URL."case-study/lifelong-adventure-case-study.php" || $actual_link == HOST_URL."case-study/lifelong-adventure-case-study.php/")
                {
                    ?>
                <title>Lifelong Adventure Case Study | Website Speedy</title>    
                <meta 
                    name="description" 
                    content="At the start of the website speed optimization process for Lifelong Adventure, we conducted a thorough assessment of their homepage, & blog page" >
                <meta name="robots" content="index, follow">
                    <?php 
            }
            elseif($actual_link==HOST_URL."case-study/cp-lab-safety-case-study.php" || $actual_link == HOST_URL."case-study/cp-lab-safety-case-study.php/")
                {
                    ?>
                <title>CP Lab Safety Case Study | Website Speedy</title>    
                <meta 
                    name="description" 
                    content="At the start of the website speed optimization process for CP Lab Safety, we conducted a thorough assessment of their homepage, collection & product pages" >
                <meta name="robots" content="index, follow">
                    <?php 
            }
            elseif($actual_link==HOST_URL."case-study/diversity-beans-case-study.php" || $actual_link == HOST_URL."case-study/diversity-beans-case-study.php/")
                {
                    ?>
                <title>Diversity Beans Case Study | Website Speedy</title>    
                <meta 
                    name="description" 
                    content="At the start of the website speed optimization process for Diversity Beans, we conducted a thorough assessment of their homepage, collection & product pages" >
                <meta name="robots" content="index, follow">
                    <?php 
            }
            elseif($actual_link==HOST_URL."case-study/strobels-supply-case-study.php" || $actual_link == HOST_URL."case-study/strobels-supply-case-study.php/")
                {
                    ?>
                <title>Strobels Supply Case Study | Website Speedy</title>    
                <meta 
                    name="description" 
                    content="At the start of the website speed optimization process for Strobels Supply, we conducted a thorough assessment of their homepage, collection & product pages" >
                <meta name="robots" content="index, follow">
                    <?php 
            }
            elseif($actual_link==HOST_URL."case-study/shopex-case-study.php" || $actual_link == HOST_URL."case-study/shopex-case-study.php/")
                {
                    ?>
                <title>Shopex Case Study | Website Speedy</title>    
                <meta 
                    name="description" 
                    content="At the start of the website speed optimization process for Shopex, we conducted a thorough assessment of their homepage, collection & product pages" >
                <meta name="robots" content="index, follow">
                    <?php 
            }
            elseif($actual_link==HOST_URL."case-study/august-collection-case-study.php" || $actual_link == HOST_URL."case-study/august-collection-case-study.php/")
                {
                    ?>
                <title>August Collection Case Study | Website Speedy</title>    
                <meta 
                    name="description" 
                    content="At the start of the website speed optimization process for August Collection, we conducted a thorough assessment of their homepage, & other pages" >
                <meta name="robots" content="index, follow">
                    <?php 
            }
            elseif($actual_link==HOST_URL."case-study/balumabliss-case-study.php" || $actual_link == HOST_URL."case-study/balumabliss-case-study.php/")
                {
                    ?>
                <title>BalumaBliss Case Study | Website Speedy</title>    
                <meta 
                    name="description" 
                    content="At the start of the website speed optimization process for BalumaBliss, we conducted a thorough assessment of their homepage, collections & product pages" >
                <meta name="robots" content="index, follow">
                <meta property="og:image" content="<?=$bunny_image?>BalumaBliss_Case_Study.webp"> 
                    <?php 
            }
            elseif($actual_link==HOST_URL."case-study/cb-radio-case-study.php" || $actual_link == HOST_URL."case-study/cb-radio-case-study.php/")
                {
                    ?>
                <title>CB Radio Case Study | Website Speedy</title>    
                <meta 
                    name="description" 
                    content="At the start of the website speed optimization process for CB Radio, we conducted a thorough assessment of their homepage, collection & product pages" >
                <meta name="robots" content="index, follow">
                <meta property="og:image" content="<?=$bunny_image?>CB_Radio_Case_Study.webp"> 
                    <?php 
            }
            elseif($actual_link==HOST_URL."case-study/talebot-case-study.php" || $actual_link == HOST_URL."case-study/talebot-case-study.php/")
                {
                    ?>
                <title>TaleBot Case Study | Website Speedy</title>    
                <meta 
                    name="description" 
                    content="At the start of the website speed optimization process for TaleBot, we conducted a thorough assessment of their homepage, & other pages" >
                <meta name="robots" content="index, follow">
                <meta property="og:image" content="<?=$bunny_image?>TaleBot_Case_Study.webp"> 
                    <?php 
            }
            elseif($actual_link==HOST_URL."case-study/executiveerp-case-study.php" || $actual_link == HOST_URL."case-study/executiveerp-case-study.php/")
                {
                    ?>
                <title>ExecutiveERP Case Study | Website Speedy</title>    
                <meta 
                    name="description" 
                    content="At the start of the website speed optimization process for ExecutiveERP, we conducted a thorough assessment of their homepage, & other pages" >
                <meta name="robots" content="index, follow">
                <meta property="og:image" content="<?=$bunny_image?>Executive_ERP_Case_Study.webp"> 
                    <?php 
            }
            elseif($actual_link==HOST_URL."case-study/greendiscounter-case-study.php" || $actual_link == HOST_URL."case-study/greendiscounter-case-study.php/")
                {
                    ?>
                <title>Greendiscounter Case Study | Website Speedy</title>    
                <meta 
                    name="description" 
                    content="At the start of the website speed optimization process for Greendiscounter, we conducted a thorough assessment of their homepage, collections & product pages" >
                <meta name="robots" content="index, follow">
                <meta property="og:image" content="<?=$bunny_image?>Greendiscounter_Case_Study.webp"> 
                    <?php 
            }
            elseif($actual_link==HOST_URL."case-study/jaguar-fitness-case-study.php" || $actual_link == HOST_URL."case-study/jaguar-fitness-case-study.php/")
                {
                    ?>
                <title>Jaguar Fitness Case Study | Website Speedy</title>    
                <meta 
                    name="description" 
                    content="At the start of the website speed optimization process for Jaguar Fitness, we conducted a thorough assessment of their homepage, collection & product pages" >
                <meta name="robots" content="index, follow">
                <meta property="og:image" content="<?=$bunny_image?>jaguarfitness_Case_Study.webp"> 
                    <?php 
            }
            elseif($actual_link==HOST_URL."case-study/alkamind-case-study.php" || $actual_link == HOST_URL."case-study/alkamind-case-study.php/")
                {
                    ?>
                <title>Alkamind Case Study | Website Speedy</title>    
                <meta 
                    name="description" 
                    content="At the start of the website speed optimization process for Alkamind, we conducted a thorough assessment of their homepage, collection & product pages" >
                <meta name="robots" content="index, follow">
                <meta property="og:image" content="<?=$bunny_image?>Alkamind_Case_Study.webp"> 
                    <?php 
            }
            elseif($actual_link==HOST_URL."case-study/hyperion-tiles-case-study.php" || $actual_link == HOST_URL."case-study/hyperion-tiles-case-study.php/")
                {
                    ?>
                <title>Hyperion Tiles Case Study | Website Speedy</title>    
                <meta 
                    name="description" 
                    content="At the start of the website speed optimization process for Hyperion Tiles, we conducted a thorough assessment of their homepage, collection and product pages" >
                <meta name="robots" content="index, follow">
                <meta property="og:image" content="<?=$bunny_image?>hyperiontiles_Case_Study.webp"> 
                    <?php 
            }

            elseif($actual_link==HOST_URL."case-study/san-carlos-imports-case-study.php" || $actual_link == HOST_URL."case-study/san-carlos-imports-case-study.php/")
                {
                    ?>
                <title>San Carlos Imports Case Study | Website Speedy</title>    
                <meta 
                    name="description" 
                    content="At the start of the website speed optimization process for San Carlos Imports, we conducted a thorough assessment of their homepage, collection & Product pages" >
                <meta name="robots" content="index, follow">
                <meta property="og:image" content="<?=$bunny_image?>san-carlos-feature-image.webp"> 
                    <?php 
            }
            elseif($actual_link==HOST_URL."case-study/flowbiz-collective-case-study.php" || $actual_link == HOST_URL."case-study/flowbiz-collective-case-study.php/")
                {
                    ?>
                <title>FlowBiz Collective Case Study | Website Speedy</title>    
                <meta 
                    name="description" 
                    content="At the start of the website speed optimization process for FlowBiz Collective, we conducted a thorough assessment of their homepage, & service pages" >
                <meta name="robots" content="index, follow">
                <meta property="og:image" content="<?=$bunny_image?>FlowBiz_Collective_Case_Study.webp"> 
                    <?php 
            }
            elseif($actual_link==HOST_URL."case-study/waveoasis-collective-case-study.php" || $actual_link == HOST_URL."case-study/waveoasis-collective-case-study.php/")
                {
                    ?>
                <title>Wave Oasis Speed Optimization Case Study | Website Speedy</title>    
                <meta 
                    name="description" 
                    content="Know how Website Speedy helped Wave Oasis which provides menstrual cycle underwear in website Speed Optimization. Click & read the case study" >
                <meta name="robots" content="index, follow">
                <meta property="og:image" content="<?=$bunny_image?>wave_oasis.avif"> 
                    <?php 
            }
            elseif($actual_link==HOST_URL."case-study/restorapic-case-study.php" || $actual_link == HOST_URL."case-study/restorapic-case-study.php/")
                {
                    ?>
                <title>Restorapic Case Study | Website Speedy</title>    
                <meta 
                    name="description" 
                    content="At the start of the website speed optimization process for Restorapic, we conducted a thorough assessment of their homepage, & service pages" >
                <meta name="robots" content="index, follow">
                <meta property="og:image" content="<?=$bunny_image?>wave_oasis.avif"> 
                    <?php 
            }

            elseif($actual_link==HOST_URL."case-study/sustainable-tomorrow-case-study.php" || $actual_link == HOST_URL."case-study/sustainable-tomorrow-case-study.php/")
                {
                    ?>
                <title>Sustainable Tomorrow Case Study | Website Speedy</title>    
                <meta 
                    name="description" 
                    content="At the start of the website speed optimization process for Sustainable Tomorrow, we conducted a thorough assessment of their homepage, collection & product pages" >
                <meta name="robots" content="index, follow">
                <meta property="og:image" content="<?=$bunny_image?>Substantial-Tomorrow-Case-Study.avif"> 
                    <?php 
            }

            elseif($actual_link==HOST_URL."case-study/perfect-client-case-study.php" || $actual_link == HOST_URL."case-study/perfect-client-case-study.php/")
                {
                    ?>
                <title>Perfect Client Case Study | Website Speedy</title>    
                <meta 
                    name="description" 
                    content="At the start of the website speed optimization process for Perfect Client, we conducted a thorough assessment of their homepage, & service pages." >
                <meta name="robots" content="index, follow">
                <meta property="og:image" content="<?=$bunny_image?>Substantial-Tomorrow-Case-Study.avif"> 
                    <?php 
            }

            elseif($actual_link==HOST_URL."case-study/supergeneralstore-case-study.php" || $actual_link == HOST_URL."case-study/supergeneralstore-case-study.php/")
                {
                    ?>
                <title>SuperGeneralStore Case Study | Website Speedy</title>    
                <meta 
                    name="description" 
                    content="At the start of the website speed optimization process for SuperGeneralStore, we conducted a thorough assessment of their homepage, collection & product pages." >
                <meta name="robots" content="index, follow">
                <meta property="og:image" content="<?=$bunny_image?>Substantial-Tomorrow-Case-Study.avif"> 
                    <?php 
            }

            elseif($actual_link==HOST_URL."case-study/plumbing4home-case-study.php" || $actual_link == HOST_URL."case-study/plumbing4home-case-study.php/")
                {
                    ?>
                <title>plumbing4home Case Study | Website Speedy</title>    
                <meta 
                    name="description" 
                    content="At the start of the website speed optimization process for plumbing4home, we conducted a thorough assessment of their homepage, & collection & product pages." >
                <meta name="robots" content="index, follow">
                <meta property="og:image" content="<?=$bunny_image?>Substantial-Tomorrow-Case-Study.avif"> 
                    <?php 
            }

            elseif($actual_link==HOST_URL."case-study/hocho-knife-case-study.php" || $actual_link == HOST_URL."case-study/hocho-knife-case-study.php/")
                {
                    ?>
                <title>Hocho Knife Case Study | Website Speedy</title>    
                <meta 
                    name="description" 
                    content="At the start of the website speed optimization process for Hocho-Knife, we conducted a thorough assessment of their homepage, collection, and product pages." >
                <meta name="robots" content="index, follow">
                <meta property="og:image" content="<?=$bunny_image?>Substantial-Tomorrow-Case-Study.avif"> 
                    <?php 
            }

            elseif($actual_link==HOST_URL."case-study/discounted-cables-case-study.php" || $actual_link == HOST_URL."case-study/discounted-cables-case-study.php/")
                {
                    ?>
                <title>Discounted Cables Case Study | Website Speedy</title>    
                <meta 
                    name="description" 
                    content="At the start of the website speed optimization process for Discounted Cables, we conducted a thorough assessment of their homepage, collection & product pages." >
                <meta name="robots" content="index, follow">
                <meta property="og:image" content="<?=$bunny_image?>Substantial-Tomorrow-Case-Study.avif"> 
                    <?php 
            }

            elseif($actual_link==HOST_URL."case-study/boxheart-case-study.php" || $actual_link == HOST_URL."case-study/boxheart-case-study.php/")
                {
                    ?>
                <title>BoxHeart Case Study | Website Speedy</title>    
                <meta 
                    name="description" 
                    content="At the start of the website speed optimization process for BoxHeart, we conducted a thorough assessment of their homepage, collection and product pages." >
                <meta name="robots" content="index, follow">
                <meta property="og:image" content="<?=$bunny_image?>Substantial-Tomorrow-Case-Study.avif"> 
                    <?php 
            }

            elseif($actual_link==HOST_URL."case-study/ashjoy-case-study.php" || $actual_link == HOST_URL."case-study/ashjoy-case-study.php/")
                {
                    ?>
                <title>AshJoy Case Study | Website Speedy</title>    
                <meta 
                    name="description" 
                    content="At the start of the website speed optimization process for AshJoy, we conducted a thorough assessment of their homepage, collection & product pages." >
                <meta name="robots" content="index, follow">
                <meta property="og:image" content="<?=$bunny_image?>Substantial-Tomorrow-Case-Study.avif"> 
                    <?php 
            }

            elseif($actual_link==HOST_URL."case-study/shopsk-case-study.php" || $actual_link == HOST_URL."case-study/shopsk-case-study.php/")
                {
                    ?>
                <title>ShopSK Case Study | Website Speedy</title>    
                <meta 
                    name="description" 
                    content="At the start of the website speed optimization process for ShopSK, we conducted a thorough assessment of their homepage, collection & product pages." >
                <meta name="robots" content="index, follow">
                <meta property="og:image" content="<?=$bunny_image?>Substantial-Tomorrow-Case-Study.avif"> 
                    <?php 
            }

            elseif($actual_link==HOST_URL."case-study/eve-street-electrical-case-study.php" || $actual_link == HOST_URL."case-study/eve-street-electrical-case-study.php/")
                {
                    ?>
                <title>Eve Street Electrical Case Study | Website Speedy</title>    
                <meta 
                    name="description" 
                    content="At the start of the website speed optimization process for Eve Street Electrical, we conducted a thorough assessment of their homepage, & services pages." >
                <meta name="robots" content="index, follow">
                <meta property="og:image" content="<?=$bunny_image?>Substantial-Tomorrow-Case-Study.avif"> 
                    <?php 
            }
            elseif($actual_link==HOST_URL."case-study/beauty-and-relax-case-study.php" || $actual_link == HOST_URL."case-study/beauty-and-relax-case-study.php/")
                {
                    ?>
                <title>Beauty & Relax Case Study | Website Speedy</title>    
                <meta 
                    name="description" 
                    content="At the start of the website speed optimization process for Beauty & Relax, we conducted a thorough assessment of their homepage, collection & product pages." >
                <meta name="robots" content="index, follow">
                <meta property="og:image" content="<?=$bunny_image?>Substantial-Tomorrow-Case-Study.avif"> 
                    <?php 
            }
            elseif($actual_link==HOST_URL."case-study/herb-saver-case-study.php" || $actual_link == HOST_URL."case-study/herb-saver-case-study.php/")
                {
                    ?>
                <title>Herb Saver Case Study | Website Speedy</title>    
                <meta 
                    name="description" 
                    content="At the start of the website speed optimization process for Herb Saver, we conducted a thorough assessment of their homepage, collection & product pages." >
                <meta name="robots" content="index, follow">
                <meta property="og:image" content="<?=$bunny_image?>Substantial-Tomorrow-Case-Study.avif"> 
                    <?php 
            }

            elseif($actual_link==HOST_URL."case-study/nurture-your-pet-case-study.php" || $actual_link == HOST_URL."case-study/nurture-your-pet-case-study.php/")
                {
                    ?>
                <title>Nurture Your Pet Case Study | Website Speedy</title>    
                <meta 
                    name="description" 
                    content="At the start of the website speed optimization process for Heyo, we conducted a thorough assessment of their homepage, & other  pages." >
                <meta name="robots" content="index, follow">
                <meta property="og:image" content="<?=$bunny_image?>Substantial-Tomorrow-Case-Study.avif"> 
                    <?php 
            }
            
            elseif($actual_link==HOST_URL.'clickfunnels-speed-optimization.php' || $actual_link == HOST_URL.'clickfunnels-speed-optimization.php/')
            {
                ?>
                <title>ClickFunnels Speed Optimization Tool - Speed Up ClickFunnel Load Time</title>    
                <meta 
                    name="description" 
                    content="Boost ClickFunnels page speed easily with the ClickFunnels speed optimization tool. Reduce website loading time easily with Website Speedy. Try it now.">
                <meta name="keywords" content="reduce website loading time, improve page speed, Core Web Vitals scores, render blocking">
                <?php 
            }
            elseif($actual_link==HOST_URL.'webflow-speed-optimization.php' || $actual_link == HOST_URL.'webflow-speed-optimization.php/')
            {
                ?>
                    <title>Speed Up Webflow Site - Webflow Speed Optimization Tool</title>    
                    <meta 
                        name="description" 
                        content="Optimize your Webflow site speed easily with Website Speedy. It is the best Webflow Speed optimization tool that helps in achieving faster loading sites.">
                    <meta name="keywords" content="reduce website loading time, improve page speed, Core Web Vitals scores, render blocking">
                <?php 
            }
            elseif($actual_link==HOST_URL.'weebly-speed-optimization.php' || $actual_link == HOST_URL.'weebly-speed-optimization.php/')
                {
                    ?>
                        <title>Weebly Speed Optimization Tool - Speed Up Weebly Site</title>    
                        <meta 
                            name="description" 
                            content="Improve Weebly website with the help of Website Speedy. It is the best Weebly speed optimization tool available online. Boost your website quickly and easily.">
                        <meta name="keywords" content="reduce website loading time, improve page speed, Core Web Vitals scores, render blocking">
                    <?php 
                }
            elseif($actual_link==HOST_URL.'hubspot-speed-optimization.php' || $actual_link == HOST_URL.'hubspot-speed-optimization.php/')
            {
                ?>
                <title>HubSpot Speed Optimization Tool - Speed Up HubSpot Website</title>    
                <meta 
                    name="description" 
                    content="Improve HubSpot page load speed with our HubSpot speed optimization tool. Speed up your website effortlessly with Website Speedy. Try it now." >
                <meta name="keywords" content="reduce website loading time, improve page speed, Core Web Vitals scores, render blocking" >
                <?php 
            }
            elseif($actual_link==HOST_URL.'blogs/the-ultimate-guide-to-wix-website-speed-optimization.php' || $actual_link == HOST_URL.'blogs/the-ultimate-guide-to-wix-website-speed-optimization.php/')
                {
                    ?>
                    <title>The Ultimate Wix Website Speed Optimization Guide </title>    
                    <meta 
                        name="description" 
                        content="Optimizing your Wix website speed is crucial to your online business. A faster website improves user experience, boosts your rankings, & increases conversions." >
                    <meta name="keywords" content="" >
                    <meta name="robots" content="index, follow" >
                    <?php 
                }
                elseif($actual_link==HOST_URL.'jimdo-speed-optimization.php' || $actual_link == HOST_URL.'jimdo-speed-optimization.php/')
                    {
                        ?>
                        <title>Jimdo Speed Optimization Tool - Improve Jimdo Site Speed</title>    
                        <meta 
                            name="description" 
                            content="Boost your Jimdo website speed easily with our Jimdo speed optimization tool. Website Speedy optimizes website speed effortlessly. Try it today." >
                        <meta name="keywords" content="reduce website loading time, improve page speed, Core Web Vitals scores, render blocking" >
                        <?php 
                }
                elseif($actual_link==HOST_URL.'bigcartel-speed-optimization.php' || $actual_link == HOST_URL.'bigcartel-speed-optimization.php/')
                    {
                        ?>
                        <title>Big Cartel Speed Optimization Tool - Speed Up Big Cartel Site</title>    
                        <meta 
                            name="description" 
                            content="Boost your Big Cartel store speed with our Big Cartel speed optimization tool, Website Speedy. Reduce website loading time effortlessly. Try it today." >
                        <meta name="keywords" content="reduce website loading time, improve page speed, Core Web Vitals scores, render blocking" >
                        <?php 
                    }
                    elseif($actual_link==HOST_URL.'webnode-speed-optimization.php' || $actual_link == HOST_URL.'webnode-speed-optimization.php/')
                        {
                            ?>
                            <title>Webnode Speed Optimization Tool - Speed Up Your Webnode Site</title>    
                            <meta 
                                name="description" 
                                content="Boost your Webnode site speed instantly with our Webnode speed optimization tool. Website Speedy tool can reduce render-blocking & fix Core Web Vitals scores easily" >
                            <meta name="keywords" content="reduce website loading time, improve page speed, Core Web Vitals scores, render blocking" >
                            <?php 
            }
            elseif($actual_link==HOST_URL.'case-study/jurassic-studio-case-study.php' || $actual_link == HOST_URL.'case-study/jurassic-studio-case-study.php/')
                {
                    ?>
                    <title>Jurassic Studio Case Study | Website Speedy</title>    
                    <meta 
                        name="description" 
                        content="Know how Website Speedy helped a leading online dinosaur themed merchandise platform to improve their website speed. Click and read the case study" >
                    <meta name="keywords" content="" >
                    <meta name="robots" content="index, follow" >
                    <?php 
            }
            elseif($actual_link==HOST_URL.'blogs/ultimate-guide-to-improving-google-page-speed-score.php' || $actual_link == HOST_URL.'blogs/ultimate-guide-to-improving-google-page-speed-score.php/')
                {
                    ?>
                    <title>The Ultimate Guide to Improving Your Google Page Speed Scor</title>    
                    <meta 
                        name="description" 
                        content="If you want to keep your visitors engaged and happy. One way to achieve this is by improving your Google Page Speed Score. Here are some tips to help you." >
                    <meta name="keywords" content="improve Google Page Speed score, how to improve site loading speed, slow page speed, " >
                    <meta name="robots" content="index, follow" >
                    <?php 
            }
            elseif($actual_link==HOST_URL.'tilda-speed-optimization.php' || $actual_link == HOST_URL.'tilda-speed-optimization.php/')
                {
                    ?>
                    <title>Tilda Speed Optimization Tool - Boost Your Tilda Website Speed</title>    
                    <meta 
                        name="description" 
                        content="With our Tilda speed optimization tool, you can quickly reduce website loading time. With Website Speedy tool, you can improve page speed, and reduce render-blocking" >
                    <meta name="keywords" content="reduce website loading time, improve page speed, Core Web Vitals scores, render blocking" >
                    <?php 
        }
            elseif($actual_link==HOST_URL.'duda-speed-optimization.php' || $actual_link == HOST_URL.'duda-speed-optimization.php/')
                {
                    ?>
                    <title>Duda Website Speed Optimization Tool - Speed Up Your Website</title>    
                    <meta 
                        name="description" 
                        content="Boost your Duda website speed easily with Website Speedy. It is best Speed Optimization tool for your website. Improve load time and reduce render blocking." >
                    <meta name="keywords" content="reduce website loading time, improve page speed, Core Web Vitals scores, render blocking" >
                    <?php 
        }
            elseif($actual_link==HOST_URL.'ecwid-speed-optimization.php' || $actual_link == HOST_URL.'ecwid-speed-optimization.php/')
                {
                    ?>
                    <title>Ecwid Website Speed Optimization Tool - Boost Your Website Speed</title>    
                    <meta 
                        name="description" 
                        content="Boost your Ecwid site speed easily with Website Speedy. It is best Speed Optimization tool for your website. Improve page load time & reduce render blocking." >
                    <meta name="keywords" content="reduce website loading time, improve page speed, Core Web Vitals scores, render blocking" >
                    <?php 
        }
            elseif($actual_link==HOST_URL.'prestashop-speed-optimization.php' || $actual_link == HOST_URL.'prestashop-speed-optimization.php/')
                {
                    ?>
                    <title>DIY Prestashop Speed Optimization Tool - Speed Up Site Now</title>    
                    <meta 
                        name="description" 
                        content="Boost Prestashop store speed instantly with our Prestashop speed optimization tool by Website Speedy. Reduce website loading time & bounce rate. Try for Free" >
                    <meta name="keywords" content="reduce website loading time, improve page speed, Core Web Vitals scores, render blocking" >
                    <?php 
        }

        elseif($actual_link==HOST_URL.'systeme-speed-optimization.php' || $actual_link == HOST_URL.'systeme-speed-optimization.php/')
            {
                ?>
                <title>Systeme.io Speed Optimization Tool - Increase Systeme.io Site Speed</title>    
                <meta 
                    name="description" 
                    content="Speed up Systeme.io site with our Systeme.io speed optimization tool. Boost performance, website speed & ROI easily with Website Speedy. Try Now." >
                <meta name="keywords" content=" Systeme.io Speed Optimization, improve site speed, make website faster, reduce website loading time" >
                <?php 
    }

        elseif($actual_link==HOST_URL.'website-performance-optimization.php' || $actual_link == HOST_URL.'website-performance-optimization.php/')
            {
                ?>
                <title>Website Performance Optimization Tool | Improve Site Speed</title>    
                <meta 
                    name="description" 
                    content="Enhance your website's speed & performance with our top-notch Website Performance Optimization Tool. Boost site speed & user experience effortlessly. Try it now" >
                <meta name="keywords" content="reduce website loading time, improve page speed, Core Web Vitals scores, render blocking" >
                <?php 
    }
    elseif($actual_link==HOST_URL.'linode-speed-optimization.php' || $actual_link == HOST_URL.'linode-speed-optimization.php/')
        {
            ?>
            <title>Linode Speed Optimization Tool - Speed Up Linode Website</title>    
            <meta 
                name="description" 
                content="Enhance your Linode website's speed with our optimization tool. Accelerate loading times effortlessly for a smoother user experience. Unlock faster results for your Linode site!" >
            <meta name="keywords" content="reduce website loading time, improve page speed, Core Web Vitals scores, render blocking" >
            <?php 
}

elseif($actual_link==HOST_URL.'cs-cart-speed-optimization.php' || $actual_link == HOST_URL.'cs-cart-speed-optimization.php/')
    {
        ?>
        <title>CS-Cart Speed Optimization Tool - Speed Up CS-Cart Website</title>    
        <meta 
            name="description" 
            content="Boost your CS-Cart website's speed with our Optimization Tool. Turbocharge loading times for a smoother user experience. Optimize and elevate your CS-Cart site's performance effortlessly!">
        <meta name="keywords" content="reduce website loading time, improve page speed, Core Web Vitals scores, render blocking" >
        <?php 
}
elseif($actual_link==HOST_URL.'weblium-speed-optimization.php' || $actual_link == HOST_URL.'weblium-speed-optimization.php/')
    {
        ?>
        <title>Weblium Speed Optimization Tool - Speed Up Weblium Website</title>    
        <meta 
            name="description" 
            content="Optimize your Weblium website with our Speed Optimization Tool. Elevate user experience and accelerate your site's performance effortlessly. Experience lightning-fast results!">
        <meta name="keywords" content="reduce website loading time, improve page speed, Core Web Vitals scores, render blocking" >
        <?php 
}
elseif($actual_link==HOST_URL.'microweberi-speed-optimization.php' || $actual_link == HOST_URL.'microweberi-speed-optimization.php/')
    {
        ?>
        <title>Microweber Speed Optimization Tool - Speed Up Microweber Website</title>    
        <meta 
            name="description" 
            content="Boost your Microweber website's speed with our Optimization Tool. Streamline loading times effortlessly for a seamless user experience. Elevate and optimize your Microweber site's performance to new heights!">
        <meta name="keywords" content="reduce website loading time, improve page speed, Core Web Vitals scores, render blocking" >
        <?php 
}
elseif($actual_link==HOST_URL.'kajabi-speed-optimization.php' || $actual_link == HOST_URL.'kajabi-speed-optimization.php/')
    {
        ?>
        <title>Kajabi Speed Optimization Tool - Speed Up Kajabi Website</title>    
        <meta 
            name="description" 
            content="Enhance your Kajabi website's speed with our Optimization Tool. Turbocharge loading times for a seamless user experience. Optimize and elevate your Kajabi site's performance effortlessly!">
        <meta name="keywords" content="reduce website loading time, improve page speed, Core Web Vitals scores, render blocking" >
        <?php 
}
elseif($actual_link==HOST_URL.'brizy-cloud-speed-optimization.php' || $actual_link == HOST_URL.'brizy-cloud-speed-optimization.php/')
    {
        ?>
        <title>Brizy Cloud Speed Optimization Tool - Speed Up Brizy Cloud Website</title>    
        <meta 
            name="description" 
            content="Accelerate your Brizy Cloud website with our Speed Optimization Tool. Effortlessly boost loading times for a smoother user experience. Optimize and enhance your Brizy Cloud site's speed today.">
        <meta name="keywords" content="reduce website loading time, improve page speed, Core Web Vitals scores, render blocking" >
        <?php 
}
    elseif($actual_link==HOST_URL.'saas-speed-optimization.php' || $actual_link == HOST_URL.'saas-speed-optimization.php/')
        {
            ?>
            <title>SaaS Performance Optimization Tool | Improve SaaS Speed</title>    
            <meta 
                name="description" 
                content="Enhance your SaaS performance with our optimization tool. Website Speedy Improve speed and efficiency for better user experiences. Improve SaaS site speed now." >
            <meta name="keywords" content="reduce website loading time, improve page speed, Core Web Vitals scores, render blocking" >
            <?php 
}

        elseif($actual_link==HOST_URL.'blogs/the-ultimate-guide-for-squarespace-speed-optimization.php' || $actual_link == HOST_URL.'blogs/the-ultimate-guide-for-squarespace-speed-optimization.php/')
            {
                ?>
                <title>Squarespace Speed Optimization: The Ultimate Guide</title>    
                <meta 
                    name="description" 
                    content="If you need some extra help with Squarespace speed optimization, Website Speedy is the ultimate solution for optimizing the speed of your Squarespace site." >
                <meta name="keywords" content="reduce website loading time, improve page speed, Core Web Vitals scores, render blocking" >
                <meta name="robots" content="index, follow" >
                <?php 
    }

    elseif($actual_link==HOST_URL.'blogs/a-comprehensive-guide-to-improving-shopify-core-web-vitals-scores.php' || $actual_link == HOST_URL.'blogs/a-comprehensive-guide-to-improving-shopify-core-web-vitals-scores.php/')
        {
            ?>
            <title>Boost Your Shopify Core Web Vitals Scores: A Comprehensive Guide</title>    
            <meta 
                name="description" 
                content="This blog aims to provide detailed guidance on how you can enhance your Shopify core web vitals score. Each core web vital metric will be analyzed in-depth" >
            <meta name="keywords" content="reduce website loading time, improve page speed, Core Web Vitals scores, render blocking" >
            <meta name="robots" content="index, follow" >
            <?php 
}

elseif($actual_link==HOST_URL.'blogs/guide-to-interaction-to-the-next-paint-inp-core-web-vital.php' || $actual_link == HOST_URL.'blogs/guide-to-interaction-to-the-next-paint-inp-core-web-vital.php/')
    {
        ?>
        <title>A Guide to Interaction to the Next Paint (INP) Core Web Vital</title>    
        <meta 
            name="description" 
            content="The Interaction to Next Paint (INP) measures the efficiency of your webpage in handling visitor interactions. It evaluates how swiftly your page responds" >
        <meta name="keywords" content="reduce website loading time, improve page speed, Core Web Vitals scores, render blocking" >
        <meta name="robots" content="index, follow" >
        <?php 
}
elseif($actual_link==HOST_URL.'blogs/guide-to-boost-your-web-page-speed.php' || $actual_link == HOST_URL.'blogs/guide-to-boost-your-web-page-speed.php/')
    {
        ?>
        <title>Speed Up Your Website: Quick Step-by-Step Guide</title>    
        <meta 
            name="description" 
            content="In today's digital age, web page speed is everything. It can make or break the success of your website and, ultimately, your business." >
        <meta name="keywords" content="reduce website loading time, improve page speed, Core Web Vitals scores, render blocking" >
        <meta name="robots" content="index, follow" >
        <?php 
}  
elseif($actual_link==HOST_URL.'blogs/a-guide-to-duda-speed-optimization.php' || $actual_link == HOST_URL.'blogs/a-guide-to-duda-speed-optimization.php/')
    {
        ?>
        <title>A Guide to Duda Speed Optimization | Maximizing Performance</title>    
        <meta 
            name="description" 
            content="Website Speedy is practically proven tool for Duda speed optimization, it identifies & compresses large files on site, reducing file sizes without compromising quality." >
        <meta name="keywords" content="reduce website loading time, improve page speed, Core Web Vitals scores, render blocking" >
        <meta name="robots" content="index, follow" >
        <?php 
}    
elseif($actual_link==HOST_URL.'about-us.php' || $actual_link == HOST_URL.'about-us.php/')
    {
        ?>
        <title>About Us – Website Speedy | Company, Vision & Way Ahead</title>    
        <meta 
            name="description" 
            content=" Know about Website Speedy and explore our conviction that every individual should have website with exceptional performance and lighting fast speed." >
        <meta name="keywords" content="About Website Speedy, About WebsiteSpeedy, WebsiteSpeedy vision" >
        <meta property="og:image" content="<?=$bunny_image?>about-page-og-img.webp">  
        <?php 
}
elseif($actual_link==HOST_URL.'platforms.php' || $actual_link == HOST_URL.'platforms.php/')
    {
        ?>
        <title>Boost Website Speed on Leading Platforms | WebsiteSpeedy</title>    
        <meta 
            name="description" 
            content="WebsiteSpeedy custom script is compatible with top website development platforms. Utilize our DIY tool to effortlessly boost your website's speed & performance" >
        <meta name="keywords" content="Platforms Website Speedy, Platforms WebsiteSpeedy, WebsiteSpeedy vision" >
        <meta property="og:image" content="<?=$bunny_image?>shopify-banner-hero551.webp">  
        <?php 
}

elseif($actual_link==HOST_URL.'blogs/common-clickfunnels-speed-issues-and-how-to-fix-them.php' || $actual_link == HOST_URL.'blogs/common-clickfunnels-speed-issues-and-how-to-fix-them.php/')
    {
        ?>
        <title>Fixing ClickFunnels Speed Issues: Expert Solutions</title>    
        <meta 
            name="description" 
            content="Like any other platform, Clickfunnels has its own set of challenges. One of the most common issues that users face is the speed of their funnels" >
        <meta name="keywords" content="reduce website loading time, improve page speed, Core Web Vitals scores, render blocking" >
        <meta name="robots" content="index, follow" >
        <?php 
}   

elseif($actual_link==HOST_URL.'blogs/ways-to-boost-bigcommerce-site-speed-to-increase-traffic.php' || $actual_link == HOST_URL.'blogs/ways-to-boost-bigcommerce-site-speed-to-increase-traffic.php/')
    {
        ?>
        <title>Speed Up BigCommerce Store: 7 Ways to Skyrocket Traffic</title>    
        <meta 
            name="description" 
            content="Boost your BigCommerce website's speed and performance with these powerful techniques. By implementing these strategies, you can ensure customers satisfaction" >
        <meta name="keywords" content="" >
        <meta name="robots" content="index, follow" >
        <?php 
}   

elseif($actual_link==HOST_URL.'blogs/a-guide-to-scoring-perfect-100-on-google-page-speed-insights.php' || $actual_link == HOST_URL.'blogs/a-guide-to-scoring-perfect-100-on-google-page-speed-insights.php/')
    {
        ?>
        <title>A Guide to Scoring Perfect 100% on Google PageSpeed Insights</title>    
        <meta 
            name="description" 
            content="Achieving a perfect 100% score on Google PageSpeed Insights is crucial for improving user experience and search engine rankings for your website." >
        <meta name="keywords" content="" >
        <meta name="robots" content="index, follow" >
        <?php 
}  

elseif($actual_link==HOST_URL.'blogs/discover-the-top-reasons-why-website-speed-matters.php' || $actual_link == HOST_URL.'blogs/discover-the-top-reasons-why-website-speed-matters.php/')
    {
        ?>
        <title>The Need for Speed: Unveiling Key Website Speed Benefits</title>    
        <meta 
            name="description" 
            content="Website speed is a critical factor that affects user experience, search engine rankings, & overall business success. Slow websites lead to high bounce rates" >
        <meta name="keywords" content="" >
        <meta name="robots" content="index, follow" >
        <?php 
}  

elseif($actual_link==HOST_URL.'blogs/proven-tips-to-boost-your-shopify-stores-mobile-site-speed.php' || $actual_link == HOST_URL.'blogs/proven-tips-to-boost-your-shopify-stores-mobile-site-speed.php/')
    {
        ?>
        <title>Speed Up Shopify Store: Proven Tips for Mobile Site Success</title>    
        <meta 
            name="description" 
            content="As online shopping continues to grow in popularity, it's becoming increasingly important for ecommerce businesses to optimize their mobile site speed." >
        <meta name="keywords" content="" >
        <meta name="robots" content="index, follow" >
        <?php 
}  
elseif($actual_link==HOST_URL.'blogs/best-ways-to-increase-organic-traffic-to-your-bigcommerce-store.php' || $actual_link == HOST_URL.'blogs/best-ways-to-increase-organic-traffic-to-your-bigcommerce-store.php/')
    {
        ?>
        <title>Top Strategies to Drive Visitors to Your BigCommerce Store</title>    
        <meta 
            name="description" 
            content="Increasing organic traffic to your BigCommerce store is crucial for long-term success and growth. By implementing the best practices mentioned in this content." >
        <meta name="keywords" content="" >
        <meta name="robots" content="index, follow" >
        <?php 
}  
elseif($actual_link==HOST_URL.'blogs/webnode-speed-optimization-ultimate-guide.php' || $actual_link == HOST_URL.'blogs/webnode-speed-optimization-ultimate-guide.php/')
    {
        ?>
        <title>Webnode Speed Optimization: The Ultimate Guide</title>    
        <meta 
            name="description" 
            content="When it comes to Webnode speed optimization, optimizing your content is really important. Quality content is crucial for successful search engine optimization." >
        <meta name="keywords" content="" >
        <meta name="robots" content="index, follow" >
        <?php 
}  
elseif($actual_link==HOST_URL.'blogs/hubspot-speed-optimization-ultimate-guide.php' || $actual_link == HOST_URL.'blogs/hubspot-speed-optimization-ultimate-guide.php/')
    {
        ?>
        <title>HubSpot Speed Optimization: The Ultimate Guide</title>    
        <meta 
            name="description" 
            content="HubSpot speed optimization plays a crucial role in enhancing user experience, increasing website performance, and improving search engine rankings." >
        <meta name="keywords" content="" >
        <meta name="robots" content="index, follow" >
        <?php 
}  
elseif($actual_link==HOST_URL.'blogs/ultimate-ecwid-speed-optimization-guide.php' || $actual_link == HOST_URL.'blogs/ultimate-ecwid-speed-optimization-guide.php/')
    {
        ?>
        <title> The Ultimate Ecwid Speed Optimization Guide</title>    
        <meta 
            name="description" 
            content="Boost your Ecwid store's performance with our Ultimate Ecwid Speed Optimization Guide. Enhance user experience and boost sales. Don't miss out!" >
        <meta name="keywords" content="" >
        <meta name="robots" content="index, follow" >
        <?php 
}  
elseif($actual_link==HOST_URL.'blogs/google-pagespeed-insights-tool-apply-recommendations.php' || $actual_link == HOST_URL.'blogs/google-pagespeed-insights-tool-apply-recommendations.php/')
    {
        ?>
        <title>Recommendations for Google's PageSpeed Insights Tool</title>    
        <meta 
            name="description" 
            content="Many of you utilize the free PageSpeed Insights tool and observe the score it provides. This is an indication of the quality of your user experience." >
        <meta name="keywords" content="" >
        <meta name="robots" content="index, follow" >
        <?php 
}
elseif($actual_link==HOST_URL.'case-study.php' || $actual_link == HOST_URL.'case-study.php/')
    {
        ?>
        <title>Speed Optimization Case Study | Website Speedy</title>    
        <meta 
            name="description" 
            content="Discover how Website Speedy improved website performance through effective speed optimization strategies. Read our insightful case study now." >
        <meta name="keywords" content="" >
        <meta name="robots" content="index, follow" >
        <?php 
}
elseif($actual_link==HOST_URL.'case-study/nailary-case-study.php' || $actual_link == HOST_URL.'case-study/nailary-case-study.php/')
    {
        ?>
        <title>Nailary Case Study | Website Speedy</title>    
        <meta 
            name="description" 
            content="Discover how Nailary, a leading nail salon, improved website speed to enhance user experience and boost online engagement. Read the Nailary case study now." >
        <meta name="keywords" content="" >
        <meta name="robots" content="index, follow" >
        <?php 
} 
elseif($actual_link==HOST_URL.'blogs/big-cartel-speed-optimization-complete-guide.php' || $actual_link == HOST_URL.'blogs/big-cartel-speed-optimization-complete-guide.php/')
    {
        ?>
        <title>Big Cartel Speed Optimization: A Complete Guide</title>    
        <meta 
            name="description" 
            content="Maximize your store's speed with our comprehensive Big Cartel Speed optimization guide. Improve performance and user experience to boost conversions." >
        <meta name="keywords" content="" >
        <meta name="robots" content="index, follow" >
        <?php 
} 
elseif($actual_link==HOST_URL.'blogs/in-depth-guide-to-prestashop-speed-optimization.php' || $actual_link == HOST_URL.'blogs/in-depth-guide-to-prestashop-speed-optimization.php/')
    {
        ?>
        <title>An In-depth Guide to Prestashop Speed Optimization</title>    
        <meta 
            name="description" 
            content="To start with the Prestashop speed optimization process, you must know its current loading time. There are several tools available that can help you analyze" >
        <meta name="keywords" content="" >
        <meta name="robots" content="index, follow" >
        <?php 
} 
elseif($actual_link==HOST_URL.'blogs/how-to-optimize-shopify-store-speed-on-mobile.php' || $actual_link == HOST_URL.'blogs/how-to-optimize-shopify-store-speed-on-mobile.php/')
    {
        ?>
        <title>Best Ways To Optimize A Shopify Store's Speed On Mobile</title>    
        <meta 
            name="description" 
            content="Enhance your Shopify store's mobile speed for a superior user experience. Our top strategies will optimize performance, boost sales, & reduce bounce rates" >
        <meta name="keywords" content="" >
        <meta name="robots" content="index, follow" >
        <?php 
} 
elseif($actual_link==HOST_URL.'blogs/top-11-speed-optimization-techniques-for-ecommerce-sites.php' || $actual_link == HOST_URL.'blogs/top-11-speed-optimization-techniques-for-ecommerce-sites.php/')
    {
        ?>
        <title>Top 11 Speed Optimization Techniques for Ecommerce Sites in 2023</title>    
        <meta 
            name="description" 
            content="Enhance eCommerce site speed in 2023 with these top 11 optimization techniques. Elevate performance and user experience for better results" >
        <meta name="keywords" content="" >
        <meta name="robots" content="index, follow" >
        <?php 
} 
elseif($actual_link==HOST_URL.'blogs/how-to-analyze-and-optimize-the-speed-of-ecommerce-websites.php' || $actual_link == HOST_URL.'blogs/how-to-analyze-and-optimize-the-speed-of-ecommerce-websites.php/')
    {
        ?>
        <title>How to Analyze & Optimize the Speed of Ecommerce Websites</title>    
        <meta 
            name="description" 
            content="Enhance Ecommerce Website Performance: Discover Insights on Analyzing & Optimizing Speed. Elevate UX, Accelerate Conversions with Proven Expert Strategies." content="" >
        <meta name="robots" content="index, follow" >
        <?php 
} 
elseif($actual_link==HOST_URL.'blogs/15-effective-strategies-to-reduce-bounce-rate-on-your-website.php' || $actual_link == HOST_URL.'blogs/15-effective-strategies-to-reduce-bounce-rate-on-your-website.php/')
    {
        ?>
        <title>Reducing Bounce Rates on Your Website: 15 Effective Strategies</title>    
        <meta 
            name="description" 
            content="Learn how to effectively reduce bounce rate on your website with these 15 proven strategies. Keep visitors engaged, improve user retention, & boost online success" content="" >
        <meta name="robots" content="index, follow" >
        <?php 
} 
elseif($actual_link==HOST_URL.'blogs/how-to-boost-the-speed-of-your-ecommerce-Shopify-store.php' || $actual_link == HOST_URL.'blogs/how-to-boost-the-speed-of-your-ecommerce-Shopify-store.php/')
    {
        ?>
        <title>How to Boost the Speed of Your Ecommerce Shopify Store?</title>    
        <meta 
            name="description" 
            content="Enhance your Shopify store's speed and performance with expert tips. Optimize Ecommerce site for faster, smoother user experience. Learn more" >
        <meta name="robots" content="index, follow" >
        <?php 
} 
elseif($actual_link==HOST_URL.'blogs/importance-of-speed-optimization-for-your-bigcommerce-store.php' || $actual_link == HOST_URL.'blogs/importance-of-speed-optimization-for-your-bigcommerce-store.php/')
    {
        ?>
        <title>Why speed optimization is so important for your Bigcommerce store</title>    
        <meta 
            name="description" 
            content="Enhance user experience & drive conversions! Learn why speed optimization is vital for your Bigcommerce store's success. Read more now." >
        <meta name="robots" content="index, follow" >
        <?php 
} 
elseif($actual_link==HOST_URL.'blogs/the-ultimate-guide-to-bigcommerce-speed-optimization-to-increase-organic-traffic.php' || $actual_link == HOST_URL.'blogs/the-ultimate-guide-to-bigcommerce-speed-optimization-to-increase-organic-traffic.php/')
    {
        ?>
        <title>The Ultimate Guide to Bigcommerce Speed Optimization</title>    
        <meta 
            name="description" 
            content="Discover top strategies in our comprehensive guide - The Ultimate Bigcommerce Speed Optimization Handbook. Turbocharge your website today" >
        <meta name="robots" content="index, follow" >
        <?php 
} 
elseif($actual_link==HOST_URL.'blogs/10-effective-strategies-for-speeding-up-shopify-store.php' || $actual_link == HOST_URL.'blogs/10-effective-strategies-for-speeding-up-shopify-store.php/')
    {
        ?>
        <title>10 Effective Strategies for Speeding Up Shopify Store</title>    
        <meta 
            name="description" 
            content="Boost your Shopify store's performance with these 10 powerful strategies for improved speed and user experience. Elevate your online business today" >
        <meta name="robots" content="index, follow" >
        <?php 
} 
elseif($actual_link==HOST_URL.'blogs/top-strategies-to-optimize-your-wix-stores-speed.php' || $actual_link == HOST_URL.'blogs/top-strategies-to-optimize-your-wix-stores-speed.php/')
    {
        ?>
        <title>Top Strategies To Optimize Your Wix Store's Speed </title>    
        <meta 
            name="description" 
            content="Elevate your Wix store's performance with our top-speed optimization strategies. Boost loading times for better user experiences. Read more" >
        <meta name="robots" content="index, follow" >
        <?php 
} 
elseif($actual_link==HOST_URL.'blogs/how-to-boost-speed-of-bigcommerce-store-for-better-performance.php' || $actual_link == HOST_URL.'blogs/how-to-boost-speed-of-bigcommerce-store-for-better-performance.php/')
    {
        ?>
        <title>How to Boost Speed of Bigcommerce Store for Better Performance</title>    
        <meta 
            name="description" 
            content="Learn effective strategies to enhance your Bigcommerce store's speed and performance for seamless customer experiences. Discover optimization tips now" >
        <meta name="robots" content="index, follow" >
        <?php 
} 
elseif($actual_link==HOST_URL.'blogs/how-can-i-improve-my-webflow-website-speed.php' || $actual_link == HOST_URL.'blogs/how-can-i-improve-my-webflow-website-speed.php/')
    {
        ?>
        <title>How Can I Speed Up My Webflow Website?</title>    
        <meta 
            name="description" 
            content="Discover effective strategies to optimize the speed of your Webflow website and enhance user experience. Boost performance with expert tips and techniques." >
        <meta name="robots" content="index, follow" >
        <?php 
} 
elseif($actual_link==HOST_URL.'blogs/what-are-the-best-ways-to-speed-up-bigcommerce-store.php' || $actual_link == HOST_URL.'blogs/what-are-the-best-ways-to-speed-up-bigcommerce-store.php/')
    {
        ?>
        <title>What Are the Best Ways to Speed Up BigCommerce Store in 2023?</title>    
        <meta 
            name="description" 
            content="Learn expert tips & techniques to optimize speed of your BigCommerce store. Discover best ways to boost speed & enhance user experience for higher conversions." >
        <meta name="robots" content="index, follow" >
        <meta property="og:image" content="<?=$bunny_image?>the_Best_Ways_to_Speed_Up_My_BigCommerce_Store.webp"> 
        <?php 
} 
elseif($actual_link==HOST_URL.'blogs/how-to-optimize-bigcommerce-product-pages-for-halloween.php' || $actual_link == HOST_URL.'blogs/how-to-optimize-bigcommerce-product-pages-for-halloween.php/')
    {
        ?>
        <title>How To Optimize Bigcommerce Product Pages For Halloween</title>    
        <meta 
            name="description" 
            content="Maximize your Halloween sales with expert tips on optimizing Bigcommerce product pages. Learn essential SEO techniques to boost visibility and conversions" >
        <meta name="robots" content="index, follow" >
        <meta property="og:image" content="<?=$bunny_image?>Ways_to_Optimize_Bigcommerce_Product_Pages_for_Halloween.webp"> 
        <?php 
} 
elseif($actual_link==HOST_URL.'blogs/how-can-you-boost-bigcommerce-conversion-rates.php' || $actual_link == HOST_URL.'blogs/how-can-you-boost-bigcommerce-conversion-rates.php/')
    {
        ?>
        <title>How Can You Boost BigCommerce Conversion Rates?</title>    
        <meta 
            name="description" 
            content="Discover Proven Strategies to Skyrocket Your BigCommerce Conversion Rates! Learn Effective Techniques and Tips to Optimize Your Online Store for Maximum Sales." >
        <meta name="robots" content="index, follow" >
        <meta property="og:image" content="<?=$bunny_image?>Top_Strategies_for_Boosting_BigCommerce_Conversion_Rates.webp"> 
        <?php 
} 
elseif($actual_link==HOST_URL.'blogs/why-bigcommerce-websites-should-be-fast-and-reliable.php' || $actual_link == HOST_URL.'blogs/why-bigcommerce-websites-should-be-fast-and-reliable.php/')
    {
        ?>
        <title>Why BigCommerce Websites Should Be Fast and Reliable?</title>    
        <meta 
            name="description" 
            content="Nowadays, the speed of your website determines the reputation and the position of your e-commerce store. This thing makes it crucial for businesses." >
        <meta name="robots" content="index, follow" >
        <meta property="og:image" content="<?=$bunny_image?>Why_a_Fast_and_Reliable_BigCommerce_Website_Is_a_Must.webp"> 
        <?php 
} 
elseif($actual_link==HOST_URL.'blogs/how-to-maximize-bigcommerce-speed-for-seo.php' || $actual_link == HOST_URL.'blogs/how-to-maximize-bigcommerce-speed-for-seo.php/')
    {
        ?>
        <title>Maximizing BigCommerce Speed for SEO Success</title>    
        <meta 
            name="description" 
            content="Boost your SEO rankings with a lightning-fast BigCommerce store. Explore expert tips and techniques for optimizing speed and enhancing user experience." >
        <meta name="robots" content="index, follow" >
        <meta property="og:image" content="<?=$bunny_image?>Maximizing_BigCommerce_Speed_for_SEO_Success.webp"> 
        <?php 
} 
elseif($actual_link==HOST_URL.'blogs/optimizing-core-web-vitals-boosting-speed-and-seo-rankings.php' || $actual_link == HOST_URL.'blogs/optimizing-core-web-vitals-boosting-speed-and-seo-rankings.php/')
    {
        ?>
        <title>Optimizing Core Web Vitals: Boosting Speed & SEO Rankings</title>    
        <meta 
            name="description" 
            content="Learn how to enhance Core Web Vitals for faster website performance & improved SEO rankings. Discover effective strategies to optimize speed & user experience" >
        <meta name="robots" content="index, follow" >
        <meta property="og:image" content="<?=$bunny_image?>Boosting_Speed_&_SEO_Rankings_with_Core_Web_Vitals.webp"> 
        <?php 
} 
elseif($actual_link==HOST_URL.'blogs/quick-tips-for-bigcommerce-website-speed-optimization.php' || $actual_link == HOST_URL.'blogs/quick-tips-for-bigcommerce-website-speed-optimization.php/')
    {
        ?>
        <title>Quick Tips for Faster Loading BigCommerce Websites</title>    
        <meta 
            name="description" 
            content="Explore our expert tips for accelerating the loading speed of your BigCommerce website. Discover strategies to optimize performance and enhance user experience" >
        <meta name="robots" content="index, follow" >
        <meta property="og:image" content="<?=$bunny_image?>Quick_Tips_for_Faster_Loading_BigCommerce_Websites.webp"> 
        <?php 
} 
elseif($actual_link==HOST_URL.'fix-systeme-core-web-vitals.php' || $actual_link == HOST_URL.'fix-systeme-core-web-vitals.php/')
    {
        ?>
        <title>Systeme.io Core Web Vitals Easily - Website Speedy</title>    
        <meta 
            name="description" 
            content="Improve Your Systeme.io site Core Web Vitals performance with the help of Website Speedy. This tool improves Google's core web vitals metrics, LCP, FCP, & CLS." >
        <meta property="og:image" content="<?=$bunny_image?>Systeme-Core-Web-Vitals-1.webp"> 
        <?php 
} 
elseif($actual_link==HOST_URL.'fix-saleforce-core-web-vitals.php' || $actual_link == HOST_URL.'fix-saleforce-core-web-vitals.php/')
    {
        ?>
        <title>Salesforce Commerce Core Web Vitals Optimization - Website Speedy</title>    
        <meta 
            name="description" 
            content="Enhance the Core Web Vital of your Salesforce Commerce website with the Website Speedy Tool. And boost the website's performance and improve the user experience effortlessly." >
        <meta property="og:image" content="<?=$bunny_image?>Systeme-Core-Web-Vitals-1.webp"> 
        <?php 
} 
elseif($actual_link==HOST_URL.'fix-oracle-commerce-core-web-vitals.php' || $actual_link == HOST_URL.'fix-oracle-commerce-core-web-vitals.php/')
    {
        ?>
        <title>Oracle Commerce Core Web Vitals Optimization - Website Speedy</title>    
        <meta 
            name="description" 
            content="Improve the core web vitals of your Oracle commerce website with the 'Website Speedy' Tool, ensuring faster performance on all devices for an optimized user experience. Boost your online presence today!" >
        <meta property="og:image" content="<?=$bunny_image?>Systeme-Core-Web-Vitals-1.webp"> 
        <?php 
} 
elseif($actual_link==HOST_URL.'fix-saas-website-core-web-vitals.php' || $actual_link == HOST_URL.'fix-saas-website-core-web-vitals.php/')
    {
        ?>
        <title>SAAS Website Core Web Vitals Optimization - Website Speedy</title>    
        <meta 
            name="description" 
            content="Improve your SAAS website's performance with 'Website Speedy', the ultimate Core Web Vitals optimization tool. Enhance user experience and boost online presence effortlessly. Explore Now!" >
        <meta property="og:image" content="<?=$bunny_image?>Systeme-Core-Web-Vitals-1.webp"> 
        <?php 
} 

elseif($actual_link==HOST_URL.'fix-elastic-path-website-core-web-vitals.php' || $actual_link == HOST_URL.'fix-elastic-path-website-core-web-vitals.php/')
    {
        ?>
        <title>Elastic Path Website Core Web Vitals Optimization - Website Speedy</title>    
        <meta 
            name="description" 
            content="Improve your Elastic Path website's performance with Website Speedy. Enhance user experience with our core web vitals optimization tool." >
        <meta property="og:image" content="<?=$bunny_image?>Systeme-Core-Web-Vitals-1.webp"> 
        <?php 
} 
elseif($actual_link==HOST_URL.'fix-intershop-website-core-web-vitals.php' || $actual_link == HOST_URL.'fix-intershop-website-core-web-vitals.php/')
    {
        ?>
        <title>Intershop Website Core Web Vitals Optimization - Website Speedy</title>    
        <meta 
            name="description" 
            content="Enhance the user experience on your Intershop Website with Website Speedy. Boost your online presence with this speed optimization tool." >
        <meta property="og:image" content="<?=$bunny_image?>Systeme-Core-Web-Vitals-1.webp"> 
        <?php 
} 
elseif($actual_link==HOST_URL.'fix-kibo-commerce-core-web-vitals.php' || $actual_link == HOST_URL.'fix-kibo-commerce-core-web-vitals.php/')
    {
        ?>
        <title>Kibo Commerce Website Core Web Vitals Optimization - Website Speedy</title>    
        <meta 
            name="description" 
            content="Improve your Kibo Commerce website's performance with ‘Website Speedy’ Tool. Enhance the user experience to boost online presence effortlessly." >
        <meta property="og:image" content="<?=$bunny_image?>Systeme-Core-Web-Vitals-1.webp"> 
        <?php 
} 
elseif($actual_link==HOST_URL.'fix-ibm-websphere-commerce-web-vitals.php' || $actual_link == HOST_URL.'fix-ibm-websphere-commerce-web-vitals.php/')
    {
        ?>
        <title>IBM WebSphere Commerce Web Vitals Optimization - Website Speedy</title>    
        <meta 
            name="description" 
            content="Boost online performance of your IBM WebSphere Commerce with the ‘Website Speedy’. The core web vitals optimization tool enhances your website and its presence." >
        <meta property="og:image" content="<?=$bunny_image?>Systeme-Core-Web-Vitals-1.webp"> 
        <?php 
} 
elseif($actual_link==HOST_URL.'fix-shopify-plus-website-core-web-vitals.php' || $actual_link == HOST_URL.'fix-shopify-plus-website-core-web-vitals.php/')
    {
        ?>
        <title>Shopify Plus Website Core Web Vitals Optimization - Website Speedy</title>    
        <meta 
            name="description" 
            content="Enhance the user experience on your Shopify Plus website using the core web vitals optimization tool - Website Speedy." >
        <meta property="og:image" content="<?=$bunny_image?>Systeme-Core-Web-Vitals-1.webp"> 
        <?php 
} 
elseif($actual_link==HOST_URL.'fix-cs-cart-core-web-vitals.php' || $actual_link == HOST_URL.'fix-cs-cart-core-web-vitals.php/')
    {
        ?>
        <title>Fix CS-Cart Website Core Web Vitals Easily - Website Speedy</title>    
        <meta 
            name="description" 
            content="Boost your CS-Cart website's performance easily with Website Speedy. Fix Core Web Vitals effortlessly for a faster and better user experience." >
        <meta property="og:image" content="<?=$bunny_image?>Systeme-Core-Web-Vitals-1.webp"> 
        <?php 
} 
elseif($actual_link==HOST_URL.'fix-linode-core-web-vitals.php' || $actual_link == HOST_URL.'fix-linode-core-web-vitals.php/')
    {
        ?>
        <title>Fix Linode Website Core Web Vitals Easily - Website Speedy</title>    
        <meta 
            name="description" 
            content="Optimize Linode website's Core Web Vitals effortlessly with Website Speedy. Improve speed and performance for a superior user experience." >
        <meta property="og:image" content="<?=$bunny_image?>Systeme-Core-Web-Vitals-1.webp"> 
        <?php 
} 
elseif($actual_link==HOST_URL.'fix-microweber-core-web-vitals.php' || $actual_link == HOST_URL.'fix-microweber-core-web-vitals.php/')
    {
        ?>
        <title>Fix Microweber Website Core Web Vitals Easily - Website Speedy</title>    
        <meta 
            name="description" 
            content="Improve Microweber website's Core Web Vitals effortlessly with Website Speedy. Enhance speed and performance for a better user experience." >
        <meta property="og:image" content="<?=$bunny_image?>Systeme-Core-Web-Vitals-1.webp"> 
        <?php 
} 
elseif($actual_link==HOST_URL.'fix-weblium-core-web-vitals.php' || $actual_link == HOST_URL.'fix-weblium-core-web-vitals.php/')
    {
        ?>
        <title>Fix Weblium Website Core Web Vitals Easily - Website Speedy</title>    
        <meta 
            name="description" 
            content="Optimize your website's speed and enhance user experience with Website Speedy, effortlessly resolving Weblium website Core Web Vitals." >
        <meta property="og:image" content="<?=$bunny_image?>Systeme-Core-Web-Vitals-1.webp"> 
        <?php 
} 
elseif($actual_link==HOST_URL.'fix-brizy-core-web-vitals.php' || $actual_link == HOST_URL.'fix-brizy-core-web-vitals.php/')
    {
        ?>
        <title>Fix Brizy Website Core Web Vitals Easily - Website Speedy</title>    
        <meta 
            name="description" 
            content="Effortlessly improve Brizy website's Core Web Vitals with Website Speedy. Optimize speed and performance for a seamless user experience." >
        <meta property="og:image" content="<?=$bunny_image?>Systeme-Core-Web-Vitals-1.webp"> 
        <?php 
} 
elseif($actual_link==HOST_URL.'fix-kajabi-core-web-vitals.php' || $actual_link == HOST_URL.'fix-kajabi-core-web-vitals.php/')
    {
        ?>
        <title>Fix Kajabi Website Core Web Vitals Easily - Website Speedy</title>    
        <meta 
            name="description" 
            content="Improve the Kajabi website's Core Web Vitals effortlessly with Website Speedy. Optimize speed and performance for an improved user experience." >
        <meta property="og:image" content="<?=$bunny_image?>Systeme-Core-Web-Vitals-1.webp"> 
        <?php 
} 
elseif($actual_link==HOST_URL.'oracle-commerce-performance-optimization.php' || $actual_link == HOST_URL.'oracle-commerce-performance-optimization.php/')
    {
        ?>
        <title>Oracle Commerce Speed Optimization Tool | Improve Site Speed</title>    
        <meta 
            name="description" 
            content="Enhance your Oracle Commerce site's speed with our optimization tool. Boost site speed and improve user experience effortlessly with Website Speedy Tool." >
        <meta property="og:image" content="<?=$bunny_image?>Systeme-Core-Web-Vitals-1.webp"> 
        <?php 
}    

elseif($actual_link==HOST_URL.'magento-commerce-performance-optimization.php' || $actual_link == HOST_URL.'magento-commerce-performance-optimization.php/')
    {
        ?>
        <title>Magento Speed Optimization Tool | Improve Website Speed</title>    
        <meta 
            name="description" 
            content="Enhance your Magento Commerce site's speed with our optimization tool. Boost site speed and improve user experience effortlessly with Website Speedy." >
        <meta property="og:image" content="<?=$bunny_image?>Systeme-Core-Web-Vitals-1.webp"> 
        <?php 
} 


elseif($actual_link==HOST_URL.'salesforce-performance-optimization.php' || $actual_link == HOST_URL.'salesforce-performance-optimization.php/')
    {
        ?>
        <title>Salesforce Commerce Cloud Speed & Performance Optimization Tool</title>    
        <meta 
            name="description" 
            content="Enhance the speed & performance of your Salesforce Commerce Cloud with our optimization tool. Boost user experience & site efficiency effortlessly. Explore now." >
        <meta property="og:image" content="<?=$bunny_image?>Systeme-Core-Web-Vitals-1.webp"> 
        <?php 
} 

elseif($actual_link==HOST_URL.'sap-commerce-cloud-speed-optimization.php' || $actual_link == HOST_URL.'sap-commerce-cloud-speed-optimization.php/')
    {
        ?>
        <title>SAP Commerce Cloud Performance & Speed Optimization Tool</title>    
        <meta 
            name="description" 
            content="Enhance SAP Commerce Cloud's efficiency with our powerful Performance & Speed Optimization Tool. Boost loading times & optimize performance effortlessly." >
        <meta property="og:image" content="<?=$bunny_image?>Systeme-Core-Web-Vitals-1.webp"> 
        <?php 
} 

elseif($actual_link==HOST_URL.'elastic-path-speed-optimization.php' || $actual_link == HOST_URL.'elastic-path-speed-optimization.php/')
    {
        ?>
        <title>Elastic Path Site Performance & Speed Optimization Tool</title>    
        <meta 
            name="description" 
            content="Enhance your Elastic Path site's performance & speed with our optimization tool - Website Speedy. Maximize efficiency & user experience effortlessly. Try it now." >
        <meta property="og:image" content="<?=$bunny_image?>Systeme-Core-Web-Vitals-1.webp"> 
        <?php 
} 
elseif($actual_link==HOST_URL.'intershop-speed-optimization.php' || $actual_link == HOST_URL.'intershop-speed-optimization.php/')
    {
        ?>
        <title>Intershop Website Performance & Speed Optimization Tool</title>    
        <meta 
            name="description" 
            content="Boost Intershop website's performance & speed with our powerful speed optimization tool. Optimize, enhance, & elevate your site's speed effortlessly. Try it now" >
        <meta property="og:image" content="<?=$bunny_image?>Systeme-Core-Web-Vitals-1.webp"> 
        <?php 
} 
elseif($actual_link==HOST_URL.'kibo-commerce-speed-optimization.php' || $actual_link == HOST_URL.'kibo-commerce-speed-optimization.php/')
    {
        ?>
        <title>Kibo Commerce Store Speed & Performance Optimization Tool</title>    
        <meta 
            name="description" 
            content="Boost your Kibo Commerce store's speed and performance effortlessly with the Website Speedy tool. Boost user experience and conversions today. Try it now." >
        <meta property="og:image" content="<?=$bunny_image?>Systeme-Core-Web-Vitals-1.webp"> 
        <?php 
} 

elseif($actual_link==HOST_URL.'bigcommerce-enterprise-speed-optimization.php' || $actual_link == HOST_URL.'bigcommerce-enterprise-speed-optimization.php/')
    {
        ?>
        <title>BigCommerce Enterprise Store Speed Optimization Tool</title>    
        <meta 
            name="description" 
            content="Enhance BigCommerce Enterprise store speed with our optimization tool. Boost performance and user experience effortlessly. Explore Website Speedy now." >
        <meta property="og:image" content="<?=$bunny_image?>Systeme-Core-Web-Vitals-1.webp"> 
        <?php 
} 

elseif($actual_link==HOST_URL.'shopify-plus-speed-optimization.php' || $actual_link == HOST_URL.'shopify-plus-speed-optimization.php/')
    {
        ?>
        <title>Shopify Plus Store Speed & Performance Optimization Tool</title>    
        <meta 
            name="description" 
            content="Boost Shopify Plus store's performance with our speed optimization tool - Website Speedy. Enhance user experience & drive conversions efficiently. Explore now" >
        <meta property="og:image" content="<?=$bunny_image?>Systeme-Core-Web-Vitals-1.webp"> 
        <?php 
} 
?>







<script type='text/javascript' src="https://websitespeedycdn.b-cdn.net/speedyweb/ecmrx_572/slick.large.js"></script>
<script type='text/javascript' src="https://websitespeedycdn.b-cdn.net/speedyweb/ecmrx_572/slick.max.js"></script>
<script type='text/javascript' src="https://websitespeedycdn.b-cdn.net/speedyweb/ecmrx_572/slick.medium.js"></script>




    <!-- CSS  -->
    <link rel="stylesheet" href="<?=$bunny_css?>style-cdn-03-08-updated.css"  onload="this.onload=null;this.rel='stylesheet'"  >
    <link rel="stylesheet" href="<?=$bunny_css?>update.css" onload="this.onload=null;this.rel='stylesheet'"  >
    <link rel="stylesheet" href="/assets/css/custom.css"  onload="this.onload=null;this.rel='stylesheet'" >


    <script src="<?=$bunny_js?>jQuery.js" type="text/javascript"  ></script>



<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "Corporation",
  "name": "Website Speedy",
  "url": "<?=HOST_URL?>",
  "logo": "https:<?=$bunny_image?>Speedy-LOGO-dark-new.webp",
  "contactPoint": {
    "@type": "ContactPoint",
    "telephone": "+91 7015897275",
    "contactType": "technical support",
    "areaServed": "IN",
    "availableLanguage": ["en","Hindi"]
  },
  "sameAs": [
    "https://www.facebook.com/websitespeedy",
    "https://www.youtube.com/channel/UC044W4qzCU9wiF1DJhl3puA",
    "https://www.linkedin.com/company/websitespeedy/",
    "https://www.instagram.com/websitespeedy/"
  ]
}
</script>

<!-- //123 -->

<script>
    !function(){var e="rest.happierleads.com/v3/script?clientId=grmgQM1j158kvYzYGkZJtZ&version=4.0.0",
    t=document.createElement("script");window.location.protocol.split(":")[0];
    t.src="https://"+e;var c=document.getElementsByTagName("script")[0];
    t.async = true;
    t.onload = function(){ new Happierleads.default };
    c.parentNode.insertBefore(t,c)}();
</script>


</head>
<body>
<img width = "3840" height = "2160" style = "pointer-events: none; position: absolute; top: 0; left: 0; width: 96vw; height: 96vh; max-width: 99vw; max-height: 99vh;" src = "data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iVVRGLTgiPz48c3ZnIHdpZHRoPSI5OTk5OXB4IiBoZWlnaHQ9Ijk5OTk5cHgiIHZpZXdCb3g9IjAgMCA5OTk5OSA5OTk5OSIgdmVyc2lvbj0iMS4xIiB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHhtbG5zOnhsaW5rPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5L3hsaW5rIj48ZyBzdHJva2U9Im5vbmUiIGZpbGw9Im5vbmUiIGZpbGwtb3BhY2l0eT0iMCI+PHJlY3QgeD0iMCIgeT0iMCIgd2lkdGg9Ijk5OTk5IiBoZWlnaHQ9Ijk5OTk5Ij48L3JlY3Q+IDwvZz4gPC9zdmc+" alt="body-img">

<!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-MK5VN7M"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->



<?php

$currentPage = $_SERVER['REQUEST_URI'];
require_once "schema.php";
?>



    <!-- Header Start -->
    <div class="header__section header-ch" >
        <div class="section__wrapper" id="sticky-header">
            <header id="header">
                <div class="logo__wrapper">
                    <a href="/" class="unstyled__link" >
                        <img width="220" height="67" loading="lazy" class="logo__image" src="<?=$bunny_image?>website_speedy_logo_21.svg" alt="Website Speedy Logo" >
                    </a>
                </div>
                <div class="navigation__wrapper" >
                    <ul>
                        <li class="megamenu__link" id="megaMenuOne">
                            <span class="menu__link link__menu">Platforms <img width="15" height="15" loading="lazy" src="<?=$bunny_image?>arrow-down.png" alt="arrow-down" class="menu__arrow"></span>
                            <div class="megamenu megamenu-new" >
                                <div class="inner__wrapper__menu__grid">
                                <div class="menu__grid" >
                                        <div class="menu__title">Website Speed Optimization</div>
                                        <div class="menu-list-wrap">
                                            <ul>
                                                <li><a href="/shopify-speed-optimization.php">Shopify Speed Optimization</a></li>
                                                <li><a href="/wix-editor-x-speed-optimization.php">WIX | Editor X Speed Optimization</a></li>
                                                <li><a href="/bigcommerce-speed-optimization.php">Bigcommerce Speed Optimization</a></li>
                                                <li><a href="/squarespace-speed-optimization.php">Squarespace Speed Optimization</a></li>
                                                <li><a href="/shift4shop-speed-optimization.php">Shift4Shop Speed Optimization</a></li>
                                                <li><a href="/clickfunnels-speed-optimization.php">Clickfunnels Speed Optimization</a></li>
                                                <li><a href="/webwave-speed-optimization.php">Webwave Speed Optimization</a></li>
                                                <li><a href="/weebly-speed-optimization.php">Weebly Speed Optimization</a></li>
                                                <li><a href="/webflow-speed-optimization.php">Webflow Speed Optimization</a></li>
                                                <li><a href="/hubspot-speed-optimization.php">HubSpot Speed Optimization</a></li>
                                                <li><a href="/oracle-commerce-performance-optimization.php">Oracle Speed Optimization</a></li>
                                                <li><a href="/magento-commerce-performance-optimization.php">Magento Speed Optimization</a></li>
                                                <li><a href="/sap-commerce-cloud-speed-optimization.php">SAP Speed Optimization</a></li>
                                                <li><a href="/intershop-speed-optimization.php">Intershop Speed Optimization</a></li>
                                                <li><a href="/cs-cart-speed-optimization.php">CS-Cart Speed Optimization</a></li>
                                                <li><a href="/weblium-speed-optimization.php">Weblium Speed Optimization</a></li>
                                                <li><a href="/linode-speed-optimization.php">Linode Optimization</a></li>
                                                <li><a href="/bigcommerce-enterprise-speed-optimization.php">BigCommerce Enterprise Speed Optimization</a></li>
                                            </ul>
                                            <ul>
                                                <li><a href="/jimdo-speed-optimization.php">Jimdo Speed Optimization</a></li>
                                                <li><a href="/bigcartel-speed-optimization.php">Big Cartel Speed Optimization</a></li>
                                                <li><a href="/webnode-speed-optimization.php">Webnode Speed Optimization</a></li>
                                                <li><a href="/tilda-speed-optimization.php">Tilda Speed Optimization</a></li>
                                                <li><a href="/duda-speed-optimization.php">Duda Speed Optimization</a></li>
                                                <li><a href="/ecwid-speed-optimization.php">Ecwid Speed Optimization</a></li>
                                                <li><a href="/prestashop-speed-optimization.php">Prestashop Speed Optimization</a></li>
                                                <li><a href="/systeme-speed-optimization.php">Systeme Speed Optimization</a></li>
                                                <li><a href="/saas-speed-optimization.php">SaaS Speed Optimization</a></li>
                                                <li><a href="/salesforce-performance-optimization.php">Salesforce Speed Optimization</a></li>
                                                <li><a href="/elastic-path-speed-optimization.php">Elastic Path Speed Optimization</a></li>
                                                <li><a href="/kibo-commerce-speed-optimization.php">Kibo Speed Optimization</a></li>
                                                <li><a href="/shopify-plus-speed-optimization.php">Shopify Plus Speed Optimization</a></li>
                                                <li><a href="/brizy-cloud-speed-optimization.php">Brizy Cloud Speed Optimization</a></li>
                                                <li><a href="/microweberi-speed-optimization.php">Microweber Speed Optimization</a></li>
                                                <li><a href="/kajabi-speed-optimization.php">Kajabi Speed Optimization</a></li>
                                                <li><a href="/website-performance-optimization.php">Website Speed Optimization</a></li>
                                                <li><a href="/signup.php">Custom Website Speed Optimization</a></li>
                                            </ul>
                                        </div>
                                    </div>

                                    <div class="menu__grid" >
                                        <div class="menu__title">Fix Core Web Vitals</div>
                                        <div class="menu-list-wrap">
                                            <ul>
                                                <li><a href="/fix-shopify-core-web-vitals.php">Shopify Core Web Vitals</a></li>
                                                <li><a href="/fix-wix-editor-x-core-web-vitals.php">Wix/Editor Core Web Vitals</a></li>
                                                <li><a href="/fix-bigcommerce-core-web-vitals.php">Bigcommerce Core Web Vitals</a></li>
                                                <li><a href="/fix-squarespace-core-web-vitals.php">Squarespace Core Web Vitals</a></li>
                                                <li><a href="/fix-shift4shop-core-web-vitals.php">Shift4Shop Core Web Vitals</a></li>
                                                <li><a href="/fix-clickfunnels-core-web-vitals.php">Clickfunnels Core Web Vitals</a></li>
                                                <li><a href="/fix-webwave-core-web-vitals.php">Webwave Core Web Vitals</a></li>
                                                <li><a href="/fix-weebly-core-web-vitals.php">Weebly Core Web Vitals</a></li>
                                                <li><a href="/fix-webflow-core-web-vitals.php">Webflow Core Web Vitals</a></li>
                                                <li><a href="/fix-hubspot-core-web-vitals.php">Hubspot Core Web Vitals</a></li>
                                                <li><a href="/fix-saas-website-core-web-vitals.php">Saas Core Web Vitals</a></li>
                                                <li><a href="/fix-intershop-website-core-web-vitals.php">Intershop Core Web Vitals</a></li>
                                                <li><a href="/fix-ibm-websphere-commerce-web-vitals.php">Websphere Core Web Vitals</a></li>
                                                <li><a href="/fix-cs-cart-core-web-vitals.php">CS-Cart Core Web Vitals</a></li>
                                                <li><a href="/fix-weblium-core-web-vitals.php">Weblium Core Web Vitals</a></li>
                                                <li><a href="/fix-linode-core-web-vitals.php">Linode Core Web Vitals</a></li>

                                            </ul>
                                            <ul>
                                                <li><a href="/fix-jimdo-core-web-vitals.php">Jimdo Core Web Vitals</a></li>
                                                <li><a href="/fix-bigcartel-core-web-vitals.php">Bigcartel Core Web Vitals</a></li>
                                                <li><a href="/fix-webnode-core-web-vitals.php">Webnode Core Web Vitals</a></li>
                                                <li><a href="/fix-tilda-core-web-vitals.php">Tilda Core Web Vitals</a></li>
                                                <li><a href="/fix-duda-core-web-vitals.php">Duda Core Web Vitals</a></li>
                                                <li><a href="/fix-ecwid-core-web-vitals.php">Ecwid Core Web Vitals</a></li>
                                                <li><a href="/fix-prestashop-core-web-vitals.php">Prestashop Core Web Vitals</a></li>
                                                <li><a href="/fix-systeme-core-web-vitals.php">Systeme Core Web Vitals</a></li>
                                                <li><a href="/fix-saleforce-core-web-vitals.php">Saleforce Core Web Vitals</a></li>
                                                <li><a href="/fix-oracle-commerce-core-web-vitals.php">Oracle Core Web Vitals</a></li>
                                                <li><a href="/fix-elastic-path-website-core-web-vitals.php">Elastic Core Web Vitals</a></li>
                                                <li><a href="/fix-kibo-commerce-core-web-vitals.php">Kibo Core Web Vitals</a></li>
                                                <li><a href="/fix-shopify-plus-website-core-web-vitals.php">Shopify-plus Web Vitals</a></li>
                                                <li><a href="/fix-brizy-core-web-vitals.php">Brizy Core Web Vitals</a></li>
                                                <li><a href="/fix-microweber-core-web-vitals.php">Microweber Core Web Vitals</a></li>
                                                <li><a href="/fix-kajabi-core-web-vitals.php">Kajabi Core Web Vitals</a></li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </li>

                        <li><a href="/website-speed-optimiation-cost.php" class="menu__link pricing_link_link">Pricing </a></li>
                        <li class="dropdown__link" id="dropdownMenuOne">
                            <span class="menu__link link__menu">Resources <img width="15" height="15" loading="lazy" src="<?=$bunny_image?>arrow-down.png" alt="arrow-down" class="menu__arrow"></span>
                            <div class="dropdown" > 
                            <div class="inner__wrapper__menu__grid">
                                <div class="menu__grid" >
                                    <div class="menu__title">Learn & Contact</div>
                                    <ul>
                                        <li><a href="/blog/">Blog</a></li>
                                        <li><a href="<?=HOST_HELP_URL?>faqs" target="_blank">FAQs</a></li>
                                        <li><a class="newPageLink" href="/about-us.php#gotoText">Investor</a></li>
                                        <li><a href="/case-study.php">Customer Success Stories</a></li>
                                    </ul>
                                </div>
                                <div class="menu__grid" >
                                    <div class="menu__title">Support & Guide</div>
                                    <ul>
                                        <li><a href="<?=HOST_HELP_URL?>" target="_blank">Knowledge base</a></li>
                                        <li><a href="<?=HOST_HELP_URL?>knowledge-base/website-speedy-admin-panel/platform-wise-instructions" target="_blank">Platform wise instuctor</a></li>
                                        <li><a href="<?=HOST_HELP_URL?>login" target="_blank">Open Support Ticket</a></li>
                                        <li><a class="newPageLink" href="/contact-us.php">Contact</a></li>
                                    </ul>
                                </div>
                            </div>
                            </div>
                        </li>
                        <li><a href="/why-website-speed-matters.php" class="menu__link">Why Speed Matters</a></li>
                        <li><a href="<?=HOST_HELP_URL?>" class="menu__link">Help Center</a></li>
                        <!-- <li><button class="menu__link faq_s" id="faq_s">Faq's</button></li> -->
                    </ul>
                </div>
                <div class="other__menu__wrapper" >
                    <!-- <a href="<?=HOST_HELP_URL?>" aria-label="help" target="_blank" class="help__img" ><img width="25" height="25" loading="lazy" src="<?=$bunny_image?>help.webp" alt="websitespeedy help"></a> -->
                    <a href="/adminpannel/" class="menu__link btn login">Sign in</a>
                    <div class="flex__col">
                        <!-- <a href="#slide-to-form" class="btn">Book a Demo</a> -->
                        <a href="/signup.php" class="btn">Get Started Free</a>
                        <!-- <span>No Credit Card Required</span> -->
                    </div>
                </div>
                <div class="login__btn__mobile">
                    <a href="#slide-to-form" class="btn">Book a Demo</a>
                    <a href="/signup.php" class="btn">Get Started Free</a>
                </div>
                <div id="togglerMenu" class="close__icon">
                    <span></span>    
                    <span></span>    
                    <span></span>    
                </div>
                
                <div id="mobileNav" class="mobil__nav">
                        <ul>
                            <li><a href="/website-speed-optimiation-cost.php" class="menu__link pricing_link_link">Pricing</a></li>
                            <li class="dropdown__menu__mobile" id="mobileDropDownOne">
                                <span class="menu__link link__menu">Platforms <img width="15" height="15" src="<?=$bunny_image?>arrow-down.png" alt="arrow-down" class="menu__arrow"></span>
                                <div class="mobile__dropdown">
                                    
                                    
                                    <div class="menu__grid" id="sub_menu_box" >
                                        <div class="menu__link one" id="sub_menu_link">Website Speed optimization <img width="15" height="15" src="<?=$bunny_image?>arrow-down.png" alt="arrow-down" class="menu__arrow"></div>
                                        <ul class="child_link_one sub_menu_list">
                                            <li><a href="/shopify-speed-optimization.php">Shopify Speed Optimization</a></li>
                                            <li><a href="/wix-editor-x-speed-optimization.php">WIX | Editor X Speed Optimization</a></li>
                                            <li><a href="/bigcommerce-speed-optimization.php">Bigcommerce Speed Optimization</a></li>
                                            <li><a href="/squarespace-speed-optimization.php">Squarespace Speed Optimization</a></li>
                                            <li><a href="/shift4shop-speed-optimization.php">Shift4Shop Speed Optimization</a></li>
                                            <li><a href="/clickfunnels-speed-optimization.php">Clickfunnels Speed Optimization</a></li>
                                            <li><a href="/webwave-speed-optimization.php">Webwave Speed Optimization</a></li>
                                            <li><a href="/weebly-speed-optimization.php">Weebly Speed Optimization</a></li>
                                            <li><a href="/webflow-speed-optimization.php">Webflow Speed Optimization</a></li>
                                            <li><a href="/hubspot-speed-optimization.php">HubSpot Speed Optimization</a></li>
                                            <li><a href="/jimdo-speed-optimization.php">Jimdo Speed Optimization</a></li>
                                            <li><a href="/bigcartel-speed-optimization.php">Big Cartel Speed Optimization</a></li>
                                            <li><a href="/webnode-speed-optimization.php">Webnode Speed Optimization</a></li>
                                            <li><a href="/tilda-speed-optimization.php">Tilda Speed Optimization</a></li>
                                            <li><a href="/duda-speed-optimization.php">Duda Speed Optimization</a></li>
                                            <li><a href="/ecwid-speed-optimization.php">Ecwid Speed Optimization</a></li>
                                            <li><a href="/signup.php">Custom Website Speed Optimization</a></li>
                                            <li><a href="/signup.php">Saas Speed Optimization</a></li>
                                        </ul>
                                    </div>

                                    <div class="menu__grid" id="sub_menu_box2" >
                                        <div class="menu__link two" id="sub_menu_link2">Fix Core web Vitals <img width="15" height="15" src="<?=$bunny_image?>arrow-down.png" alt="arrow-down" class="menu__arrow"></div>
                                        <ul class="child_link_two sub_menu_list">
                                            <li><a href="/fix-shopify-core-web-vitals.php">Shopify Core Web Vitals</a></li>
                                            <li><a href="/fix-wix-editor-x-core-web-vitals.php">Wix/Editor Core Web Vitals</a></li>
                                            <li><a href="/fix-bigcommerce-core-web-vitals.php">Bigcommerce Core Web Vitals</a></li>
                                            <li><a href="/fix-squarespace-core-web-vitals.php">Squarespace Core Web Vitals</a></li>
                                            <li><a href="/fix-shift4shop-core-web-vitals.php">Shift4Shop Core Web Vitals</a></li>
                                            <li><a href="/fix-clickfunnels-core-web-vitals.php">Clickfunnels Core Web Vitals</a></li>
                                            <li><a href="/fix-webwave-core-web-vitals.php">Webwave Core Web Vitals</a></li>
                                            <li><a href="/fix-weebly-core-web-vitals.php">Weebly Core Web Vitals</a></li>
                                            <li><a href="/fix-webflow-core-web-vitals.php">Webflow Core Web Vitals</a></li>
                                            <li><a href="/fix-hubspot-core-web-vitals.php">Hubspot Core Web Vitals</a></li>
                                            <li><a href="/fix-jimdo-core-web-vitals.php">Jimdo Core Web Vitals</a></li>
                                            <li><a href="/fix-bigcartel-core-web-vitals.php">Bigcartel Core Web Vitals</a></li>
                                            <li><a href="/fix-webnode-core-web-vitals.php">Webnode Core Web Vitals</a></li>
                                            <li><a href="/fix-tilda-core-web-vitals.php">Tilda Core Web Vitals</a></li>
                                            <li><a href="/fix-duda-core-web-vitals.php">Duda Core Web Vitals</a></li>
                                            <li><a href="/fix-ecwid-core-web-vitals.php">Ecwid Core Web Vitals</a></li>
                                            <li><a href="/fix-prestashop-core-web-vitals.php">Prestashop Core Web Vitals</a></li>
                                            <li><a href="/fix-systeme-core-web-vitals.php">Systeme Core Web Vitals</a></li>

                                        </ul>
                                    </div>
                                </div>
                            </li>
                            <li class="dropdown__menu__mobile " id="mobileDropDownTwo">
                                <span class="menu__link link__menu">Resources <img width="15" height="15" src="<?=$bunny_image?>arrow-down.png" alt="arrow-down" class="menu__arrow"></span>
                                <div class="mobile__dropdown">
                                    <div class="menu__grid" >
                                        <ul>
                                            <li><a href="/blogs.php">Blog</a></li>
                                            <li><a href="/case-study.php">Customer Success Stories</a></li>
                                            <li><a href="<?=HOST_HELP_URL?>faqs" target="_blank">FAQs</a></li>
                                            <li><a class="newPageLink" href="/about-us.php#gotoText">Investor</a></li>
                                            <li><a href="<?=HOST_HELP_URL?>" target="_blank">Knowledge base</a></li>
                                            <li><a href="<?=HOST_HELP_URL?>knowledge-base/website-speedy-admin-panel/platform-wise-instructions" target="_blank">Platform wise instuctor</a></li>
                                            <li><a href="<?=HOST_HELP_URL?>login" target="_blank">Open support ticket</a></li>
                                            <li><a class="newPageLink" href="/contact-us.php">Contact</a></li>
                                        </ul>
                                    </div>
                                </div>
                            </li>
                            <li><a href="/why-website-speed-matters.php" class="menu__link">Why Speed Matters</a></li>
                            <li><a href="<?=HOST_HELP_URL?>" class="menu__link">Help Center</a></li>
                            <!-- <li><button class="menu__link faq_s" id="faq_sm">Faq's</button></li> -->

                            <li class="admin__btns" >
                                <a href="/adminpannel/" class="btn">Login</a>
                                <a class="btn" href="/signup.php" >Optimize My Website</a>
                            </li>

                        </ul>  
                    </div>
            </header> 
        </div>
    </div>


<script>
$('.faq_s').click(function() {
    $('#mobileNav').removeClass('active');
    $('#togglerMenu').removeClass('active');
    if ($('.faq__review').length > 0) {
        $('html, body').animate({
            scrollTop: $('.faq__review').offset().top - 87
        }, 1000);
    } 
    else if ($('.faq__speed__optimization__page').length > 0) {
        $('html, body').animate({
            scrollTop: $('.faq__speed__optimization__page').offset().top - 87
        }, 1000);
    }
    else if ($('.faq__cvw__page').length > 0) {
        $('html, body').animate({
            scrollTop: $('.faq__cvw__page').offset().top - 87
        }, 1000);
    }
    else {
    window.open("<?=HOST_HELP_URL?>faqs", "_blank");
}
});


</script>

 
