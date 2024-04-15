<?php

/**
* method to check user is logged in or not
**/
function checkUserLogin() {

	if ( !empty($_SESSION["user_id"]) && !empty($_SESSION["role"]) ) {
		return true ;
	}
	else {
		return false ;
	}
}

/**
* method to get data from data in single/multiple rows
**/
function getTableData( $conn , $table , $where = '' , $clouse = '' , $fetch_type = 0 , $column = "*" ) {

	$data = [] ;

	$sql = " SELECT * FROM $table " ;

	if ( !empty($where) ) {
		$sql .= " WHERE $where " ;
	}
	$sql .= " $clouse " ;

	 $sql ;
	
	$query = $conn->query($sql) ;

	if ( $query->num_rows > 0 ) {
		if ( $fetch_type == 0 ) {
			// single array
			$data = $query->fetch_assoc() ;
		}
		else {
			$data = $query->fetch_all(MYSQLI_ASSOC) ;
		}
	}

	return $data ;
}

/**
* method to update row/multiple rows
**/
function updateTableData( $conn , $table , $columns , $where = '1' ) {

	$sql = " UPDATE $table SET $columns WHERE $where " ;
	// echo $sql ;
	// die();
	if ( $conn->query($sql) === TRUE ) {
		return true ;
	}
	else {
		return false ;
	}
}

/**
* method to update row/multiple rows
**/
function insertTableData( $conn , $table , $columns , $values ) {

	$sql = " INSERT INTO $table ( $columns )  VALUES ( $values ) " ;
// echo $sql ;
// die();
	if ( $conn->query($sql) === TRUE ) {
		return $conn->insert_id ;
	}
	else {
		return false ;
	}
}


function activePage($page) {
	$urlFile = basename($_SERVER["SCRIPT_NAME"]) ;
	// echo "urlFile : ".$urlFile ;
	// if ( $page == $urlFile ) {
	if ( in_array($urlFile, $page)  ) {
		return "active" ;
	}
}


/*
*	google page speed api
**/ 
function google_page_speed_insight($domain="",$strategy="desktop")
{

    $key = PAGE_INSIDE_KEY ;
    if($domain=="" || $key=="") exit();

    $url="https://www.googleapis.com/pagespeedonline/v5/runPagespeed?key=".$key."&url=".$domain."&strategy=".$strategy."&category=ACCESSIBILITY&category=BEST_PRACTICES&category=PERFORMANCE&category=PWA&category=SEO";

    // echo "<br>".$url."<br>" ;

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible;)");
    curl_setopt($ch, CURLOPT_URL, $url); // set url to post to
    // curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 0);
    // curl_setopt($ch, CURLOPT_FAILONERROR, 1);
    // curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);// allow redirects
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // return into a variable
    curl_setopt($ch, CURLOPT_TIMEOUT, 1200); // times out after 50s
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

    $content = curl_exec($ch); // run the whole process

    if (curl_errno($ch)) {
	    $content = curl_error($ch);
	}
	else {
		$content= json_decode($content,TRUE);
	}

    curl_close($ch);

    return $content;

	$lighthouseResult = $content["lighthouseResult"]["runWarnings"] ;

	if($content["lighthouseResult"]["requestedUrl"] != $content["lighthouseResult"]["finalUrl"] ){
		 google_page_speed_insight($content["lighthouseResult"]["finalUrl"],$strategy);

	}
	else{ 
	    return $content;
	}


}

/*
* Method to check reistration process is completed or not
*/ 
function checkSignupComplete($conn) {

	$query = $conn->query( " SELECT * FROM admin_users WHERE id ='".$_SESSION['user_id']."' " ) ;
	if ($query->num_rows > 0) 
	{
	
		$user_data = $query->fetch_assoc() ;
		// print_r($user_data) ;

		if ( $user_data["userstatus"] == "manager" ) 
		{
			$user_id = $user_data["id"] ;

			// check Let's customize your flow complete or not
			$query1 = $conn->query( " SELECT * FROM manager_company WHERE user_id ='".$user_id."' " ) ;
			if ( $query1->num_rows <= 0 ) {
				header("location: ".HOST_URL."customize-flow.php") ;
				die();
			}

			// check Any active plan or not
			$query2 = $conn->query( " SELECT * FROM user_subscription WHERE user_id ='".$user_id."' AND status LIKE 'active' " ) ;
			if ( $query2->num_rows <= 0 ) {
				header("location: ".HOST_URL."plan.php") ;
				die();
			}

		}
	}
	else {
		header("location: ".HOST_URL."signup.php") ;
		die() ;
	}
}

/*
* escape string method
**/ 
function escape_string($con,$postdata){

	foreach ($postdata as $key => $value) 
	{
		if ( is_array($value) ) {
			$postdata[$key] = escape_string($con,$value) ;
		}
		else {
			$postdata[$key] = $con->real_escape_string($value) ;
		}
	}

	return $postdata ;
}

/* encrypt/decrypt number to number */ 
function encrypt_number($string) {

  $integer = '';
  foreach (str_split($string) as $char) {
      $integer .= sprintf("%03s", ord($char));
  }
  
  return $integer;
}

function decrypt_number($integer) {
    $string = '';
    foreach (str_split($integer, 3) as $number) {
    	$string .= chr($number);
    }
	return $string ;
}


/*
* Get Email template content by its title.
**/
function getEmailContent($conn , $type='') {

    $return = array('subject' => '' , 'body' => '' );

    if ( !empty($type) ) 
    {
        $query = $conn->query( " SELECT * FROM email_template WHERE title LIKE '".$type."' ; " ) ;
        // print_r($query) ;

        if ( $query->num_rows > 0 ) {
            $data = $query->fetch_assoc() ;
            // print_r($data) ;
            $return["subject"] = $data["subject"] ;
            $return["body"] = $data["description"] ;
        }
    }
    // print_r($return) ;
    return $return ;
}

/*
* Get SMTP details
**/
function getSMTPDetail($conn) {

    $return = [] ;
    $query = $conn->query( " SELECT * FROM smtp_login ; " ) ;
    // print_r($query) ;

    if ( $query->num_rows > 0 ) {
        $data = $query->fetch_assoc() ;
        $return = $data ;
    }
    return $return ;
}



// $user_subscriptions_free = getTableData( $conn , " user_subscriptions_free " , " status = 1", "" ,"" ) ;
// print_r($user_subscriptions_free);