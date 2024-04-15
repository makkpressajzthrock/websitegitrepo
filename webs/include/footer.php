
<!-- Test Website section start -->
<span id="test__section"></span>
<div class="website__test__section" >
    <div class="section__wrapper">
        <div class="website__test__wrapper">
            <div class="heading">
                <h2>Test your <span>Website</span> Speed now</h2>
                <p>You can quickly analyze your site just by entering the site address in the input below! Find you current speed with Google page insights and instantly fix it to grow your sales and Conversion rate!</p>
            </div>
            <div class="form__wrapper">
                <form id='index_form'>
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
    if($url__con == HOST_URL."all-platforms-new.php/"){
    }else{
        if ($url__con == HOST_URL.'blogs/' || $url__con == HOST_URL.'case-study/') {
            include '../mail-footer.php';
        }else {
            include './mail-footer.php';
        }
    }

    

  ?>  




<!-- Footer Start -->

<footer >
    <div class="section__wrapper">
        <div class="footer__wrapper">
            <div class="f-grid about">
                <div class="logo">
                    <img loading="lazy" width="225" height="68" src="<?=$bunny_image?>website_speedy_logo_21.svg" alt="Website Speedy Logo">
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
                    <h2>Speed Optimization</h2>
                </div>
                <ul>
                    <li><a href="/shopify-speed-optimization.php">Shopify Speed Optimization</a></li>
                    <li><a href="/wix-editor-x-speed-optimization.php">WIX | Editor X Speed Optimization</a></li>
                    <li><a href="/bigcommerce-speed-optimization.php">Bigcommerce Speed Optimization</a></li>
                    <li><a href="/squarespace-speed-optimization.php">Squarespace Speed Optimization</a></li>
                    <li><a href="/shift4shop-speed-optimization.php">Shift4Shop Speed Optimization</a></li>
                    <li><a href="/weebly-speed-optimization.php">Weebly Speed Optimization</a></li>
                    <li><a href="/duda-speed-optimization.php">Duda Speed Optimization</a></li>
                    <li><a href="/signup.php">Custom Website Speed Optimization</a></li>
                    <li><a href="/signup.php">Saas Speed Optimization</a></li>
                </ul>
         
            </div>

            <div class="f-grid">
                <div class="footer__sec__heading">
                    <h2>Fix Core web Vitals</h2>
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
                    <h2>Legal</h2>
                </div>
                <ul>
                    <li><a href="/privacy-policy.php">Privacy Policy</a></li>
                    <li><a href="/cookie-policy.php">Cookie Policy</a></li>
                    <li><a href="/terms-of-use.php">Terms Of Use</a></li>
                    <li><a href="/cancellation-refund-policy.php">Cancellation and Refund Policy</a></li>


                </ul>

                <div class="footer__sec__heading">
                    <h2>Partnership Programs</h2>
                </div>
                <ul>

                    <li><a class="FooterAgencyLink" href="/agency-partner.php">Agency Partner</a></li>
                    <li><a class="AffiliateProgramLink" style="display: none;"  href="https://www.shareasale.com/shareasale.cfm?merchantID=144859" target="_blank">Affiliate Program</a></li>
                    <li><a class="newPageLink" href="/about-us.php#gotoText">Investor</a></li>
                </ul>

            </div>

            <div class="f-grid">
                <div class="footer__sec__heading">
                    <h2>Pages</h2>
                </div>
                <ul>
                    <li><a href="/about-us.php">About Us</a></li>
                    <li><a href="/why-website-speed-matters.php">Why Speed Matters</a></li>
                    <li><a href="/speed-guarantee.php">Speed Guarantee</a></li>
                    <li><a href="/website-speed-optimiation-cost.php" class="pricing_link_link" >Pricing</a></li>
                    <li><a href="<?=HOST_HELP_URL?>faqs" target="_blank">FAQ</a></li>
                    <li><a href="/blogs.php">Blogs</a></li>
                    <li><a href="/case-study.php">Case Study</a></li>
                    <li><a href="/contact-us.php">Contact Us</a></li>
                    <li><a href="<?=HOST_HELP_URL?>" target="_blank">Knowledge Base</a></li>
                    <li><a class="newPageLink" href="/platforms.php">Platforms</a></li>
                </ul>
            </div>

            


            <div class="trust__icons" >
            <a href="https://www.producthunt.com/posts/website-speedy?utm_source=badge-featured&utm_medium=badge&utm_souce=badge-website&#0045;speedy" target="_blank"><img src="<?=$bunny_image?>featured.svg" alt="WebsiteSpeedy - Instantly reduce your website loading time by 3x | Product Hunt" style="width: 250px; height: 54px;" width="250" height="54"></a>
             <img height="48" width="170" src="<?=$bunny_image?>stripe-badge-transparent.webp" alt="strip logo">   
            </div>
        </div>

        

        <div class="footer__bottom">

            <div class="social__links" > 
                <a class="facebook__link" href="https://www.facebook.com/websitespeedy" aria-label="Facebook" target="_blank"><span style="display:none" >facebook</span></a>
                <a class="insta__link" href="https://www.instagram.com/websitespeedy/" aria-label="Instagram" target="_blank" ><span style="display:none" >insta</span></a>
                <a class="linkedin__link" href="https://www.linkedin.com/company/websitespeedy/" aria-label="Linkedin" target="_blank"><span style="display:none" >linkedin</span></a>
                <a class="youtube__link" href="https://www.youtube.com/channel/UC044W4qzCU9wiF1DJhl3puA" aria-label="Youtube" target="_blank"><span style="display:none" >youtube</span></a>
            </div>

            <div class="copyright">
                © 2024 Website Speedy By <a href="https://makkpress.com/" target="_blank" >MakkPress</a>. All rights reserved
            </div>

        </div>
    </div>
