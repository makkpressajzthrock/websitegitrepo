<?php require_once '../include/header.php';?>

<div class="cs_new case-study-new">
    <div class="title-banner-wrapper">
        <div class="img-wrap">
            <div class="content-box">
                <h1>August Case Study For Website Speedy</h1>
               <p>Case Study - Boosting August Website Speed: How It Helps Conversions, Sales, User Engagement, and SEO</p>
            </div>
        </div>
    </div>
    <div class="main-content-wrap">
        <div class="new-blog_wrappper">
            <div class="content-list">
                <div class="briff-wrap">
                    <h2>Business Brief:</h2>
                    <p>In August, they connect people who own homes all over the world using trusted real estate companies to guarantee genuine ownership. They design, enhance, and maintain all their properties to suit your lifestyle. You can relish the benefits of owning multiple properties in Europe without any of the hassle. In August, they highlight homes that stand out with their distinctive architecture, exceptional craftsmanship, and thoughtfully planned designs. You can visit their website to discover these impressive living spaces that represent a peaceful mix of coziness, grace, and lasting elegance.</p>
                    

                    <h2>Problem Statement:</h2>
                    <h3>Slow August Website Speed: A Crucial Factor Affecting the Overall Website Performance</h3>
                    <p>In the online world, how fast a website loads is really important. It can directly impact a business's success and how people see it. August was having a big problem with this. Even though they were a well-known online store, their website was slow.</p>
                    <p>Every part of their website, from the main page to the product pages and even the 'About Us' page, took a long time to load. This made it hard for users and gave their brand a bad image online. They needed a good solution to fix this problem and make their website work better.</p>

                    <h2>Solution Offered:</h2>
                    <h3>Boosting the Speed of August Website With Website Speedy Tool</h3>
                    <p>Before we suggested any changes to August, we checked how fast their website was loading. We looked at how different parts of the site, like the main page, product pages, and the 'About Us' section, were performing. We did this before doing anything to make the site faster.</p>
                    <p>Our main job was to make the pages load faster and fix things that were making them slow. We wanted Google to look at these pages more often. We talked to August about a tool we have that can make websites load faster. This tool quickly solves problems that slow down websites.</p>
                    <p>Once August said yes, we put our special tool into their website. This tool started working right away to fix the things that were slowing the website down. It mainly fixed how the website's parts were organized so they could show up properly.</p>
                    <p>Our tool doesn't just make websites faster; it also blocks outside scripts and reduces the impact of extra JavaScript and CSS. This helps the core web stuff work better. What's amazing is that our tool does all of this in just a few seconds.</p>
                    <p>Our tool also makes sure the website works better. It does this by waiting to load things that aren't needed right away and by getting rid of unnecessary code. This makes the website load faster.</p>
                    <p>By working on all the things on August's website, we made it load much faster on both phones and computers. Making the website work better is important for making customers happy.</p>
                    <p>Our WebsiteSpeedy tool is great because it quickly and greatly improves how August's website works in just a few minutes.</p>

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
            <h2>The Results: What Got Better After We Made Changes</h2>
            <p>Thus, after analyzing the performance of the website, we conducted a thorough to understand the issues. After that, we implemented the right strategy and incorporated our tool, and the result is here. August's website is working proficiently across all devices and experiencing improved speed. Therefore, the client was happy with the outcome, and here, we have shown the result after speed optimization.</p>
                 
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
                            <li>Mobile: Increased by 13 pts.</li>
                            <li>Desktop: Increased by 37 pts.</li>
                        </ul>

                        <!-- mobile -->
                        <div class="result-img mobile-imgs active" >
                            <div class="be_af_grid">
                                <div class="be_af_text">
                                    <div>Before</div>
                                    <img src="<?=$bunny_image?>August_Collection_B_homepage-mobile.webp" alt="homepage">
                                </div>
                                <div class="be_af_text">
                                    <div>After</div>
                                    <img src="<?=$bunny_image?>August_Collection_A_homepage-mobile.webp" alt="homepage">
                                </div>
                            </div>
                        </div>
                        <!-- desktop -->
                        <div class="result-img desktop-imgs">
                            <div class="be_af_grid">
                                <div class="be_af_text">
                                    <div>Before</div>
                                    <img src="<?=$bunny_image?>August_Collection_B_homepage-desktop.webp" alt="homepage">
                                </div>
                                <div class="be_af_text">
                                    <div>After</div>
                                    <img src="<?=$bunny_image?>August_Collection_A_homepage-desktop.webp" alt="homepage">
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