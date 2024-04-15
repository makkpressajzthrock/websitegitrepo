<?php 
require_once("/var/www/html/adminpannel/env.php") ;

// echo "sssss";
// include('../config.php');

 $pageurl= $_SERVER['PHP_SELF'];
// $domain_url = "https://".$_SERVER['HTTP_HOST']."$pageurl";
// echo "<pre>";
// print_r($_POST);die;

 $file1 = basename($pageurl);

$sele="SELECT id , title , keyword , descripton , pageurl FROM add_meta WHERE pageurl='$file1'";
$sele_run= mysqli_query($conn, $sele);

if ($result=mysqli_fetch_array($sele_run)) {

	$title = $result['title'];
	$keyword = $result['keyword'];
	$description = $result['descripton'];

} 
 

?>


<html lang="en">
	<head>

		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0" >
		<meta name="keywords" content="<?php echo $keyword; ?>">
		<meta name="description" content="<?php echo $description; ?>" />

		<meta name="author" content="" />
		<meta name="google_pagespeed_api" content="AIzaSyDw2nckjNQeVLGw_BxcfIvLTw3NYONCuRE" />



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


<!-- Google Tag Manager -->
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','GTM-MK5VN7M');</script>
<!-- End Google Tag Manager -->		

        <script src="https://www.dwin1.com/58969.js" type="text/javascript" defer="defer"></script>

		<title><?php echo $title; ?></title>

			<!-- Favicon-->
			<link rel="icon" type="image/x-icon" href="//websitespeedycdn.b-cdn.net/speedyweb/images/favicon.ico" > 
<!-- styles -->


<link rel="stylesheet" href="css/bootstrap.min.css">
<link rel="stylesheet" href="css/font-awesome.css">
<link rel="stylesheet" type="text/css" href="css/jquery.dataTables.min.css">
<link rel="stylesheet" href="css/all.min.css">
<link rel="stylesheet" href="css/select2.min.css"  />
<link rel="stylesheet" type="text/css" href="https://shopifyspeedy.com/adminpannel/style1.css">
<link href="css/styles.css?v=<?=rand(0,99);?>" rel="stylesheet" />
<link href="css/custom.css?v=<?=rand(0,99);?>" rel="stylesheet" />
<link href="css/line-awesome.min.css?v=<?=rand(0,99);?>" rel="stylesheet" />

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<!-- scripts -->
<script src="js/jquery-3.5.1.js" ></script>
<script src="js/bootstrap.min.js" ></script>
<script src="js/bootstrap.bundle.min.js" ></script>
<script src="js/typed.js" ></script>
<script type="text/javascript" src="js/jquery.dataTables.min.js" ></script>
<script src="js/jquery.easypiechart.min.js" ></script>
<script src="js/all.min.js" ></script>
<script src="js/chart.js"  ></script>
<script src="js/select2.min.js"  ></script>
<script src="js/sweetalert2@11.js"  ></script>
<script src="js/dotlottie-player.js" ></script> 
<script src="js/scripts.js?v=<?=rand(0,99);?>" ></script>

<script>
	function loaderest() {
    var typed = new Typed('.auto-type', {   
    strings: ['Webpages should take only 1 or 2 seconds to load to reduce bounce rate ot 9% - Research by Pingdom', '1 in 3 consumers say they’ll leave a brand they love after just one bad experience - Research by PWC', '500 milliseconds of extra loading results in a traffic drop of 20% - Research by Google', '500 milliseconds of extra loading results in a traffic drop of 20% - Research by Google', 'Every extra 100 milliseconds of loading decreases sales by 1%  - Research by Amazon', 'With a 0.1s improvement in site speed, retail consumers spent almost 10% more - Research by Deloitte', 'An ecommerce site that loads within 1 converts 2.5x than a site that loads in 5 seconds - Research by Portent', '36.8% of shoppers are less likely to return if page loads slowly - Research by Google', '53% of mobile visitors will leave a page if it takes more than 3 seconds to load - Research by EY'],
    typeSpeed: 20,
    backSpeed: 20,
    backDelay: 3000,
    loop: true,
  });
}
</script>