</footer>




<!-- Footer Start -->


<script >

$( document ).ready(function() {

    setTimeout(() => {

        $('.slider-slick-before-afer').slick({
        dots: false,
        infinite: true,
        autoplay:true,
        autoplaySpeed:3000,
        speed: 1000,
        slidesToShow: 1,
        slidesToScroll: 1,
        pauseOnHover:true
        });
        
        
        $('.cwv__page__slick').slick({
        dots: false,
        infinite: true,
        autoplay:true,
        autoplaySpeed:9000,
        speed: 1000,
        slidesToShow: 1,
        slidesToScroll: 1,
        pauseOnHover:true
        });
        
        $('.review__slider').slick({
        dots: true,
        arrows:false,
        infinite: true,
        autoplay:true,
        autoplaySpeed:3000,
        speed: 1000,
        slidesToShow: 1,
        slidesToScroll: 1,
        pauseOnHover:true,
        responsive: [
                {
                breakpoint: 768,
                settings: {
                    dots: false
                }
                }
            ]
        });

        $('.coll_grid').slick({
            dots: false,
            infinite: false,
            arrows: true,
            speed: 300,
            slidesToShow: 4,
            slidesToScroll: 1,
            responsive: [
                {
                breakpoint: 1200,
                settings: {
                    slidesToShow: 2,
                }
                },
                {
                breakpoint: 768,
                settings: {
                    slidesToShow: 1,
                }
                }
            ]
        });
        
        
        $('.facts__slides').slick({
        dots: false,
        arrows:false,
        infinite: true,
        autoplay:true,
        autoplaySpeed:3000,
        speed: 1000,
        slidesToShow: 1,
        slidesToScroll: 1,
        pauseOnHover:true
        });
        
        $(document).ready(function() {
                if($(window).width() < 767 ) {
                    $('.partners__grid').slick({
                        infinite: true,
                        slidesToShow: 2,
                        slidesToScroll: 1,
                        dots:false,
                        arrows:false,
                        autoplay: true,
                        autoplaySpeed: 1500
                    });
                    
                    $('.logo__container').slick({
                        infinite: true,
                        slidesToShow: 2,
                        slidesToScroll: 1,
                        dots:false,
                        arrows:false,
                        autoplay: true,
                        autoplaySpeed: 1500
                    });
                }

                if($(window).width() < 1024 ) {
                    $('.platform-new .logo__container').slick({
                        infinite: true,
                        slidesToShow: 3,
                        slidesToScroll: 1,
                        dots:false,
                        arrows:false,
                        autoplay: true,
                        autoplaySpeed: 1500,
                        responsive: [
                            {
                            breakpoint: 768,
                            settings: {
                                slidesToShow: 2,
                            }
                            }
                        ]
                    });
                }
        });

        // Average Bounce Rate slider
        $('.img-slider-wrap').slick({
            dots: false,
            infinite: false,
            speed: 300,
            slidesToShow: 1,
            slidesToScroll: 1,
        }); 

        $('.client-case-slider-wrap').slick({
            dots: false,
            infinite: false,
            arrows: true,
            speed: 300,
            slidesToShow: 4,
            slidesToScroll: 1,
            responsive: [
                {
                breakpoint: 1024,
                settings: {
                    slidesToShow: 3,
                }
                },
                {
                breakpoint: 767,
                settings: {
                    slidesToShow: 2,
                }
                },
                {
                breakpoint: 480,
                settings: {
                    slidesToShow: 1,
                }
                }
            ]
        });

        $('.testimonial-reviews-slider').slick({
            dots: false,
            centerMode: true,
            infinite: true,
            arrows: true,
            speed: 300,
            slidesToShow: 3,
            slidesToScroll: 1,
            autoplay: true,
            autoplaySpeed: 2000,
            swipe: true,
            swipeToSlide: true,
            responsive: [
                {
                breakpoint: 991,
                settings: {
                    slidesToShow: 1,
                }
                }
            ]
        });

    }, 2000);
        
})
       
