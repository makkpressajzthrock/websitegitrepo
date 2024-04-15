
<!-- Test Website section start -->

<div class="website__test__section" >
    <div class="section__wrapper">
        <div class="website__test__wrapper">
            <div class="heading">
                <h2>Test your <span>Website</span> Speed now</h2>
                <p>You can quickly analyze your site just by entering the site address in the input below! Find you current speed with Google page insights and instantly fix it to grow your sales and Conversion rate!</p>
            </div>
            <div class="form__wrapper">
                <form action="" id='index_form'>
                    <input type="text" name="search" id="search" placeholder="https://..." required>
                    <button onclick="urlbtn('search')" type="button" class="btn">Analyze</button>
                </form>
                <span id='url_validate' class="url_validate"></span>
            </div>


        </div>
    </div>
</div>
<!-- Test Website section end -->
<?php include 'urls_log.php' ?>

<!-- Footer mail Include -->

<?php  
    if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on')   
         $url = "https://";   
    else  
         $url = "http://";   
    // Append the host(domain name, ip) to the URL.   
    $url.= $_SERVER['HTTP_HOST'];   
    
    // Append the requested resource location to the URL   
    $url.= $_SERVER['REQUEST_URI'];    


  $url_array = explode('/', $url);

  $url_array[4] = null;

  $url__con = implode('/', $url_array);

  echo "<div style='display:none'>$url__con</div>";
    // echo var_dump($url__con);
    if ($url__con == 'https://websitespeedy.com/blogs/' || $url__con == 'https://websitespeedy.com/case-study/') {
        include '../mail-footer.php';
    }else {
        include './mail-footer.php';
    }

  ?>  




<!-- Footer Start -->

