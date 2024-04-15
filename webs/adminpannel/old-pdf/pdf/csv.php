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
 
    $Datacs = array(
         'First Name',
         'Last Name',
         'Subgroup',
         'Catalog Version',
         'Catalog Item #',
         'Product Name',
         'Price',
         'Quantity',
         'Amount',
         'Subtotal per Seller',
         'Profit per Seller',
    );
array_push($dataAr,$Datacs);    


$ii=0;
$DatacsAc = [];
$jj=0;
$gt = 0;
foreach ($DatacsvO as $orders) { 


 $queryCsv = "SELECT faqs.id as fname,faqs.id as lname,faqs.id as group_nm,faqs.question,faqs.answer,faqs.description,faqs.price,campaign_orders_items.qty FROM `faqs`,campaign_orders_items WHERE faqs.id = campaign_orders_items.product_id and campaign_orders_items.campaign_id = '".$orders['id']."' order by faqs.id";

	$statementcsv = $connect->prepare($queryCsv);
	$statementcsv->execute();
	$Datacsv = $statementcsv->fetchAll(\PDO::FETCH_ASSOC);

if( count($Datacsv) > 0 ){ 
if($ii!=0 ){
     $Datacs = array();
array_push($dataAr,$Datacs);    
$Datacs = array();
array_push($dataAr,$Datacs);
    $Datacs = array(
         '',
         '',
         '',
         'Catalog Version',
         'Catalog Item #',
         'Product Name',
         'Price',
         'Quantity',
         'Amount',
    );
array_push($dataAr,$Datacs);    

   
}

$ii++;
$jj=0;

    $DatacsAc = array(
         $orders['first_name'],
         $orders['last_name'],
         $orders['subgroup']
    );
 


$gt = 0;

// print_r($Datacsv);
foreach ($Datacsv as $Datacs) { 
     $Subtotal_amt += $Datacs['price']*$Datacs['qty'] ;
     $grand_total  += $Datacs['price']*$Datacs['qty'];
     $Subtotal_qty += $Datacs['qty'];
     $gt += $Datacs['price']*$Datacs['qty'];
if($jj==0){
$Datacs['fname'] =  $DatacsAc[0];
$Datacs['lname'] =  $DatacsAc[1];
$Datacs['group_nm'] =  $DatacsAc[2];
}
else{
$Datacs['fname'] =  $DatacsAc[0];
$Datacs['lname'] =  $DatacsAc[1];
$Datacs['group_nm'] =  $DatacsAc[2];
}

$jj++;
$Datacs['amount'] = '$'.$Datacs['price']*$Datacs['qty'];                                             
$Datacs['price'] = '$'.$Datacs['price']; 
array_push($dataAr,$Datacs);
}

  if($gt != 0){
    $Datacs = array(
         '',
         '',
         '',
         '',
         '',
         '',
         '',
         '',
         '',
         '$'.$gt,
         '$'.$gt/100*$discount
    );
array_push($dataAr,$Datacs);
}

}
}
   

    $fp = fopen('orders/order_F'.$order_Data[0]['id'].'.csv', 'w');




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
 
     $data = ',,,,,,Group Subtotal,'.$Subtotal_qty.',$'.$Subtotal_amt.',,$'.($Subtotal_amt/100*$discount);
   $val = explode(",",$data);
    fputcsv($fp, $val);

    fclose($fp);

?>