</script>



<script src="<?=$bunny_js?>slick.min.js" type="text/javascript" defer="defer"></scrip>
<script src="<?=$bunny_js?>app.js" defer="defer"></script>



<script async>



       
        const megaMenu = document.getElementById('megaMenuOne');
const megaMainLink = megaMenu.getElementsByClassName('link__menu');
const innerMegaMenu = megaMenu.getElementsByClassName('megamenu');


megaMainLink[0].addEventListener('mouseover', () => {
    // innerMegaMenu[0].style.display = "block";
    megaMainLink[0].classList.add('rotate');
    megaMenu.classList.add('anim-menu');
})
megaMainLink[0].addEventListener('mouseout', () => {
    // innerMegaMenu[0].style.display = "none";
    megaMainLink[0].classList.remove('rotate');
    megaMenu.classList.remove('anim-menu');
})
innerMegaMenu[0].addEventListener('mouseover', () => {
    // innerMegaMenu[0].style.display = "block";
    megaMainLink[0].classList.add('rotate');
    megaMenu.classList.add('anim-menu');
})
innerMegaMenu[0].addEventListener('mouseout', () => {
    // innerMegaMenu[0].style.display = "none";
    megaMainLink[0].classList.remove('rotate');
    megaMenu.classList.remove('anim-menu');
})

const dropDown = document.getElementById('dropdownMenuOne');
const dropDownLink = dropDown.getElementsByClassName('link__menu');
const innerDropDownMenu = dropDown.getElementsByClassName('dropdown');