<footer >
    <div class="section__wrapper">
        <div class="footer__wrapper">
            <div class="f-grid about">
                <div class="logo">
                    <img src="https://websitespeedy.com/assets/images-optimized/Speedy-LOGO-dark.webp" alt="Website Speedy Logo">
                </div>
                <div class="about___us" >
                    <div class="address">
                            <p>Website Speedy is a SaaS-based website optimization tool that instantly reduces website loading times.</p>
                    </div>
                    <a href="mailto:support@websitespeedy.com" class="mail__icon__footer"><span>support@websitespeedy.com</span></a>
                </div>
            </div>

            <div class="f-grid">
                <div class="footer__sec__heading">
                    <h5>Speed Optimization</h5>
                </div>
                <ul>
                    <li><a href="/shopify-speed-optimization.php">Shopify Speed Optimization</a></li>
                    <li><a href="/wix-editor-x-speed-optimization.php">WIX | Editor X Speed Optimization</a></li>
                    <li><a href="/bigcommerce-speed-optimization.php">Bigcommerce Speed Optimization</a></li>
                    <li><a href="/squarespace-speed-optimization.php">Squarespace Speed Optimization</a></li>
                    <li><a href="/shift4shop-speed-optimization.php">Shift4Shop Speed Optimization</a></li>
                    <li><a href="/clickfunnels-speed-optimization.php">Clickfunnels Speed Optimization</a></li>
                    <li><a href="/webwave-speed-optimization.php">Webwave Speed Optimization</a></li>
                    <li><a href="/weebly-speed-optimization.php">Weebly Speed Optimization</a></li>
                    <li><a href="/webflow-speed-optimization.php">Webflow Speed Optimization</a></li>
                    <li><a href="/hubspot-speed-optimization.php">HubSpot Speed Optimization</a></li>
                    <li><a href="/jimdo-speed-optimization.php">Jimdo Speed Optimization</a></li>
                    <li><a href="/bigcartel-speed-optimization.php">Big Cartel Speed Optimization</a></li>
                    <li><a href="/webnode-speed-optimization.php">Webnode Speed Optimization</a></li>
                    <li><a href="/tilda-speed-optimization.php">Tilda Speed Optimization</a></li>
                    <li><a href="/signup.php">Custom Website Speed Optimization</a></li>
                    <li><a href="/signup.php">Saas Speed Optimization</a></li>
                </ul>
            </div>

            <div class="f-grid">
                <div class="footer__sec__heading">
                    <h5>Fix Core web Vitals</h5>
                </div>
                <ul>
                                            <li><a href="/fix-shopify-core-web-vitals.php">Shopify Core Web Vitals</a></li>
                                            <li><a href="/fix-wix-editor-x-core-web-vitals.php">Wix/Editor Core Web Vitals</a></li>
                                            <li><a href="/fix-bigcommerce-core-web-vitals.php">Bigcommerce Core Web Vitals</a></li>
                                            <li><a href="/fix-squarespace-core-web-vitals.php">Squarespace Core Web Vitals</a></li>
                                            <li><a href="/fix-shift4shop-core-web-vitals.php">Shift4Shop Core Web Vitals</a></li>
                </ul>
            </div>

            <div class="f-grid">
                <div class="footer__sec__heading">
                    <h5>Legal</h5>
                </div>
                <ul>
                    <li><a href="/privacy-policy.php">Privacy Policy</a></li>
                    <li><a href="/cookie-policy.php">Cookie Policy</a></li>
                    <li><a href="/terms-of-use.php">Terms Of Use</a></li>
                    <li><a href="/cancellation-refund-policy.php">Cancellation and Refund Policy</a></li>
                </ul>
            </div>

            <div class="f-grid">
                <div class="footer__sec__heading">
                    <h5>Pages</h5>
                </div>
                <ul>
                    <li><a href="/why-website-speed-matters.php">Why Speed Matters</a></li>
                    <li><a href="/speed-guarantee.php">Speed Guarantee</a></li>
                    <li><a href="/website-speed-optimiation-cost.php" class="pricing_link_link" >Pricing</a></li>
                    <li><a href="https://help.websitespeedy.com/faqs" target="_blank">FAQ</a></li>
                    <li><a href="/blogs.php">Blogs</a></li>
                    <li><a href="/case-study.php">Case Study</a></li>
                    <li><a href="/contact-us.php">Contact Us</a></li>
                    <li><a href="https://help.websitespeedy.com/" target="_blank">Knowledge Base</a></li>
                    <li><a href="/agency-partner.php">Agency Partner</a></li>
                    
                </ul>
            </div>

            


            <div class="trust__icons" >
            <a href="https://www.producthunt.com/posts/website-speedy?utm_source=badge-featured&utm_medium=badge&utm_souce=badge-website&#0045;speedy" target="_blank"><img src="https://api.producthunt.com/widgets/embed-image/v1/featured.svg?post_id=385085&theme=light" alt="Website&#0032;Speedy&#0032; - Instantly&#0032;reduce&#0032;your&#0032;website&#0032;loading&#0032;time&#0032;by&#0032;3x | Product Hunt" style="width: 250px; height: 54px;" width="250" height="54" /></a>
             <img src="https://websitespeedy.com/assets/images-optimized/stripe-badge-transparent.webp" alt="strip logo">   
            </div>
        </div>

        

        <div class="footer__bottom">

            <div class="social__links" > 
                <a class="facebook__link" href="https://www.facebook.com/websitespeedy" target="_blank"></a>
                <a class="insta__link" href="https://www.instagram.com/websitespeedy/" target="_blank" ></a>
                <a class="linkedin__link" href="https://www.linkedin.com/company/websitespeedy/" target="_blank"></a>
                <a class="youtube__link" href="https://www.youtube.com/channel/UC044W4qzCU9wiF1DJhl3puA" target="_blank"></a>
            </div>

            <div class="copyright">
                © 2023 Website Speedy By <a href="https://makkpress.com/" target="_blank" >Makkpress</a>. All rights reserved
            </div>

        </div>
    </div>
</footer>



<script async>
const formCwv = document.getElementById('form__cwv');

if (typeof formCwv !== 'undefined') {
    
}else{
    formCwv.addEventListener('submit', (e) => {
        e.preventDefault();
        window.location.href="https://websitespeedy.com/signup.php"; 
    })   
}
 
</script>


<script src="/assets/js/slick.min.js" type="text/javascript" defer="defer"></script>
<script src="/assets/js/app.js" defer="defer"></script>

