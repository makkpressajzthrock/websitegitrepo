<?php require_once 'include/header.php';

require_once("adminpannel/config.php") ;
require_once('adminpannel/inc/functions.php') ;
?>

<!-- Plan page start -->

<!-- Card Section Start -->
<div class="plan__section" >
    <div class="section__wrapper">
        <div class="plan__section__wrapper">
            <div class="heading" >
                <h1>Choose Your Plan and<br> start Your 7 day <span>FREE Trial</span></h1>
                <h2>Improve your website loading speed by 3X and improve your conversion rate</h2>
                <p>Get started in less than 10 minutes</p>
                <div class="plan__switch">
                    <label class="switch">
                        <input type="checkbox" id="switchPrice" >
                        <span class="slider"></span>
                    </label>
                </div>
                <div class="free__tag" > 
                    <span>All Plans</span>
                    <span>Free</span>
                    <span>For 7 days</span>
                </div>
            </div>

            <div class="card__blocks" id="cardWrapper" >
                <div class="month__plan__card plan__card popular">
                    <div class="top__area">
                        <h4>Super Plan</h4>
                        <div class="price" id="pricePlan">$100 <span>/mo</span></div> 
                        <!-- <div class="popular__text" >Most Popular</div> -->
                        <div class="free__trial__btn"><a class="btn__inverse" href="#" >Get Started <i class="las la-angle-right"></i></a></div>
                    </div>
                    <div class="key__points">
                <!--         <p>One site</p>
                        <p>unlimited views</p>
                        <p>3X loading Speed*</p>
                        <p>Free installation by websiteSpeedy team</p>
                        <p>Invite team members</p>
                        <p>weekly reports - 10 URLs</p>
                        <p>Chat Support (coming soon)</p>
                        <p>Ticket support</p>
                        <p>add on - buy 5 URLs for $10 per month</p>
                        <p class="bonus">yearly plan - 2 months free</p> -->
                          <?php


                            
                            $query = $conn->query(" SELECT * FROM `plans` WHERE status = 1 and interval_plan = 'month' ") ;

                            if($query->num_rows > 0) 
                            {
                                $i = 1;
                                while($data = $query->fetch_assoc() ) 
                                {
                                    // print_r($data);
                                   
                                                            $pid=$data['id'];
                                               $query_cp = $conn->query("select * from `plans_functionality`  where plan_id=$pid") ;
                                                $data_cp = $query_cp->fetch_assoc();
                                              
                                                // print_r($data_cp); 
                                                     ?> 
                                                
                                                    <p><?php echo $data['page_view']; ?> <?php if($data_cp['line2']!=null){ ?><?php echo $data_cp['line2']; ?><?php } ?>  </p>
                                                

                                                            <?php if($data_cp['line1']!=null){ ?> <p> <?php echo $data_cp['line1']; ?></p> <?php } ?>                             
                                                 <?php if($data_cp['line3']!=null){ ?> <p> <?php echo $data_cp['line3']; ?> </p>  <?php } ?>
                                                 <?php if($data_cp['line4']!=null){  ?>  <p><?php echo $data_cp['line4']; ?></p> <?php } ?>
                                                 <?php if($data_cp['line5']!=null){  ?> <p><?php echo $data_cp['line5']; ?></p> <?php } ?>
                                                 <?php if($data_cp['line6']!=null){ ?>  <p><?php echo $data_cp['line6']; ?></p> <?php } ?>
                                                 <?php if($data_cp['line7']!=null){  ?> <p><?php echo $data_cp['line7']; ?></p> <?php } ?>
                                                  <?php if($data_cp['line8']!=null){  ?> <p><?php echo $data_cp['line8']; ?></p> <?php } ?>
                                                <?php if($data_cp['line9']!=null){   ?>  <p><?php echo $data_cp['line9']; ?></p> <?php } ?>
                                                  <?php if($data_cp['line10']!=null){   ?>  <p><?php echo $data_cp['line10']; ?></p> <?php } ?>
                                    <?php
                                   

                                    $i++;
                                }
                                
                            }
                                
                        ?>
                    </div>
                    
                </div>

                <div class="month__plan__card plan__card">
                    <div class="top__area">
                        <h4>Booster plan</h4>
                        <div class="price">$50 <span>/mo</span></div> 
                        <div class="free__trial__btn"><a href="#" class="btn__inverse" >Get Started <i class="las la-angle-right"></i></a></div>
                        <!-- <div class="popular__text" >Most Popular</div> -->
                    </div>
                    <div class="key__points">
                        <p>One site</p>
                        <p>100,000 views</p>
                        <p>3X loading Speed*</p>
                        <p>Free installation by websiteSpeedy team</p>
                        <p>Invite team members</p>
                        <p>monthly reports - 3 URLs</p>
                        <p>Chat Support (coming soon)</p>
                        <p>Ticket support</p>
                        <p>add on - buy 5 URLs for $10 per month</p>
                    </div>
                   
                </div>

                <div class="year__plan__card plan__card popular">
                    <div class="top__area">
                        <h4>Super Plan</h4>
                        <div class="price" id="pricePlan">$500 <span>/year</span></div> 
                        <div class="free__trial__btn"><a class="btn__inverse" href="#" >Get Started <i class="las la-angle-right"></i></a></div>
                        <!-- <div class="popular__text" >Most Popular</div> -->
                    </div>
                    <div class="key__points">
                        <p>One site</p>
                        <p>unlimited views</p>
                        <p>3X loading Speed*</p>
                        <p>Free installation by websiteSpeedy team</p>
                        <p>Invite team members</p>
                        <p>weekly reports - 10 URLs</p>
                        <p>Chat Support (coming soon)</p>
                        <p>Ticket support</p>
                        <p>add on - buy 5 URLs for $10 per month</p>
                        <p class="bonus">yearly plan - 2 months free</p>
                    </div>
                    
                </div>

                <div class="year__plan__card plan__card">
                    <div class="top__area">
                        <h4>Booster plan</h4>
                        <div class="price">$300 <span>/year</span></div> 
                        <div class="free__trial__btn"><a href="#" class="btn__inverse" >Get Started <i class="las la-angle-right"></i></a></div>
                        <!-- <div class="popular__text" >Most Popular</div> -->
                    </div>
                    <div class="key__points">
                        <p>One site</p>
                        <p>100,000 views</p>
                        <p>3X loading Speed*</p>
                        <p>Free installation by websiteSpeedy team</p>
                        <p>Invite team members</p>
                        <p>monthly reports - 3 URLs</p>
                        <p>Chat Support (coming soon)</p>
                        <p>Ticket support</p>
                        <p>add on - buy 5 URLs for $10 per month</p>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Card Section end -->


