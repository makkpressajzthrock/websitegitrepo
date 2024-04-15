<?php

require_once('config.php');

$query = $conn->query(" SELECT id , categories , mobile_categories , desktop_score , mobile_score , flag FROM `pagespeed_report` 
WHERE flag = 0 
ORDER BY `pagespeed_report`.`id` ASC LIMIT 250 ; ") ;

while ( $row = $query->fetch_assoc() ) {
	
	$categories = unserialize($row["categories"]) ;
	$categories = is_array($categories) && !empty($categories["performance"]["score"]) ? ($categories["performance"]["score"]*100) : 0 ; 

	$mobile_categories = unserialize($row["mobile_categories"]) ;
	$mobile_categories = is_array($mobile_categories) && !empty($mobile_categories["performance"]["score"]) ? ($mobile_categories["performance"]["score"]*100) : 0 ; 


	echo "categories : ".$categories."<br>" ;
	echo "mobile_categories : ".$mobile_categories."<hr>" ;

	$conn->query(" UPDATE pagespeed_report SET desktop_score = '$categories' , mobile_score = '$mobile_categories' , flag = 1 WHERE id = '".$row["id"]."' ; ");

	// break ;
}