<script defer="defer" >
document.addEventListener("DOMContentLoaded", () => {
    setTimeout(() => {
        if($('.slider-slick-before-afer') === 'undefined') {
            function runRepeatedCode() {
                    // Your code to be executed goes here
                    $('.slider-slick-before-afer').slick({
                        dots: false,
                        infinite: true,
                        autoplay:true,
                        autoplaySpeed:5000,
                        speed: 1000,
                        slidesToShow: 1,
                        slidesToScroll: 1,
                        pauseOnHover:true
                    });
                    if($('.slider-slick-before-afer') != 'undefined') {
                        clearTimeout(setTimeout(runRepeatedCode, 200));
                    }else{
                        setTimeout(runRepeatedCode, 200);
                    }  
                }
            runRepeatedCode();
        } else {
            $('.slider-slick-before-afer').slick({
                dots: false,
                infinite: true,
                autoplay:true,
                autoplaySpeed:5000,
                speed: 1000,
                slidesToShow: 1,
                slidesToScroll: 1,
                pauseOnHover:true
            });
        }
        


            $('.cwv__page__slick').slick({
            dots: false,
            infinite: true,
            autoplay:true,
            autoplaySpeed:5000,
            speed: 1000,
            slidesToShow: 1,
            slidesToScroll: 1,
            pauseOnHover:true
            });

            $('.review__slider').slick({
            dots: false,
            infinite: true,
            autoplay:true,
            autoplaySpeed:5000,
            speed: 1000,
            slidesToShow: 3,
            slidesToScroll: 1,
            pauseOnHover:true,
            responsive: [
                {
                breakpoint: 991,
                settings: {
                    slidesToShow: 2,
                    slidesToScroll: 1,
                }
                },
                {
                breakpoint: 780,
                settings: {
                    slidesToShow: 2,
                    slidesToScroll: 1
                }
                },
                {
                breakpoint: 480,
                settings: {
                    slidesToShow: 1,
                    slidesToScroll: 1
                }
                }

            ]
            });


            $('.facts__slides').slick({
            dots: true,
            arrows:false,
            infinite: true,
            autoplay:true,
            autoplaySpeed:3000,
            speed: 1000,
            slidesToShow: 1,
            slidesToScroll: 1,
            pauseOnHover:true
            });

            $( document ).ready(function() {
                    if($(window).width() < 550 ) {
                        $('.partners__grid').slick({
                            infinite: true,
                            slidesToShow: 2,
                            slidesToScroll: 1,
                            dots:true,
                            arrows:false,
                            autoplay: true,
                            autoplaySpeed: 1500
                            });
                    }
            });
    }, 500);
    
})
</script>


<script async>
    document.addEventListener('DOMContentLoaded', function() {
        const urlPage = window.location.href;
        if (urlPage === 'https://websitespeedy.com/contact-us.php' || urlPage === 'https://websitespeedy.com/agency-partner.php') {
            const footerForm = document.getElementsByClassName('contact__form__only');
            footerForm[0].remove();
        }
    })
</script>

<script async>
        
       
        const megaMenu = document.getElementById('megaMenuOne');
        const megaMainLink = megaMenu.getElementsByClassName('link__menu');
        const innerMegaMenu = megaMenu.getElementsByClassName('megamenu');

        megaMainLink[0].addEventListener('mouseover', () => {
            innerMegaMenu[0].style.display = "block";
            megaMainLink[0].classList.add('rotate');
        })
        megaMainLink[0].addEventListener('mouseout', () => {
            innerMegaMenu[0].style.display = "none";
            megaMainLink[0].classList.remove('rotate');
        })
        innerMegaMenu[0].addEventListener('mouseover', () => {
            innerMegaMenu[0].style.display = "block";
            megaMainLink[0].classList.add('rotate');
        })
        innerMegaMenu[0].addEventListener('mouseout', () => {
            innerMegaMenu[0].style.display = "none";
            megaMainLink[0].classList.remove('rotate');
        })

        const dropDown = document.getElementById('dropdownMenuOne');
        const dropDownLink = dropDown.getElementsByClassName('link__menu');
        const innerDropDownMenu = dropDown.getElementsByClassName('dropdown');

        dropDownLink[0].addEventListener('mouseover', () => {
            innerDropDownMenu[0].style.display = "block";
            dropDownLink[0].classList.add('rotate');
        })
        dropDownLink[0].addEventListener('mouseout', () => {
            innerDropDownMenu[0].style.display = "none"
            dropDownLink[0].classList.remove('rotate');
        })
        innerDropDownMenu[0].addEventListener('mouseover', () => {
            innerDropDownMenu[0].style.display = "block";
            dropDownLink[0].classList.add('rotate');
        })
        innerDropDownMenu[0].addEventListener('mouseout', () => {
            innerDropDownMenu[0].style.display = "none";
            dropDownLink[0].classList.remove('rotate');
        })
        
        //Mobile Menu 
        
        const mobileOneMenu = document.getElementById('mobileDropDownOne');
        const linkOne = mobileOneMenu.getElementsByClassName('menu__link');
        const dropOne = mobileOneMenu.getElementsByClassName('mobile__dropdown');
        linkOne[0].addEventListener('click', () => {
            dropOne[0].classList.toggle('open');
            linkOne[0].classList.toggle('open');
        })
        
        const mobileTwoMenu = document.getElementById('mobileDropDownTwo');
        const linkTwo = mobileTwoMenu.getElementsByClassName('menu__link');
        const dropTwo = mobileTwoMenu.getElementsByClassName('mobile__dropdown');
        linkTwo[0].addEventListener('click', () => {
            dropTwo[0].classList.toggle('open');
            linkTwo[0].classList.toggle('open');
        })
        
        
        
        window.onscroll = function() {stickyHeader()};
        
                    var header = document.getElementById("header");
                    var sticky = header.offsetTop;
        
                    function stickyHeader() {
                                if (window.pageYOffset > sticky) {
                                    header.classList.add("sticky");
                                } else {
                                    header.classList.remove("sticky");
                                }
                    }
        
            </script>

