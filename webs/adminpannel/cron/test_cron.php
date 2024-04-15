<?php
require_once('../config.php');

$run_at = date('Y-m-d H:i:s') ;	

$sql = " INSERT INTO `test_cron`(`run_at`) VALUES ('$run_at') ; ";

if ($conn->query($sql) === TRUE) {
  echo "New record created successfully";
}
else {
  echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();