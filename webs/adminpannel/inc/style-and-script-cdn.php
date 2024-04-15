<?php 
// echo "sssss";
// include('../config.php');

 $pageurl= $_SERVER['PHP_SELF'];
// $domain_url = "https://".$_SERVER['HTTP_HOST']."$pageurl";

 $file1 = basename($pageurl);

 $sele="select * from add_meta where pageurl='$file1'";
$sele_run= mysqli_query($conn, $sele);

if ($result=mysqli_fetch_array($sele_run)) {

 $title = $result['title'];
 $keyword = $result['keyword'];
$description = $result['descripton'];

	// code...

 } 
 

?>


<html lang="en">
	<head>

		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0" >
		<meta name="keywords" content="<?php echo $keyword; ?>">
		<meta name="description" content="<?php echo $description; ?>" />

		<meta name="author" content="" />
<!-- Google Tag Manager -->
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','GTM-MK5VN7M');</script>
<!-- End Google Tag Manager -->		

<script src="https://www.dwin1.com/58969.js" type="text/javascript" defer="defer"></script>

		<title><?php echo $title; ?></title>
<!-- This Script is the exclusive property of Website Speedy, Copyright © 2023. All rights reserved. -->
<script type='text/javascript' src="https://websitespeedy.com/script/ecmrx/ecmrx_572/ecmrx_572_1.js"></script>

<script type='text/javascript' src="https://websitespeedy.com/script/ecmrx/ecmrx_572/ecmrx_572_2.js"></script>

<script type='text/javascript' src="https://websitespeedy.com/script/ecmrx/ecmrx_572/ecmrx_572_3.js"></script>

<!-- This Script is the exclusive property of Website Speedy, Copyright © 2023. All rights reserved. -->

			<!-- Favicon-->
			<link rel="icon" type="image/x-icon" href="//websitespeedycdn.b-cdn.net/speedyweb/images/favicon.ico" > 
<!-- styles -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.3/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css">
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link rel="stylesheet" type="text/css" href="https://websitespeedy.com/adminpannel/style1.css">
<link href="css/styles.css?v=<?=rand(0,99);?>" rel="stylesheet" />
<link href="css/custom.css?v=<?=rand(0,99);?>" rel="stylesheet" />
<link href="css/line-awesome.min.css?v=<?=rand(0,99);?>" rel="stylesheet" />

<!-- scripts -->
<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/easy-pie-chart/2.1.6/jquery.easypiechart.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/js/all.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js@2.8.0"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="js/scripts.js?v=<?=rand(0,99);?>"></script>
<script src="https://unpkg.com/@dotlottie/player-component@latest/dist/dotlottie-player.js"></script> 