dropDownLink[0].addEventListener('mouseover', () => {
    // innerDropDownMenu[0].style.display = "block";
    dropDownLink[0].classList.add('rotate');
    dropDown.classList.add('anim-menu');
})
dropDownLink[0].addEventListener('mouseout', () => {
    // innerDropDownMenu[0].style.display = "none"
    dropDownLink[0].classList.remove('rotate');
    dropDown.classList.remove('anim-menu');
})
innerDropDownMenu[0].addEventListener('mouseover', () => {
    // innerDropDownMenu[0].style.display = "block";
    dropDownLink[0].classList.add('rotate');
    dropDown.classList.add('anim-menu');
})
innerDropDownMenu[0].addEventListener('mouseout', () => {
    // innerDropDownMenu[0].style.display = "none";
    dropDownLink[0].classList.remove('rotate');
    dropDown.classList.remove('anim-menu');
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
if(mobileTwoMenu) {
    const linkTwo = mobileTwoMenu.getElementsByClassName('menu__link');
    const dropTwo = mobileTwoMenu.getElementsByClassName('mobile__dropdown');
    linkTwo[0].addEventListener('click', () => {
        dropTwo[0].classList.toggle('open');
        linkTwo[0].classList.toggle('open');
    })
}



const mobileTwoMenu_new = document.getElementById('mobileDropDownOne_new');
if(mobileTwoMenu_new) {
    const linkTwo_new = mobileTwoMenu_new.getElementsByClassName('menu__link');
    const dropTwo_new = mobileTwoMenu_new.getElementsByClassName('mobile__dropdown');
    if(linkTwo_new) {
    linkTwo_new[0].addEventListener('click', () => {
            dropTwo_new[0].classList.toggle('open');
            linkTwo_new[0].classList.toggle('open');
        })
    }
}




// sub menu child






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



<script defer >

    const formCwv2 = document.getElementById('form__cwv');
    if(formCwv2){
        formCwv2.addEventListener('submit', (e) => {
        if (formCwv2.length >= 1) {
            e.preventDefault();
            window.location.href="<?=HOST_URL?>signup.php"; 
        }
        
    })
    }
    

    const formCwv3 = document.getElementById('form__cwv__two');
    if(formCwv3) {
        formCwv3.addEventListener('submit', (e) => {
        if (formCwv3.length >= 1) {
            e.preventDefault();
            window.location.href="<?=HOST_URL?>signup.php"; 
        }
        
    })
    }


    
    document.addEventListener('DOMContentLoaded', function() {

        if(window.location.href === '<?=HOST_URL?>') {

        } else {
            // function clickElementsLoop() {
            // const elements = document.querySelectorAll('.stepBtn');
            // let index = 0;
            // if(elements) {
            //     setInterval(() => {
            //     elements[index].click();
            //     index = (index + 1) % elements.length;
            // }, 3000); // 3000 milliseconds = 3 seconds
            // }
            // }
            // clickElementsLoop();

            const stepBtn = document.getElementsByClassName('stepBtn')

            const stepText = document.getElementsByClassName('stepText');

            for (let i = 0; i < stepBtn.length; i++) {
                stepBtn[i].addEventListener('click', ()=> {
                2        
                        for (let j = 0; j < stepText.length; j++) {
                            stepText[j].classList.add('hide');
                            stepBtn[j].classList.remove('animate');
                        }
                        stepText[i].classList.toggle('hide'); 
                        stepBtn[i].classList.toggle('animate')
                })
            }
        }

    })
</script>

<script defer>
    
const wrapFaq = document.querySelectorAll('.faq__wrapper')
const ans = document.querySelectorAll('.ans');
const que = document.querySelectorAll('.que');
for (let i = 0; i < wrapFaq.length; i++) {
    wrapFaq[i].addEventListener('click', () => {
        que[i].classList.toggle('rot')
        ans[i].classList.toggle('open');
    })
    
};
</script>


<script async>
    document.addEventListener('DOMContentLoaded', function() {
        const urlPage = window.location.href;
        if (urlPage === '<?=HOST_URL?>contact-us.php' || urlPage === '<?=HOST_URL?>agency-partner.php') {
            const footerForm = document.getElementsByClassName('contact__form__only');
            footerForm[0].remove();
        }
    })
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
        // $(".FooterAgencyLink").remove();
        $(".AffiliateProgramLink").show();
    }
    else{
        $(".AffiliateProgramLink").remove();
        // $(".FooterAgencyLink").show();
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
                url: '<?=HOST_URL?>cookie_data_post.php',  
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
            var header_1 = document.getElementById("sticky-header");
            var sticky = header.offsetTop;

            function stickyHeader() {
            if (window.pageYOffset > sticky) {
                header.classList.add("sticky");
                header_1.classList.add("sticky");
                
            } else {
                header.classList.remove("sticky");
                header_1.classList.remove("sticky");
            }
    }

    // sub menu 
    let subMenuBox = document.getElementById('sub_menu_box');
    let subMenuLink = subMenuBox.querySelector('#sub_menu_link');
    subMenuLink.addEventListener('click', () => {
        subMenuBox.classList.toggle('open');
    })
    
    let subMenuBox2 = document.getElementById('sub_menu_box2');
    let subMenuLink2 = subMenuBox2.querySelector('#sub_menu_link2');
    subMenuLink2.addEventListener('click', () => {
        subMenuBox2.classList.toggle('open');
    })


    
</script>


<script>

    if(window.location.href === '<?=HOST_URL?>website-speed-optimiation-cost.php' || window.location.href === '<?=HOST_URL?>website-speed-optimiation-cost-us.php') {
        document.querySelector('.website__test__section').remove();
        document.querySelector('.contact__form__only').remove();
    }

    
</script>

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



</body>

</html>