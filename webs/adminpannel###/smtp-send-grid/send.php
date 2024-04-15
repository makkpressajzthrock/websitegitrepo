<?php
require 'vendor/autoload.php'; // If you're using Composer (recommended)
// Comment out the above line if not using Composer
// require("<PATH TO>/sendgrid-php.php");
// If not using Composer, uncomment the above line and
// download sendgrid-php.zip from the latest release here,
// replacing <PATH TO> with the path to the sendgrid-php.php file,
// which is included in the download:
// https://github.com/sendgrid/sendgrid-php/releases

echo getenv('SENDGRID_API_KEY');
echo '<pre>';

$email = new \SendGrid\Mail\Mail(); 
$email->setFrom("support@websitespeedy.com", "Website Speddy");
$email->setSubject("Sending with SendGrid is Fun");
$email->addTo("rohan@makkpress.com", "Example User");
$email->addContent("text/plain", " Hello Smtp testing.");
$email->addContent(
    "text/html", "<strong>and easy to do anywhere, even with PHP</strong>"
);
$sendgrid = new \SendGrid('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');
try {
    $response = $sendgrid->send($email);
    print $response->statusCode() . "\n";
    print_r($response->headers());
    print $response->body() . "\n";
} catch (Exception $e) {
    echo 'Caught exception: '. $e->getMessage() ."\n";
}