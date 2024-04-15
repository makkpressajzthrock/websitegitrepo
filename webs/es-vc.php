<?php
  
// Store a string into the variable which
// need to be Encrypted


$simple_string = <<< 'HTML'
let _0xcb0c79 = "aWYod2luZG93WydceDZFXHg2MVx4NzZceDY5XHg2N1x4NjFceDc0XHg2Rlx4NzInXVsnXHg3NVx4NzNceDY1XHg3Mlx4NDFceDY3XHg2NVx4NkVceDc0J10uaW5kZXhPZignXHg0M1x4NjhceDcyXHg2Rlx4NkRceDY1XHgyRFx4NENceDY5XHg2N1x4NjhceDc0XHg2OFx4NkZceDc1XHg3M1x4NjUnKSA+IC0xICYmIHdpbmRvdy5pbm5lcldpZHRoIDw9IDYwMCkgeyAKICAgIHZhciBpbWdUYWdzID0gZG9jdW1lbnRbInF1ZXJ5U2VsZWN0b3JBbGwiXSgiaW1nIik7CiAgICAgICAgICAgICAgIEFycmF5WyJwcm90b3R5cGUiXVsiZm9yRWFjaCJdWyJjYWxsIl0oaW1nVGFnc yxmdW5jdGlvbihpdGVtKSB7CiAgICBpdGVtLnJlbW92ZSgpOwp9KTsKICAgIHZhciBpZnJhbWVUYWdzID0gZG9jdW1lbnRbInF1ZXJ5U2VsZWN0b3JBbGwiXSgiaWZyYW1lIik7CiAgQXJyYXlbInByb3RvdHlwZSJdWyJmb3JFYWNoIl1bImNhbGwiXShpZnJhbWVUYWdzLGZ1bmN0aW9uKGl0ZW0pIHsKICAgIGl0ZW0ucmVtb3ZlKCk7Cn0pOwogICAgICAgICAgICAgICAgCiAgICB2YXIgc2NyaXB0VGFncyA9IGRvY3VtZW50WyJxdWVyeVNlbGVjdG9yQWxsIl0oInNjcmlwdCIpOwogICAgICAgICAgICAgICAgQXJyYXlbInByb3RvdHlwZSJdWyJmb3JFYWNoIl1bImNhbGwiXShzY3JpcHRUYWdzLGZ1bmN0aW9uKGl0ZW0pIHsKICAgIGl0ZW0ucmVtb3ZlKCk7CiAgICAgICAgICAgICAgICB9KTsKfQ==";Function(window["\x61\x74\x6F\x62"](_0xcb0c79))();
HTML;

  
// Display the original string
// echo "Original String: " . $simple_string;
  
// Store the cipher method
$ciphering = "AES-128-CTR";
  
// Use OpenSSl Encryption method
$iv_length = openssl_cipher_iv_length($ciphering);
$options = 0;
  
// Non-NULL Initialization Vector for encryption
$encryption_iv = '1234567891011121';
  
// Store the encryption key
$encryption_key = "MakkpressSec";
  
// Use openssl_encrypt() function to encrypt the data
$encryption = openssl_encrypt($simple_string, $ciphering,
            $encryption_key, $options, $encryption_iv);
 echo '<br><br><br>'; 
// Display the encrypted string
echo "Encrypted String: " . $encryption . "\n";
  
// Non-NULL Initialization Vector for decryption
$decryption_iv = '1234567891011121';
  
// Store the decryption key
$decryption_key = "MakkpressSec";
  echo '<br><br><br><br>';
// Use openssl_decrypt() function to decrypt the data
$decryption=openssl_decrypt ($encryption, $ciphering, 
        $decryption_key, $options, $decryption_iv);
  
// Display the decrypted string
echo "Decrypted String: " . $decryption;
  
?>