if(window.location.search != '?nospeedy')
{   



  var date = new Date(); var f = Date.now(); var observer; var Xzxs = {-Xzxs_3-};
    var SxulRs = "{-Xzxsrdf_3-}";
    var wxcs = window;
    var lcvd = location;

    if(Xzxs > f  && SxulRs == wxcs.lcvd.hostname   {-ExtraCondition_6-}){
        'use strict';

        // Immediately inject an empty pixel to trigger FCP
        var img = new Image(1, 1); // 1x1 pixel image
        img.src = "data:image/gif;base64,R0lGODlhAQABAIAAAP///wAAACH5BAEAAAAALAAAAAABAAEAAAICRAEAOw=="; // Transparent GIF
        document.body.appendChild(img);










    var date = new Date(); var f = Date.now(); var observer; var Xzxs = {-Xzxs_3-};
    var SxulRs = "{-Xzxsrdf_3-}";
    var wxcs = window;
    var lcvd = location;

    if(Xzxs > f  && SxulRs == wxcs.lcvd.hostname   {-ExtraCondition_6-}){
        'use strict';
        var _typeof = typeof Symbol === "function" && typeof Symbol.iterator === "symbol" ? function(canCreateDiscussions) {
            return typeof canCreateDiscussions;
        } : function(obj) {
            return obj && typeof Symbol === "function" && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj;
        };
        var themeJsInit;
        (function() {
            function onload() {
                function init() {
                    if (!_0xF6D5) {
                        _0xF6D5 = true;

                        // Existing code for handling specific elements defined by 'match'
                        document.querySelectorAll(match[3]).forEach(function(img) {
                            var datasrc = img.dataset.src;
                            if (null != datasrc) {
                                img.src = datasrc;
                            }
                        });

                        // New code: Handling <picture> elements for lazy loading
                        document.querySelectorAll('picture').forEach(function(picture) {
                            // Handling <source> elements within <picture>
                            picture.querySelectorAll('source[data-srcset]').forEach(function(source) {
                                const dataSrcSet = source.dataset.srcset;
                                if (dataSrcSet) {
                                    source.srcset = dataSrcSet;
                                    source.removeAttribute('data-srcset');
                                }
                            });

                            // Handling <img> fallback within <picture>
                            const img = picture.querySelector('img[data-src]');
                            if (img) {
                                const dataSrc = img.dataset.src;
                                if (dataSrc) {
                                    img.src = dataSrc;
                                    img.removeAttribute('data-src');
                                }
                            }
                        });

                        // Rest of the existing logic...
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
                // Additional existing logic for event listeners...
            }
            if(Xzxs > f  && SxulRs == wxcs.lcvd.hostname   {-ExtraCondition_7-} ){
                var match = ["undefined", "{%-store-url-%}", "Unauthorised use of code detected. Refrain from using the copyrighted code without prior permission.", "iframe.lazy", "script", "data-src", "src", "lazyloadscript", "type", "text/javascript", "link", "data-href", "href", "StartKernelLoading", "asyncLazyLoad", "scroll", "mousemove", "touchstart", "load", "onload"];
            }
            if (typeof Shopify  !== 'undefined') {
                if (_typeof(Shopify.shop) !== match[0]) {

                    if(Xzxs > f   && SxulRs == wxcs.lcvd.hostname  {-ExtraCondition_8-}){
                        if (Shopify.shop !== match[1]) { 
                            themeJsInit = onload;
                        } else { 
                            themeJsInit = onload;
                        }

                    }
                }

            }

        })();

        document.addEventListener('DOMContentLoaded', function() {
            // Temporarily disable stylesheets linked to fonts
            document.querySelectorAll('link[href*="google-fonts"]').forEach(function(link) {
                link.setAttribute('data-href', link.getAttribute('href'));
                link.setAttribute('data-rel', link.getAttribute('rel'));
                link.removeAttribute('href'); // This prevents the font from being loaded initially
                link.setAttribute('rel', 'preload'); // Prevents it from being applied
            });
        });

        window.addEventListener('load', function() {
            // Re-enable stylesheets after the page has fully loaded
            document.querySelectorAll('link[data-href*="google-fonts"]').forEach(function(link) {
                link.setAttribute('href', link.getAttribute('data-href'));
                link.setAttribute('rel', link.getAttribute('data-rel'));
                link.removeAttribute('data-href');
                link.removeAttribute('data-rel');
            });
        });

}

    }
}




document.addEventListener('DOMContentLoaded', function() {
    const targetText = "Guided Adventure Experiences and Camps for Sons and Daughters"; // Text to search for within the element
    const blocks = document.querySelectorAll('.w-text-block');

    blocks.forEach(function(block) {
        // Check if the block contains the unique text
        if (block.textContent.includes(targetText)) {
            // Hide the original block
            block.style.display = 'none';
            
            // Create and show the placeholder
            const placeholder = document.createElement('div');
            placeholder.style.width = '100%'; // Adjust as necessary
            placeholder.style.height = '100px'; // Adjust to match the expected content size
            placeholder.style.backgroundColor = '#f0f0f0'; // Placeholder styling
            placeholder.innerText = 'Content is loading...'; // Placeholder text

            // Insert the placeholder before the block
            block.parentNode.insertBefore(placeholder, block);

            // When the whole page has loaded, swap the placeholder with the original content
            window.addEventListener('load', function() {
                placeholder.style.display = 'none'; // Hide placeholder
                block.style.display = 'block'; // Show original content
            });
        }
    });
});















