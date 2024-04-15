<?php


/**
* method to get data from data in single/multiple rows
**/
function getTableData( $conn , $table , $where = '' , $clouse = '' , $fetch_type = 0 , $column = "*" ) {

	$data = [] ;

	$sql = " SELECT $column FROM $table " ;

	if ( !empty($where) ) {
		$sql .= " WHERE $where " ;
	}
	$sql .= " $clouse " ;

	 $sql ;
	// echo $sql; die;
	$query = $conn->query($sql) ;
	// echo "Last executed query: " . $sql; 

	if ( $query->num_rows > 0 ) {
		if ( $fetch_type == 0 ) {
			// single array
			$data = $query->fetch_assoc() ;
		}
		else {
			// echo "Last executed query: " . $row['last_query']; die;
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

