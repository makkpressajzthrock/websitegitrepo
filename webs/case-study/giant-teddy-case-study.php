<?php require_once '../include/header.php';?>

<div class="cs_new case-study-new">
    <div class="title-banner-wrapper">
        <div class="img-wrap">
            <div class="content-box">
                <h1>Giant Teddy Case Study For Website Speedy</h1>
               <p>Case Study- Revolutionizing Website Performance For Giant Teddy With Faster Loading Times & Consistent Geen Core Web Vitals</p>
            </div>
        </div>
    </div>
    <div class="main-content-wrap">
        <div class="new-blog_wrappper">
            <div class="content-list">
                <div class="briff-wrap">
                    <h2>Business Brief:</h2>
                    <p>Giant Teddy was founded nine years ago with a deep passion for crafting personalized gifts that conveyed thoughtfulness and care. Initially, these unique gifts comprised stuffed animals paired with outfits or accessories that reflected the personalities of the recipient, resulting in truly one-of-a-kind gifts. Soon, their teddy bears become the first choice of every buyer when it comes to gifting. Today, Giant Teddy is known for well-constructed stuffed bears and imaginative teddy bear gift packages that continue to be a popular choice for gift-givers looking to delight their loved ones.</p>

                    <h2>Problem Statement:</h2>
                    <h3>Discovering Effective Solutions To Resolve Hindrances To Achieve Lightning-Fast Website Speed</h3>
                    <p>Faster website load times are crucial for every website because it also affects user experience and SEO. Unfortunately, this is what was missing from Giant Teddy's website. Despite being a popular name in the gifting industry, the website's performance on page speed tools was quite low. From the home page, collection page, product page, and about us pages, each and every page of Giant Teddy's website, loading time was quite low. This directly affected their user experience, bounce rate and brand's online presence. When the brand reached out to us for the same, we were already working on their design and SEO part. While certain manual or traditional methods could be applied to improve the website speed of every page, Giant Teddy required a solution that could do it all for great results. </p>
                    
                    <h2>Solution:</h2>
                    <h3>Boosting Website Speed To Transform Brand's Online Presence With Our Revolutionary Tool</h3>
                    <p>Before suggesting or implementing anything to Giant Teddy for their website speed, we tapped the current score of website loading speed. We checked the performance scores for the homepage, collection page, product page, and about us page scores before optimization. Reducing loading times & working on render blocking issues for these pages was important because it would help increase the number of pages crawled by Google. While discussing with the client, we shared with them our website speed optimization tool. Our website speedy tool is a saas based website optimization tool that instantly reduces website loading times by working on assets that need to be rectified.</p>
                    <p>After getting a green signal from Giant Teddy to use our tool, we implemented our custom hard-coded script to the client’s website, and it started fixing issues that would ultimately enhance the website speed. The automated script started looking for render-blocking elements to rearrange them in order to enhance the website DOM. Website Speedy tool also blocked third-party scripts and reduced the impact of unused JS and CSS that helped achieve green core web vitals. While all of this sounds like a hassle, our tool does all of this in seconds.</p>
                    <p>For achieving better results, the tool also works on optimizing the images by implementing lazy loading and removing the redundant codes from the website. Moreover, our tool also delayed loading non-critical resources until user interaction was detected to enhance the loading time. Post fixing all the assets on Giant Teddy's website, we were able to achieve better results in page speed scores for all browsers on mobile and desktop devices. As we said earlier, improving web performance is very important for user experience. One advantage of using a website speedy tool is user experience pointer because it is truly a great website tool that instantly improves the website performance for Giant Teddy in a fraction of minutes.</p>
            
                    
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
                   <p>After analyzing Giant Teddy's website on our tool and addressing all the issues found, we achieved great results on Google Page Insights for every page of the website. This helped us improve the sales and conversion rate for the brand.</p>
                   <p>Here's the result of trusting the website's speedy tool for speed optimization:</p>

                   <div class="tabs-result-wrap">
                

                <div class="tb-imgs">

                    <div class="result-imgs">
                    <ul>
                        <li>Homepage speed score improved for mobile increased by 54 pts.</li>
                        <li>Homepage speed score improved for desktop increased by 47 pts.</li>
                    </ul>

                   <ul>
                       <li>The collection page speed score increased for mobile by 42 pts.</li>
                       <li>The collection page speed score increased for the desktop by 50 pts.</li>
                    </ul>

                    <ul>
                        <li>Product page speed score improved for mobile by 42 pts.</li>
                        <li>Product page speed score improved for desktop by 50 pts.</li>
                    </ul>

                    <ul>
                        <li>About us, the page speed score increased for mobile by 49 pts.</li>
                        <li>About us, the page speed score increased for the desktop by 29 pts.</li>
                    </ul>
                        
                
                        
                                               
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