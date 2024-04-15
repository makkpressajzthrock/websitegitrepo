<?php
// error_reporting(E_ALL);
// ini_set('display_errors', 1);
require_once('vendor/autoload.php');
include('bunnycdn-storage.php');

//Uploading objects

$bunnyCDNStorage = new BunnyCDNStorage("websitespeedy", "19284fdd-f85d-4bfd-b5d21dafe1ed-6a88-4c2c", "");

	$url_F = "/var/www/html/script/ecmrx/";

// ===============  Purge Cache ====================
$client = new \GuzzleHttp\Client();

// ===============  Purge Cache End ====================


// $deletedata1 = $bunnyCDNStorage->getStorageObjects("/websitespeedy/speedyscripts/ecmrx_637/ecmrx_637_1.js");
// print_r($deletedata1);

if($count_site_num > 0)
{
  $deletedata1 = $bunnyCDNStorage->deleteObject("/websitespeedy/speedyscripts/$encFn/$first.js");
  $deletedata2 = $bunnyCDNStorage->deleteObject("/websitespeedy/speedyscripts/$encFn/$second.js");
  $deletedata3 = $bunnyCDNStorage->deleteObject("/websitespeedy/speedyscripts/$encFn/$third.js");
  sleep(1);
}

// sleep(1);
$returndata1 = $bunnyCDNStorage->uploadFile($url_F."$encFn/$first.js", "/websitespeedy/speedyscripts/$encFn/$first.js");
$returndata2 = $bunnyCDNStorage->uploadFile($url_F."$encFn/$second.js", "/websitespeedy/speedyscripts/$encFn/$second.js");
$returndata3 = $bunnyCDNStorage->uploadFile($url_F."$encFn/$third.js", "/websitespeedy/speedyscripts/$encFn/$third.js");
$returndata4 = $bunnyCDNStorage->uploadFile($url_F."$encFn/$fourth.txt", "/websitespeedy/speedyscripts/$encFn/$fourth.txt");


sleep(1);


$response1 = $client->request('POST', "https://api.bunny.net/purge?url=https://websitespeedycdn.b-cdn.net/speedyscripts/$encFn/$first.js&async=false", [
  'headers' => [
    'AccessKey' => 'af7051be-9560-4951-8043-1015c55bc095e3dde32b-4cf9-4688-8b75-f973271c7476',
  ],
]);


// print_r($response1);

$response2 = $client->request('POST', "https://api.bunny.net/purge?url=https://websitespeedycdn.b-cdn.net/speedyscripts/$encFn/$second.js&async=false", [
  'headers' => [
    'AccessKey' => 'af7051be-9560-4951-8043-1015c55bc095e3dde32b-4cf9-4688-8b75-f973271c7476',
  ],
]);

// print_r($response2);

$response3 = $client->request('POST', "https://api.bunny.net/purge?url=https://websitespeedycdn.b-cdn.net/speedyscripts/$encFn/$third.js&async=false", [
  'headers' => [
    'AccessKey' => 'af7051be-9560-4951-8043-1015c55bc095e3dde32b-4cf9-4688-8b75-f973271c7476',
  ],
]);




// sleep(1);

//       $url_F = "/var/www/html/script/ecmrx/$encFn/$first.js";
//       unlink($url_F);

//       $url_F = "/var/www/html/script/ecmrx/$encFn/$second.js";
//       unlink($url_F);

//       $url_F = "/var/www/html/script/ecmrx/$encFn/$third.js";
//       unlink($url_F);

//       $url_F = "/var/www/html/script/ecmrx/$encFn/$fourth.txt";
//       unlink($url_F);

//       $url_F = "/var/www/html/script/ecmrx/$encFn";
//       rmdir($url_F);

// print_r($response3);



// get_dataa("https://websitespeedycdn.b-cdn.net/speedyscripts/$encFn/$first.js");
// // get_dataa("https://websitespeedycdn.b-cdn.net/speedyscripts/$encFn/$second.js");
// // get_dataa("https://websitespeedycdn.b-cdn.net/speedyscripts/$encFn/$third.js");

// function get_dataa($url) {
//   echo $url;
//   $ch = curl_init();
//   $timeout = 5;
//   curl_setopt($ch, CURLOPT_URL, $url);
//   curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 6.0)");
//   curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
//   curl_setopt($ch, CURLOPT_SSL_VERIFYHOST,false);
//   curl_setopt($ch, CURLOPT_SSL_VERIFYPEER,false);
//   curl_setopt($ch, CURLOPT_MAXREDIRS, 10);
//   curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
//   curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
//   $data = curl_exec($ch);
//   curl_close($ch);
//   print_r($data);
// }