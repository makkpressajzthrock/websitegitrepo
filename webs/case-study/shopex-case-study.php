<?php require_once '../include/header.php';?>

<div class="cs_new case-study-new">
    <div class="title-banner-wrapper">
        <div class="img-wrap">
            <div class="content-box">
                <h1>Shopex Case Study For Website Speedy</h1>
               <p>Case Study - Maximizing Shopex Speed: A Catalyst for Enhanced Conversions, Sales, User Engagement, and SEO Success</p>
            </div>
        </div>
    </div>
    <div class="main-content-wrap">
        <div class="new-blog_wrappper">
            <div class="content-list">
                <div class="briff-wrap">
                    <h2>Business Brief:</h2>
                    <p>Shopex is an ecommerce store founded by Luca Colenbrander, an entrepreneur. Shopex is a website that provides multiple category products online to the scattered market.</p>
                    <p>They sell products in categories of electronics, toys, home decorations, and kitchens. Here are their top product details: lint hair remover roller for clothes, portable air conditioner personal cooling fan, double heart shaggy rug for living room, fuzzy hand towel ball soft, absorbent microfiber towels, adhesive multi-purpose wall mounted hooks, laptop side mount magnetic phone holder, smart thermos bottle and many more.</p>
                    <p>They guarantee that their product performs excellent at an affordable price. They value your time and provide you with early shipment of your ordered products.</p>

                    <h2>Problem Statement:</h2>
                    <p>Sluggish Shopex Speed: A Critical Factor Impacting Overall Website Performance</p>
                    <p>In the world of online presence, the speed at which a website load plays a pivotal role, directly impacting business growth and reputation. Thus, Shopex was struggling with this problem. Despite their prominent position in the online marketplace, their website's loading times left much to be desired.</p>
                    <p>Every section of their website, from the homepage to product listings and the 'About Us' page, suffered from long loading times. This had a great effect on user experience, resulting in higher bounce rates and a compromised online brand image. While conventional methods might have provided some relief, Shopex was in search of a comprehensive solution to ensure optimal results.</p>

                    <h2>Solution Offered:</h2>
                    <p>Before suggesting any modifications to Shopex, we conducted a thorough assessment of their website's current loading speed. We carefully scrutinized the performance metrics of various pages, including the homepage, collection pages, product pages, and the about us section, all before implementing any optimization measures.</p>
                    <p>We took on the crucial job of reducing page load times and fixing issues that were slowing down the rendering of web pages. Our goal was to get Google to crawl these pages more frequently. In our discussions with the client, we introduced them to our website speed optimization tool, which is a software-as-a-service solution designed to improve loading times quickly by addressing problematic elements.</p>
                    <p>Once we got permission from Shopex, we smoothly integrated our customized script directly into their website. This automated script immediately started fixing issues that were causing slow speeds, mainly by reorganizing elements that were blocking the proper rendering of the website's Document Object Model (DOM).</p>
                    <p>The Website Speedy tool doesn't stop at just improving your website's speed; it also does an excellent job of blocking third-party scripts and minimizing the effects of unused JavaScript and CSS, which in turn positively affects your core web vitals. What's truly impressive is that our tool accomplishes all of these improvements in just a matter of seconds.</p>
                    <p>Additionally, our tool places a strong emphasis on optimizing performance. It does this by introducing lazy loading and removing unnecessary code from your website. Moreover, it intelligently delays the loading of non-essential resources until it detects user interaction, further enhancing your website's loading times.</p>
                    <p>By addressing all the assets on Shopex's website, we managed to make significant improvements in page speed scores for both mobile and desktop browsers. As we've emphasized before, enhancing web performance is crucial for creating positive user experiences.</p>
                    <p>One remarkable advantage of our WebsiteSpeedy tool is its ability to make a profound impact on user experience. It swiftly and significantly enhances Shopex's website performance in just a matter of minutes.</p>
                   
                    
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
                    <h2>The Outcomes: Advancements Since Implementation</h2>
                    <p>After conducting a thorough analysis of Shopex's website using our tool and subsequently addressing the identified issues, we observed significant improvements across all pages. These achievements directly contributed to increased sales and conversion rates for the brand, emphasizing the importance of having a smooth and effective online platform.</p>
                    <p>Let’s delve into the changes in their website speed before and after optimization.</p>

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
                            <li>Mobile: Increased by 15 pts.</li>
                            <li>Desktop: Increased by 10 pts.</li>
                        </ul>

                        <!-- mobile -->
                        <div class="result-img mobile-imgs active" >
                            <div class="be_af_grid">
                                <div class="be_af_text">
                                    <div>Before</div>
                                    <img src="<?=$bunny_image?>shopex_before_mobile.webp" alt="homepage">
                                </div>
                                <div class="be_af_text">
                                    <div>After</div>
                                    <img src="<?=$bunny_image?>shopex_after_mobile.webp" alt="homepage">
                                </div>
                            </div>
                        </div>
                        <!-- desktop -->
                        <div class="result-img desktop-imgs">
                            <div class="be_af_grid">
                                <div class="be_af_text">
                                    <div>Before</div>
                                    <img src="<?=$bunny_image?>shopex_before_desktop.webp" alt="homepage">
                                </div>
                                <div class="be_af_text">
                                    <div>After</div>
                                    <img src="<?=$bunny_image?>shopex_after_desktop.webp" alt="homepage">
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