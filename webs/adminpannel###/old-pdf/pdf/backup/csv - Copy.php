<?php

    $discount = ($order_Data[0]['discount']);

$grand_total = 0;
     $Subtotal_qty = 0;
     $Subtotal_amt = 0;
 $queryCsvO = "SELECT * FROM campaign_orders where campaign_id='".$order_Data[0]['id']."'";

 // $queryCsvO = "SELECT * FROM campaign_orders where campaign_id='29'";
	$statementcsvO = $connect->prepare($queryCsvO);
	$statementcsvO->execute();
	$DatacsvO = $statementcsvO->fetchAll();

// echo '<pre>';
 $dataAr = array ();

foreach ($DatacsvO as $orders) { 

    $Datacs = array();
array_push($dataAr,$Datacs);    
$Datacs = array();
array_push($dataAr,$Datacs);


$Datacs = array();
array_push($dataAr,$Datacs);
    $Datacs = array(
         'First Name',
         'Last Name',
         'Sub Group',
    );
array_push($dataAr,$Datacs);    


    $Datacs = array(
         $orders['first_name'],
         $orders['last_name'],
         $orders['subgroup']
    );
array_push($dataAr,$Datacs);
$Datacs = array();
array_push($dataAr,$Datacs);
    $Datacs = array(
         'Catalog Version',
         'Catalog Item #',
         'Price',
         'Quantity',
         'Amount',
         'Profit'
    );
array_push($dataAr,$Datacs);


 $queryCsv = "SELECT faqs.question,faqs.answer,faqs.price,campaign_orders_items.qty FROM `faqs`,campaign_orders_items WHERE faqs.id = campaign_orders_items.product_id and campaign_orders_items.campaign_id = '".$orders['id']."' order by faqs.id";

	$statementcsv = $connect->prepare($queryCsv);
	$statementcsv->execute();
	$Datacsv = $statementcsv->fetchAll(\PDO::FETCH_ASSOC);


$gt = 0;

// print_r($Datacsv);
foreach ($Datacsv as $Datacs) { 
     $Subtotal_amt += $Datacs['price']*$Datacs['qty'] ;
     $grand_total  += $Datacs['price']*$Datacs['qty'];
     $Subtotal_qty += $Datacs['qty'];
     $gt += $Datacs['price']*$Datacs['qty'];

$Datacs['amount'] = '$'.$Datacs['price']*$Datacs['qty'];                                             
$Datacs['price'] = '$'.$Datacs['price']; 
array_push($dataAr,$Datacs);
}
$Datacs = array();
array_push($dataAr,$Datacs);
    $Datacs = array(
         '',
         '',
         'Sub Total',
         '',
         '$'.$gt,
         '$'.$gt/100*$discount
    );
array_push($dataAr,$Datacs);


}
   
//  print_r($dataAr);

// die;
    $fp = fopen('orders/order_'.$order_Data[0]['id'].'.csv', 'w');




    foreach($dataAr as $line){
    	 	// print_r($line);
    	$aa = implode(',', $line);
    	// print_r($aa);
             $val = explode(",",$aa);
             fputcsv($fp, $val);
    		// echo '<br>';

    }
    $data = ',,,,';
    $val = explode(",",$data);
    fputcsv($fp, $val);

    $data = ',,,,';
    $val = explode(",",$data);
    fputcsv($fp, $val);
    $data = ',,Grand Total,'.$Subtotal_qty.',$'.$Subtotal_amt.',$'.($Subtotal_amt/100*$discount);
    $val = explode(",",$data);
    fputcsv($fp, $val);

    fclose($fp);

?>