<!-- include section start  -->
<div class="include__section" >
    <div class="section__wrapper" >
        <div class="include__wrapper " >
            <svg class="blob__one" id="blobBackground" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg" version="1.1">
                <defs> 
                    <linearGradient id="sw-gradient" x1="0" x2="1" y1="1" y2="0">
                        <stop id="stop1" stop-color="rgba(242, 54, 64, 1)" offset="0%"></stop>
                        <stop id="stop2" stop-color="rgba(215.227, 153.181, 156.481, 1)" offset="100%"></stop>
                    </linearGradient>
                </defs>
            <path fill="url(#sw-gradient)" d="M24.5,-26.2C30.8,-18.2,34.3,-9.1,35.5,1.2C36.7,11.6,35.7,23.1,29.4,30.8C23.1,38.5,11.6,42.2,1.6,40.7C-8.4,39.1,-16.9,32.2,-23.8,24.6C-30.7,16.9,-36,8.4,-36.4,-0.4C-36.7,-9.1,-32.1,-18.3,-25.2,-26.3C-18.3,-34.2,-9.1,-41,0,-41C9.1,-41,18.2,-34.2,24.5,-26.2Z" width="100%" height="100%" transform="translate(50 50)" stroke-width="0" style="transition: all 0.3s ease 0s;" stroke="url(#sw-gradient)"></path>
          </svg>
          <svg class="blob__two" id="blobBackground2" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg" version="1.1">
            <defs> 
                <linearGradient id="sw-gradient" x1="0" x2="1" y1="1" y2="0">
                    <stop id="stop1" stop-color="rgba(242, 54, 64, 1)" offset="0%"></stop>
                    <stop id="stop2" stop-color="rgba(215.227, 153.181, 156.481, 1)" offset="100%"></stop>
                </linearGradient>
                </defs>
            <path fill="url(#sw-gradient)" d="M24.5,-26.2C30.8,-18.2,34.3,-9.1,35.5,1.2C36.7,11.6,35.7,23.1,29.4,30.8C23.1,38.5,11.6,42.2,1.6,40.7C-8.4,39.1,-16.9,32.2,-23.8,24.6C-30.7,16.9,-36,8.4,-36.4,-0.4C-36.7,-9.1,-32.1,-18.3,-25.2,-26.3C-18.3,-34.2,-9.1,-41,0,-41C9.1,-41,18.2,-34.2,24.5,-26.2Z" width="100%" height="100%" transform="translate(50 50)" stroke-width="0" style="transition: all 0.3s ease 0s;" stroke="url(#sw-gradient)"></path>
        </svg>
            <div class="heading">
                <h2>What's <span>included</span></h2>
            </div>
            <div class="include__card__wrapper" >
                <div class="include__card" >
                    <div class="img">
                        <img src="<?=HOST_URL?>assets/images/coding.gif" alt="">
                    </div>
                    <div class="text">
                        <p>Our AI Website Speedy script</p>
                    </div>
                </div>

                <div class="include__card" >
                    <div class="img">
                    <img src="<?=HOST_URL?>assets/images/repair-tools.gif" alt="">
                    </div>
                    <div class="text">
                        <p>Free Installation</p>
                    </div>
                </div>

                <div class="include__card" >
                    <div class="img">
                    <img src="<?=HOST_URL?>assets/images/checklist.gif" alt="">
                    </div>
                    <div class="text">
                        <p>Weekly or Monthly Reporting</p>
                    </div>
                </div>

                <div class="include__card" >
                    <div class="img">
                        <img src="<?=HOST_URL?>assets/images/speedometer.gif" alt="">
                    </div>
                    <div class="text">
                        <p>Know when the page speed falls</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- include section end  -->


