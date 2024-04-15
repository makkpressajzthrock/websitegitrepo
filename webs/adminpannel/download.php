<?php
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=data.csv');


$filename = 'data.csv';
$export_data = unserialize($_POST['export_data']);

// file creation
    $file = fopen('php://output', 'w');  


fputcsv($file, ["Id","Email", "How do you dress for work?", "How about on the weekend?","What size tops do you wear?","How do you like your shirts to fit?","What is your pants waist size?","What is your pants inseam measurement?","How do you like your pants to fit?","What is your shoe size?"]);


foreach ($export_data as $line){
  fputcsv($file,$line);
}

fclose($file); 


readfile($filename);

// deleting file
unlink($filename);
exit();