<script async>
const testBtns = document.querySelectorAll(".test__scroll");

for (const link of testBtns) {
link.addEventListener("click", clickHandlerTest);
}

function clickHandlerTest(e) {
e.preventDefault();
const href = this.getAttribute("href");
const offsetTop = document.querySelector(href).offsetTop;

scroll({
    top: offsetTop,
    behavior: "smooth"
});
}
</script>


<script async>
const speedNowBtn = document.querySelectorAll("#speedNowBtn");

for (const link of speedNowBtn) {
link.addEventListener("click", clickHandlerTwo);
}

function clickHandlerTwo(e) {
e.preventDefault();
const href = this.getAttribute("href");
const offsetTop = document.querySelector(href).offsetTop;

scroll({
    top: offsetTop,
    behavior: "smooth"
});
}
</script>

<script async>
const featureLink = document.querySelectorAll("#featureLink");

for (const link of featureLink) {
link.addEventListener("click", clickHandlerThree);
}

function clickHandlerThree(e) {
e.preventDefault();
const href = this.getAttribute("href");
const offsetTop = document.querySelector(href).offsetTop;

scroll({
    top: offsetTop,
    behavior: "smooth"
});
}
</script>


<script async type="text/javascript" defer="defer">
  
var user_data = '';
var country = "";
var getData = $.getJSON('https://ipapi.co/json/', function(data) {  
    user_city = data.city ;
    user_country = data.country_name ;
    country = data.country;
    user_countryIso = data.country_code ;
    user_latitude = data.latitude ;
    user_longitude = data.longitude ;
    user_timeZone = data.timezone ;
    user_flag = 1 ;
    user_data = data;
    
    // console.log(country);
    if(country!="IN"){
        $(".pricing_link_link").prop("href","<?=HOST_URL?>website-speed-optimiation-cost-us.php");
    }
});


//NEW cookies 

jQuery(document).ready(function(){
        $("#btn_cookie").click(function(){
        
          var hours = new Date(new Date().setDate(new Date().getDate() + 1000)); // Reset when storage is more than 24hours
          var now = new Date();

          localStorage.setItem('setupTime', now);
          localStorage.setItem("konse_popup", true);

$("#cookieNotice").css('display', 'none');

$.ajax({
                url: 'https://websitespeedy.com/cookie_data_post.php',  
                type : "POST", 
                dataType : 'json', 
                data : getData.responseJSON,
                success : function(result) {
                    console.log(result);
                    alert('success');
                },
                error: function(xhr, resp, text) { 
                    console.log(xhr, resp, text);
                }
            });


// document.getElementById("cookieNotice").style.display = "none";
          });

 


          function check_popup_time(){
        var hours = new Date(new Date().setDate(new Date().getDate() + 7));
          var now = new Date();
          var setupTime = localStorage.getItem('setupTime');
          if(now-setupTime > hours) {
          
          localStorage.setItem('setupTime', now);
          localStorage.setItem("konse_popup", false);
          }

          var pop = localStorage.getItem("konse_popup");
          
          if(pop == 'true'){

        $("#cookieNotice").css('display', 'none');
          }
          else{
      $("#cookieNotice").css('display', 'block');
            
          }    

      }      
 check_popup_time();
 });


// End Cookies

</script>    

<script async>

    
    var mobileToggler = document.getElementById('togglerMenu');
    var menuMobile = document.getElementById('mobileNav');
    var body = document.getElementsByTagName('body');
    mobileToggler.addEventListener('click', function(){
        menuMobile.classList.toggle('active');
        mobileToggler.classList.toggle('active');
        body[0].classList.toggle('menu__open');
    })


    window.onscroll = function() {stickyHeader()};

            var header = document.getElementById("header");
            var sticky = header.offsetTop;

            function stickyHeader() {
            if (window.pageYOffset > sticky) {
                header.classList.add("sticky");
            } else {
                header.classList.remove("sticky");
            }
    }

</script>





</body>

</html>