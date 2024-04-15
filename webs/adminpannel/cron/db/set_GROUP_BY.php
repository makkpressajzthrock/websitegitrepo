<?php
require_once('../../config.php');

$r = $conn->query("SET GLOBAL sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''))");

echo $r;



?>