<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Load External Resource After Page Load</title>
</head>
<body>

<!-- Your page content goes here -->
   <!-- jquey cdn -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" ></script>
    <script src="https://code.jquery.com/jquery-3.7.2.min.js" ></script>
    <style src="https://code.jquery.com/jquery-3.7.3333.min.css" ></style>

    <!-- Bing Ads Script Start -->
    <a href="https://code.jquery.com/jquery-3.7.0.min.js"></a>
    <a href="https://code.jquery.com/jquery-3.7.1.min.js"></a>



<script>


/// JS script
/// JS ancor
//// CSS


// Function to extract external URLs from a webpage
function extractExternalUrls() {
    // Get all anchor (a) elements on the page
    var anchorElements = document.getElementsByTagName('a');
    var anchorElementsScript = document.getElementsByTagName('script');
    var externalUrls = [];

    // Loop through all anchor elements
    for (var i = 0; i < anchorElements.length; i++) {
        var anchor = anchorElements[i];
        var href = anchor.getAttribute('href');

        // Check if the href attribute exists and if it's an external URL
        if (href && isExternalUrl(href)) {
            externalUrls.push(href);
        }
    }


     for (var j = 0; j < anchorElementsScript.length; j++) {
        var anchor = anchorElementsScript[j];
        var href = anchor.getAttribute('src');

        // Check if the href attribute exists and if it's an external URL
        if (href && isExternalUrl(href)) {
            externalUrls.push(href);
        }
    }

    return externalUrls;
}

// Function to check if a URL is external
function isExternalUrl(url) {
    // Create a dummy anchor element to parse the URL
    var dummyAnchor = document.createElement('a');
    dummyAnchor.href = url;

    // Check if the hostname of the URL is different from the current hostname
    return dummyAnchor.hostname !== window.location.hostname;
}

// Usage example
var externalUrls = extractExternalUrls();


console.log(externalUrls);




  window.onload = function() { 

    // Dynamically create a link element for CSS


    var cssLink = document.createElement('link');
    cssLink.rel = 'stylesheet';
    cssLink.href = 'scripttest.css';
    // Append the link element to the head of the document
    document.head.appendChild(cssLink);




    // Dynamically create a script element for JavaScript
    var jsScript = document.createElement('script');
    jsScript.src = 'scripttest.js';
    // Append the script element to the body of the document
    document.body.appendChild(jsScript);
  };
</script>
</body>
</html>