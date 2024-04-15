<?php

require_once('config.php');
require_once('inc/functions.php') ;

// Include autoloader 
require_once 'dompdf/autoload.inc.php'; 
 
// Reference the Dompdf namespace 
use Dompdf\Dompdf; 
ob_clean();


/*************************************************************/ 
// boost_website
$user_id = $_SESSION['user_id'] ;
$project_id = $_GET['id'] ;
//$project_id = 2 ;


$project_data = getTableData( $conn , " boost_website " , " id = '$project_id' AND manager_id = '$user_id'  " ) ;

// print_r($project_data) ;

$content = '' ;
switch ($project_data["platform"]) {
	case 'Shopify':
		$content .= '<div><h3>Installation Process For Shopify</h3></div>' ;
		$content .= file_get_contents("script-installation/shopify-inst.php") ;
		break;

	case 'Bigcommerce':
		$content .= '<div><h3>Installation Process For Bigcommerce</h3></div>' ;
		$content .= file_get_contents("script-installation/bigcommerce-inst.php") ;
		break;

	case 'Wordpress':
		$content .= '<div><h3>Installation Process For Wordpress</h3></div>' ;
		$content .= file_get_contents("script-installation/wordpress-inst.php") ;
		break;

	case 'Shift4Shop':
		$content .= '<div><h3>Installation Process For Shift4Shop</h3></div>' ;
		$content .= file_get_contents("script-installation/shift4shop-inst.php") ;
		break;

	case 'Wix':
		$content .= '<div><h3>Installation Process For Wix</h3></div>' ;
		$content .= file_get_contents("script-installation/wix-inst.php") ;
		break;

	case 'Magento':
		$content .= '<div><h3>Installation Process For Magento</h3></div>' ;
		$content .= file_get_contents("script-installation/magento-inst.php") ;
		break;

	case 'Opencart':
		$content .= '<div><h3>Installation Process For Opencart</h3></div>' ;
		$content .= file_get_contents("script-installation/opencart-inst.php") ;
		break;

	case 'Prestashop':
		$content .= '<div><h3>Installation Process For Prestashop</h3></div>' ;
		$content .= file_get_contents("script-installation/prestashop-inst.php") ;
		break;
	
	default:
		$content .= '<div><h3>Installation Process</h3></div>' ;
		$content .= file_get_contents("script-installation/others-inst.php") ;
		break;
}

// print_r($content) ; die("<hr>") ;

$website_name = empty($project_data["website_name"]) ? parse_url($project_data["website_url"])["host"] : $project_data["website_name"] ;

$pdf_name = $website_name.'-'.$project_data["platform"]."-script-installation-instructions.pdf" ;

// Instantiate and use the dompdf class 
$dompdf = new Dompdf();
$dompdf->loadHtml($content); 
$dompdf->setPaper('A4', 'portrait'); 
$dompdf->render(); 
// $dompdf->stream($pdf_name, array("Attachment" => 0)); 
$output = $dompdf->output();
file_put_contents('installation-documents/'.$pdf_name, $output);

?>