<!-- Testimonial Section start -->

<div class="review__section" >
    <div class="section__wrapper">
        <div class="review__section__wrapper" >
            <div class="heading">
                <h2>Our <span>customers love</span> what we do</h2>
            </div>
            <div class="review__grid" >
                <div class="row__main">
                    <div data-glide-el="track" >
                        <div class="glide__slides">
                            <div class="review__card glide__slide" >
                                <div class="star">
                                    <img src="https://ecommerceseotools.com/ecommercespeedy/html/assets/images/review/star.svg" alt="" >
                                    <img src="https://ecommerceseotools.com/ecommercespeedy/html/assets/images/review/star.svg" alt="" >
                                    <img src="https://ecommerceseotools.com/ecommercespeedy/html/assets/images/review/star.svg" alt="" >
                                    <img src="https://ecommerceseotools.com/ecommercespeedy/html/assets/images/review/star.svg" alt="" >
                                    <img src="https://ecommerceseotools.com/ecommercespeedy/html/assets/images/review/star.svg" alt="" >
                                </div>
                                <div class="review__text">
                                    I was amazed by how effortless it was to use. I was able to speed up my website quickly and efficiently. I would highly recommend this system to anyone looking for a user-friendly solution
                                </div>
                                <div class="review__publisher">
                                    <div class="image">
                                        <img src="https://ecommerceseotools.com/ecommercespeedy/html/assets/images/review/review-person-1.jpg" alt="">
                                    </div>
                                    <div class="name" >
                                        John Smith
                                    </div>
                                </div>
                            </div>
                            <div class="review__card glide__slide" >
                                <div class="star">
                                    <img src="https://ecommerceseotools.com/ecommercespeedy/html/assets/images/review/star.svg" alt="" >
                                    <img src="https://ecommerceseotools.com/ecommercespeedy/html/assets/images/review/star.svg" alt="" >
                                    <img src="https://ecommerceseotools.com/ecommercespeedy/html/assets/images/review/star.svg" alt="" >
                                    <img src="https://ecommerceseotools.com/ecommercespeedy/html/assets/images/review/star.svg" alt="" >
                                    <img src="https://ecommerceseotools.com/ecommercespeedy/html/assets/images/review/star.svg" alt="" >
                                </div>
                                <div class="review__text">
                                    Website Speedy has been a lifesaver for my website. We love that it provides us with a quick and easy way to manage and speed up our website. We've seen a major improvement in our website's speed and efficiency
                                </div>
                                <div class="review__publisher">
                                    <div class="image">
                                        <img src="https://ecommerceseotools.com/ecommercespeedy/html/assets/images/review/review-person-2.jpg" alt="">
                                    </div>
                                    <div class="name" >
                                        Samantha Jones
                                    </div>
                                </div>
                       