<?php require_once '../include/header.php';?>

<div class="cs_new case-study-new">
    <div class="title-banner-wrapper">
        <div class="img-wrap">
            <div class="content-box">
                <h1>BalumaBliss Case Study For Website Speedy</h1>
                <p>Case Study - Expediting BalumaBliss's Online Progress Through Swift Website Loading Times</p>
            </div>
        </div>
    </div>
    <div class="main-content-wrap">
        <div class="new-blog_wrappper">
            <div class="content-list">
                <div class="briff-wrap">
                    <h2>Business Brief:</h2>
                    <p>BalumaBliss is an online store that sells products worldwide. They primarily sell products for hair care and skin care. Their goods categories include lash extensions, curling rod headbands, steel precision tools, massaging heating pads, and more. Additionally, they produce body care and nail care products. BalumaBliss is known for fast and best shipping and goods quality.</p>
                    

                    <h2>Problem Statement:</h2>
                    <h3>Identifying the Barriers to Enhance Website Loading Speed</h3>
                    <p>A quick website load time is critical for all websites since it affects both user experience and SEO. However, BalumaBliss's website is lacking in this department.</p>
                    <p>When the company realized the significance of website speed, it examined its website's performance on page speed tools and discovered that its score was extremely low. Each page, from the home page to the collection page, product page, and about us page, took a long time to load.</p>
                    <p>This had a direct impact on their user experience, raised their bounce rate, and harmed their search engine visibility. BalumaBliss contacted us for the same reason and asked us to develop solutions to assist them in increasing the loading speed across every page on their website.</p>


                    <h2>Solution Offered:</h2>
                    <h3>Enhancing Brand Visibility Through Our Effective Website Speed Enhancement Tool</h3>
                    <p>We initially evaluated the loading speed of BalumaBliss's homepage, collection page, product page, and about us page to guarantee the best performance of the website. We hoped to improve the number of pages indexed by Google by finding and fixing render blocking issues and improving loading times.</p>
                    <p>We offered our sophisticated website performance optimization tool, a SaaS-based solution that quickly corrected anything that hindered the website, throughout our conversation with the client. BalumaBliss should expect a big increase in website loading speeds and overall efficiency using this approach.</p>
                    <p>We instantly deployed our own hard-coded script into their website after getting them to test our website speed-boosting solution. To improve the website's DOM, our automated script began detecting and restructuring render-blocking items.</p>
                    <P>The Website Speedy tool successfully disabled third-party scripts and reduced the burden of unneeded JS and CSS, yielding excellent green core web vitals.</P>
                    <P>The client's website's speed and general performance improved significantly as a result of our technology.</P>
                    <P>The tool streamlines your content and uses the technique of lazy loading to remove extra code from your photos to improve the speed of your website. We were pleased to notice a big increase in page performance scores across all browsers on both mobile and desktop devices after correcting all of BalumaBliss's files.</P>
                    <P>Our team went above and beyond to ensure that the preview theme was fully tested on all major devices and browsers, and we're pleased to announce that it works flawlessly. We managed to streamline the optimization procedure with our Website Speedy Tool, resulting in lightning-fast website performance for BalumaBliss in a matter of minutes.</P>
                  
                    

                </div>
                <div class="deliverables">
                    <div class="inner-content">
                        <h2>Deliverables</h2>
                        <ul>
                            <li>Core Web Vitals</li>
                            <li>Lazy Loading</li>
                            <li>Page Speed</li>
                            <li>Mobile Optimization</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        
    </div>

    <div class="results-wrapper">
        <div class="new-blog_wrappper">
            <h2>Results Since We Started:</h2>
            <P>We effectively increased the scores on Google Page Insights, GT Metrix, and Pingdom for each page of the website by using our technology to examine BalumaBliss's website and rectify any reported issues. This resulted in increased sales and conversion rates for the brand.</P>
            <P>Certainly, here is the result of the brand speed before and after optimization.</P>

            <div class="tabs-result-wrap">
                <div class="tb-links">
                    <ul class="tb-btn-list">
                        <li><a class="tb-link-mobile active">Mobile</a></li>    
                        <li><a class="tb-link-desktop">Desktop</a></li>    
                    </ul>
                </div>
           

                <div class="tb-imgs">

                    <div class="result-imgs">
                        <!-- HOMEPAGE -->
                        <h2>Home Page</h2>
                        <h3>Page Speed Insight</h3>
                        <ul>
                            <li>Mobile: Increased by 53 pts.</li>
                            <li>Desktop: Increased by 57 pts.</li>
                        </ul>

                        <!-- mobile -->
                        <div class="result-img mobile-imgs active" >
                            <div class="be_af_grid">
                                <div class="be_af_text">
                                    <div>Before</div>
                                    <img src="<?=$bunny_image?>Bliss-B-mobile.webp" alt="homepage">
                                </div>
                                <div class="be_af_text">
                                    <div>After</div>
                                    <img src="<?=$bunny_image?>Bliss-A-mobile.webp" alt="homepage">
                                </div>
                            </div>
                        </div>
                        <!-- desktop -->
                        <div class="result-img desktop-imgs">
                            <div class="be_af_grid">
                                <div class="be_af_text">
                                    <div>Before</div>
                                    <img src="<?=$bunny_image?>Bliss-B-desktop.webp" alt="homepage">
                                </div>
                                <div class="be_af_text">
                                    <div>After</div>
                                    <img src="<?=$bunny_image?>Bliss-A-desktop.webp" alt="homepage">
                                </div>
                            </div>
                        </div>
                       
                        
                                               
                    </div>
                </div>

                
            </div>


        </div>
    </div>
</div>


<script>

$(document).ready(function(){


    $(".tb-btn-list a").click(function(e) {
        e.preventDefault();
        var $link = $(this); // Wrap 'this' in a jQuery object

        
        if (!$link.hasClass("active")) {
            $link.addClass("active"); // Use the jQuery object to add the class
            $link.closest("li").siblings("li").find("a").removeClass("active");

            if($link.hasClass("tb-link-desktop")){
                $link.closest(".tabs-result-wrap").find(".desktop-imgs").addClass("active")
                $link.closest(".tabs-result-wrap").find(".mobile-imgs").removeClass("active")
            } else if($link.hasClass("tb-link-mobile")){
                $link.closest(".tabs-result-wrap").find(".mobile-imgs").addClass("active")
                $link.closest(".tabs-result-wrap").find(".desktop-imgs").removeClass("active")
            }
            
        }
    });
})




</script>


<?php require_once '../include/footer.php';?>