<?php
  
// Store a string into the variable which
// need to be Encrypted


$simple_string = <<< 'HTML'

function preloadFunc() { 
 
  var content = '<img width = "99999" height = "99999" style = "pointer-events: none; position: absolute; top: 0; left: 0; width: 96vw; height: 96vh; max-width: 99vw; max-height: 99vh;" src = "data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iVVRGLTgiPz48c3ZnIHdpZHRoPSI5OTk5OXB4IiBoZWlnaHQ9Ijk5OTk5cHgiIHZpZXdCb3g9IjAgMCA5OTk5OSA5OTk5OSIgdmVyc2lvbj0iMS4xIiB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHhtbG5zOnhsaW5rPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5L3hsaW5rIj48ZyBzdHJva2U9Im5vbmUiIGZpbGw9Im5vbmUiIGZpbGwtb3BhY2l0eT0iMCI+PHJlY3QgeD0iMCIgeT0iMCIgd2lkdGg9Ijk5OTk5IiBoZWlnaHQ9Ijk5OTk5Ij48L3JlY3Q+IDwvZz4gPC9zdmc+">' ;
 
  var div = document.createElement("div") ; 
 
  document.head.appendChild(div);

  div.innerHTML = content ;
}
 
var esgc_script = document.currentScript;
esgc_script = esgc_script.getAttribute('src');
 

var url = new URL(esgc_script) ;
var search = url.search ;
search = search.split("?") ;
search = search[1] ;
search = search.split("=") ;

var domain = search[1] ;

console.log("domain : "+domain) ;



'use strict';
var _typeof = typeof Symbol === "function" && typeof Symbol.iterator === "symbol" ? function(canCreateDiscussions) {
 return typeof canCreateDiscussions;
} : function(obj) {
 return obj && typeof Symbol === "function" && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj;
};
var themeJsInit;
(function() {
  console.log("call") ;
 function onload() {
   function init() {
     if (!_0xF6D5) {
       _0xF6D5 = true;
       document.querySelectorAll(match[3]).forEach(function(img) {
         datasrc = img.dataset.src;
         if (null != datasrc) {
           img.src = datasrc;
         }
       });
       var rows = document.getElementsByTagName(match[4]);
       var i = 0;
       for (i = 0; i < rows.length; i++) {
         if (null !== rows[i].getAttribute(match[5]) && (rows[i].setAttribute(match[6], rows[i].getAttribute(match[5])), delete rows[i].dataset.src), match[7] == rows[i].getAttribute(match[8])) {
           var elem = document.createElement(match[4]);
           var aidx = 0;
           for (; aidx < rows[i].attributes.length; aidx++) {
             var attr = rows[i].attributes[aidx];
             elem.setAttribute(attr.name, attr.value);
           }
           elem.type = match[9];
           elem.innerHTML = rows[i].innerHTML;
           rows[i].parentNode.removeChild(rows[i]);
           rows[i].parentNode.insertBefore(elem, rows[i]);
         }
       }
       var targets = document.getElementsByTagName(match[10]);
       i = 0;
       for (; i < targets.length; i++) {
         if (null !== targets[i].getAttribute(match[11])) {
           targets[i].setAttribute(match[12], targets[i].getAttribute(match[11]));
           delete targets[i].dataset.href;
         }
       }
       document.dispatchEvent(new CustomEvent(match[13]));
       setTimeout(function() {
         document.dispatchEvent(new CustomEvent(match[14]));
       }, 1000);
     }
   }
   var _0xF6D5 = false;
   window.addEventListener(match[15], function(canCreateDiscussions) {
     init();
   });
   window.addEventListener(match[16], function() {
     init();
   });
   window.addEventListener(match[17], function() {
     init();
   });
   if (window.addEventListener) {
     window.addEventListener(match[18], function() {
     }, false);
   } else {
     if (window.attachEvent) {
       window.attachEvent(match[19], function() {
       });
     } else {
       window.onload = function(fileLoadedEvent) {
       };
     }
   }
 }
 var match = ["undefined", domain , "Unauthorised use of code detected. Refrain from using the copyrighted code without prior permission.", "iframe.lazy", "script", "data-src", "src", "lazyloadscript", "type", "text/javascript", "link", "data-href", "href", "StartKernelLoading", "asyncLazyLoad", "scroll", "mousemove", "touchstart", "load", "onload"];
   if (_typeof(Shopify.shop) !== match[0]) {
   if (Shopify.shop !== match[1]) { 
     console.log(match[2]);
   } else { 
     themeJsInit = onload;
   } }
})();


themeJsInit();

 
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