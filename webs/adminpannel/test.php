<?php
    
    $curl_url = "https://logging.bunnycdn.com/01-09-24/1394947.log" ;

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $curl_url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 0);
    curl_setopt($ch, CURLOPT_FAILONERROR, 0);

    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        "AccessKey: af7051be-9560-4951-8043-1015c55bc095e3dde32b-4cf9-4688-8b75-f973271c7476",
    ));

    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
    $output = curl_exec($ch);
    // print_r(var_dump($output)) ; echo "<hr>";
    $curlError = curl_errno($ch);
    $responseCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    print_r(explode('|', $output));