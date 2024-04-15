<?php require_once 'include/header.php';

require_once("adminpannel/config.php") ;
require_once('adminpannel/inc/functions.php') ;
?>
<style>
   .exclusive__strip {
   display: none;
   }
   @font-face {
   font-family: 'ambit';
   src: url('https://websitespeedycdn.b-cdn.net/speedyweb/font/ambit-bold-webfont.woff2') format('woff2'),
   url('https://websitespeedycdn.b-cdn.net/speedyweb/font/ambit-bold-webfont.woff') format('woff');
   font-weight: normal;
   font-style: normal;
   font-weight: 700;
   }
   @font-face {
   font-family: 'ambit';
   src: url('https://websitespeedycdn.b-cdn.net/speedyweb/font/ambit-regular-webfont.woff2') format('woff2'),
   url('https://websitespeedycdn.b-cdn.net/speedyweb/font/ambit-regular-webfont.woff') format('woff');
   font-weight: normal;
   font-style: normal;
   font-weight: 400;
   }
   @font-face {
   font-family: 'ambit';
   src: url('https://websitespeedycdn.b-cdn.net/speedyweb/font/ambit-semibold-webfont.woff2') format('woff2'),
   url('https://websitespeedycdn.b-cdn.net/speedyweb/font/ambit-semibold-webfont.woff') format('woff');
   font-weight: normal;
   font-style: normal;
   font-weight: 600;
   }
   body .plan__section * {
   /* font-family: 'ambit' !important; */
   }
   .plan_new .switch {
   width: auto;
   height: 50px;
   }
   .plan_new .slider {
   display: flex;
   align-items: center;
   justify-content: space-between;
   color: #fff;
   font-weight: 600;
   padding: 6px;
   width: fit-content;
   gap: 15px;
   position: relative;
   width: 100%;
   height: 100%;
   background: #f23640;
   }
   .plan_new .slider:before {
   content: none;
   }
   .plan_new .slider span {
   position: relative;
   z-index: 1;
   color: #333;
   line-height: 1;
   }
   .plan_new input+.slider span {
   padding: 12px 24px;
   border-radius: 20px;
   }
   .plan_new input+.slider .monthly,
   .plan_new input:checked+.slider .yearly {
   background: #fff;
   color: #333;
   padding: 12px 24px 10px;
   }
   .plan_new input+.slider .yearly,
   .plan_new input:checked+.slider .monthly {
   background: none;
   color: #fff;
   }
   .plan_new .switch input {
   position: absolute;
   }
   .new__design .plan_new .plan__card.popular {
   scale: 1;
   }
   .plan_new .new__design.card__blocks {
   padding-top: 0;
   }
   .plan_new .plan__card .top__area .price {
   color: #333;
   }
   .new__design .plan_new .actual__price::before {
   content:none !important;
   }
   .plan_new .new__design.card__blocks .plan__card .key__points {
   background: none !important;
   gap:0;
   }
   .plan_new .plan__card .key__points p {
   color: #333 !important;
   }
   .new__design .plan_new .top__area .get__started__btn a {
   background-color: #f23640 !important;
   box-shadow: none !important;
   }
   .new__design .plan_new .plan__card.popular {
   box-shadow: rgba(0, 0, 0, 0.15) 0 5px 15px 0;
   }
   .new__design .plan_new .actual__price {
   color: #515151;
   font-weight: normal;
   font-size: 20px;
   }
   .new__design .plan_new .plan__card .top__area .price {
   min-height: auto;
   padding-left: 0 !important;
   left: 0;
   margin-top: 30px;
   gap: 0;
   }
   .plan_new .new__design.card__blocks .plan__card .key__points {
   padding: 40px 18px 50px;
   }
   .plan__section.new__design.new__design .plan__card .key__points p:nth-child(1) span:nth-child(1),
   .plan__section.new__design .new__design .plan__card .key__points p:nth-child(2) span.first__line,
   .plan__section.new__design .new__design .plan__card .key__points p:nth-child(3) span.first__line {
   font-size: 14px;
   }
   .plan_new .new__design.card__blocks a.btn__inverse {
   height: 40px;
   font-size: 15px;
   transition: all 200ms ease;
   display: flex;
   align-items: center;
   justify-content: center;
   order:2;
   }
   .plan_new .plan__card .key__points p::before {
   filter: brightness(0);
   }
   body .new__design .plan_new .discount__price {
   font-size: 40px;
   font-weight: 600;
   line-height: 1.2;
   letter-spacing: normal !important;
   }
   .plan__section.new__design .plan_new .new__design .plan__card .key__points p:nth-child(1),
   .plan__section.new__design .plan_new .new__design .plan__card .key__points p:nth-child(2),
   .plan__section.new__design .plan_new .new__design .plan__card .key__points p:nth-child(3) {
   display: flex;
   flex-direction: row;
   justify-content: left;
   align-items: baseline;
   }
   .plan__section.new__design .plan_new .new__design .plan__card .key__points p:nth-child(1){    display:none;
   }
   .plan__section.new__design.new__design .plan_new .plan__card .key__points p:nth-child(1) span:nth-child(2) {
   text-transform: lowercase;
   }
   .plan__section.new__design.new__design .plan_new .plan__card .key__points p:nth-child(1) span:nth-child(1),
   .plan__section.new__design.new__design .plan_new .plan__card .key__points p:nth-child(1) span:nth-child(2) {
   width: fit-content;
   }
   .text__hidden {
   line-height: 1.5 !important;
   z-index: 99;
   }
   .new__design .plan_new .q__icon {
   font-size: 14px !important;
   width: 20px;
   height: 20px;
   position: absolute;
   right: 0;
   background: #f23640;
   font-size: 14px;
   line-height: 1;
   display: flex;
   align-items: center;
   justify-content: center;
   color: #fff;
   border-radius: 22px;
   cursor: pointer;
   top: 50%;
   transform: translateY(-50%);
   }
   .plan_new .key__points .get__started__btn {
   display: none;
   }
   .plan_new .new__design.card__blocks .plan__card .key__points {
   height: auto !important;
   }
   .plan_new .get__started__btn .btn__inverse::after {
   content: none;
   }
   .plan__section.new__design.plan_new_s::after {
   content: none;
   }
   .inr_cur {
   text-transform: uppercase;
   display: inline-block;
   margin-right: 4px;
   }
   .btn__inverse::after{
   content:none;
   }
   .new__design .plan__card {
   overflow: visible;
   box-shadow:rgb(0 0 0 / 37%) 0px 0px 2px 0px !important;
   }
   .new__design .plan_new .plan__card.popular .popular__text {
   top: -12px !important;
   border-radius: 8px;
   line-height: 1;
   right: 15px;
   background: #333;
   color: #fff;
   padding: 8px 10px 4px;
   text-transform: uppercase;
   font-weight: normal;
   font-size: 14px;
   }
   .plan__card .top__area {
   padding: 35px 18px 30px !important;
   gap: 0;
   }
   .new__design .plan_new .plan__card .top__area h4 {
   position: relative;
   display: flex;
   flex-direction: column;
   padding-right: 10px;
   line-height: 1.2;
   gap: 7px;
   text-align: left;
   margin: 0;
   padding: 0;
   color: #333;
   top: 0 !important;
   font-size: 32px;
   text-transform: capitalize;
   }
   .new__design.plan_new_s .plan__card .key__points p {
   font-size: 16px;
   padding: 0 0 6px 0 !important;
   }
   .plan__card .key__points p::before {
   content: none;
   }
   .card__blocks .month__plan__card:nth-child(4),
   .card__blocks.year .year__plan__card:nth-child(8) {
   order: 1;
   }
   .card__blocks .month__plan__card:nth-child(2),
   .card__blocks.year .year__plan__card:nth-child(6) {
   order: 4;
   }
   .card__blocks .month__plan__card:nth-child(1),
   .card__blocks.year .year__plan__card:nth-child(5) {
   order: 3;
   }
   .card__blocks .month__plan__card:nth-child(3),
   .card__blocks.year .year__plan__card:nth-child(7) {
   order: 2;
   }
   .new__design .actual__price del {
   text-decoration: line-through !important;
   }
   .sp_first {
   color: #333;
   font-size: 14px;
   font-weight: 600;
   order: 3;
   margin-top: 24px;
   position: relative;
   width: 100%;
   margin-bottom: 0;
   min-height: 40.73px;
   vertical-align: middle;
   display: flex;
   align-items: center;
   padding-right:25px;
   }
   p.sub_hs {
   order: 2;
   margin: 5px  0 0;
   font-weight: 500;
   color: #515151;
   line-height: 1.4;
   min-height: 45px;
   }
   .new__design.plan_new_s .plan__card .key__points p br {
   display: none;
   }
   .slide_btn {
   display: none;
   color: #333;
   justify-content: space-between;
   font-weight: 600;
   font-size: 16px;
   padding: 24px;
   border-top: 1px solid #00000014;
   cursor: pointer;
   }
   .text__hidden {
   line-height: 1.3;
   font-size: 14px;
   font-weight: 600;
   }
   .trust-factor-tem-new {
   padding: 15px;
   margin-top: 50px;
   box-shadow: rgba(60, 64, 67, 0.3) 0px 1px 2px 0px, rgba(60, 64, 67, 0.15) 0px 2px 6px 2px;
   border-radius: 8px;
   }
   .trust-factor-icon {
   width: 60px;
   margin: 0;
   border: 1px solid #ec363b8c;
   padding: 15px;
   border-radius: 4px;
   }
   .trust-factor-desc {
   text-align: left;
   margin-top: 15px;
   color: #333;
   font-size: 18px;
   font-weight: 600;
   }
   .trust-factor-text {
   text-align: left;
   margin-top: 15px;
   color: #333;
   font-size: 15px;
   margin: 8px 0 15px 0;
   }
   .plan__section .new__design.plan_new_s .plan__card .key__points p::before {
   position: absolute;
   width: 16px;
   height: 16px;
   top: 50%;
   left: 10px;
   transform: translateY(-50%);
   background-size: 100%;
   background-repeat: no-repeat;
   background-position: center;
   }
   .plan__section.new__design.plan_new_s .plan__card .key__points p:nth-child(6)::before {
   content: " ";
   background-image: url(https://websitespeedycdn.b-cdn.net/speedyweb/images/pageSpeed_insights.png);
   }
   .plan__section.new__design.plan_new_s .plan__card .key__points p:nth-child(7)::before {
   content: " ";
   background-image: url(https://websitespeedycdn.b-cdn.net/speedyweb/images/gt_matrix.png);
   }
   .plan__section.new__design.plan_new_s .plan_new .plan__card .key__points p::before {
   filter: none;
   left: 6px;
   top: 50%;
   transform: translateY(-50%);
   padding:0;
   }
   .new__design.plan_new_s .plan__card .key__points p:not(:last-child) {
   border-bottom: 1px solid #00000014;
   }
   .new__design.plan_new_s .plan__card .key__points p:nth-last-child(2) {
   border:none;
   }
   .plan__section.new__design.plan_new_s .plan__card .key__points p {
   font-size: 14px;
   padding: 8px 0 !important;
   }
   .new__design.plan_new_s .plan__card .key__points p:nth-child(6), 
   .new__design.plan_new_s .plan__card .key__points p:nth-child(7) {
   padding-left: 30px !important;
   }
   .new__design.plan_new_s .plan__card .key__points p:last-child, 
   .new__design.plan_new_s .plan__card .key__points p:nth-child(6), 
   .new__design.plan_new_s .plan__card .key__points p:nth-child() {
   border:none;
   }
   .selectPricing {
   border-radius: 5px;
   width: 100%;
   padding: 8px;
   cursor: pointer;
   background: #edf1fd;
   border: 1px solid #333;
   outline: none;
   max-width: 375px;
   font-size: 16px;
   min-height:40.8px;
   text-transform: capitalize;
   }
   .pr_sd{
   width: 100%;
   font-weight: 500;
   color: #212b36;
   row-gap: 24px;
   column-gap: 15px;
   display: flex;
   flex-wrap: wrap;
   order: 2;
   }
   .card__plan .card__plan__button {
   width: calc(50% - 8px);
   max-width: 180px;
   background: #f23640;
   border-color: #f23640;
   color: #fff;
   }
   .new__design .top__area .get__started__btn {
   bottom: 0;
   padding: 0;
   }
   .subscribe_free {
   z-index: 9;
   width: 100%;
   order: 4;
   position: relative;
   top: 24px;
   }
   .sub_free {
   width: 100%;
   font-weight: 500;
   color: #212b36;
   row-gap: 24px;
   column-gap: 15px;
   display: flex;
   flex-wrap: wrap;
   }
   .card__blocks .month__plan__card:nth-child(2) .sp_first,
   .card__blocks.year .year__plan__card:nth-child(6) .sp_first{
   display:none;
   }
   .card__plan__button.sales.contactSalesBtn,
   .plan_new .new__design.card__blocks a.btn__inverse,
   .plan_new .new__design.card__blocks a.card__plan__button {
   width: calc(50% - 8px) !important;
   max-width: 180px;
   min-width:auto;
   background: #f23640;
   color: #fff;
   border-radius: 4px;
   padding: 6px 16px !important;
   text-transform: capitalize;
   transition: all 250ms ease;
   text-align: center;
   text-decoration: none;
   cursor: pointer;
   font-weight:600;
   }
   .plan_new .new__design.card__blocks a.btn__inverse:hover,
   .plan_new .new__design.card__blocks a.card__plan__button:hover {
   background: #333;
   border-color: #333;
   }
   .card__plan__button.sales.contactSalesBtn {
   text-align: left;
   bottom: auto;
   height:40px;
   order: 3;
   background: none !important;
   color: #333 !important;
   border-color: #333 !important;
   border: 2px solid;
   font-weight: 500;
   line-height: 1;
   display: flex;
   align-items: center;
   justify-content: center;
   }
   .card__plan__button.sales.contactSalesBtn:hover{
   background: #333 !important;
   color:#fff !important;
   }
   span.usd_nn {
   text-transform: uppercase;
   margin-right: 4px;
   }
   .new__design .plan_new .plan__card .top__area h4+.price {
   margin-top: 80px;
   }
   .actual__price span, .discount__price span:nth-child(2), .year__plan__card .actual__price span, .year__plan__card .discount__price span:nth-child(2) {
   text-transform: capitalize;
   }
   .card__blocks .month__plan__card:nth-child(4) .top__area .btn__inverse, 
   .card__blocks.year .year__plan__card:nth-child(8) .top__area .btn__inverse {
   background: none !important;
   color: #333;
   transition: all 250ms ease;
   border: 2px solid #333;
   }
   .card__blocks .month__plan__card:nth-child(4) .top__area .btn__inverse:hover, 
   .card__blocks.year .year__plan__card:nth-child(8) .top__area .btn__inverse:hover {
   outline: 1px solid #f23640;
   border-color: #f23640;
   color:#333;
   }
   .card__blocks .month__plan__card:nth-child(2) .actual__price del, 
   .card__blocks.year .year__plan__card:nth-child(6) .actual__price del {
   display: none;
   }

   @media (min-width:991px) and (max-width:1415px) {

.card__plan__button.sales.contactSalesBtn, .plan_new .new__design.card__blocks a.btn__inverse, .plan_new .new__design.card__blocks a.card__plan__button {
    padding: 6px 6px !important;
 
}
   }
   @media (min-width:991px) and (max-width:1255px) {
    .plan__card .top__area {
    padding: 35px 10px 30px !important;
}
.plan_new .new__design.card__blocks .plan__card .key__points {
    padding: 40px 10px 50px;
}
   }

   @media (min-width:991px) and (max-width:1190px) {

    .sub_free {
    column-gap: 8px;
  
}
.plan_new .new__design.card__blocks a.btn__inverse,
.card__plan__button.sales.contactSalesBtn {
    height: 35px;
}
.card__plan__button.sales.contactSalesBtn, .plan_new .new__design.card__blocks a.btn__inverse, .plan_new .new__design.card__blocks a.card__plan__button {
    border-radius: 4px;

   }
   .card__plan__button.sales.contactSalesBtn, .plan_new .new__design.card__blocks a.btn__inverse, .plan_new .new__design.card__blocks a.card__plan__button {
    font-size: 14px;
    font-weight: 700;
}
.card__plan__button.sales.contactSalesBtn, .plan_new .new__design.card__blocks a.btn__inverse, .plan_new .new__design.card__blocks a.card__plan__button {
    width: calc(50% - 4px) !important;

}
   }
   @media (min-width:991px) and (max-width:1160px) {

    .card__plan__button.sales.contactSalesBtn, .plan_new .new__design.card__blocks a.btn__inverse, .plan_new .new__design.card__blocks a.card__plan__button {
    padding: 6px 2px !important;

}


   }
   @media (min-width:991px) and (max-width:1095px) {


    .card__plan__button.sales.contactSalesBtn, .plan_new .new__design.card__blocks a.btn__inverse, .plan_new .new__design.card__blocks a.card__plan__button {
    font-size: 12px;
}

   }

   @media(max-width:991px) {
   .slide_btn {
   display: block;
   }
   .subscribe_free {
   top: 0;
   margin-top: 24px;
   }
   .key_wrap {
   display: none;
   }
   .card__blocks .plan__card {
   width: 100%;
   max-width: 100% !important;
   }
   .plan__card .top__area {
   border-bottom: 1px solid #00000014;
   }
   .flx_con {
   display: flex;
   align-items: center;
   justify-content: space-between;
   }
   .flx_con svg path {
   stroke: #333;
   }
   .slide_btn svg {
   transition: all 200ms ease;
   }
   .slide_btn.open_key svg {
   rotate: -180deg;
   }
   .new__design.card__blocks {
   gap: 30px;
   }
   .plan_new .new__design.card__blocks .plan__card .key__points {
   padding: 50px 24px 20px;
   }
   }
   @media(max-width:480px) {
   .new__design.card__blocks a.btn__inverse {
   min-width: 220px;
   }
   .new__design .plan__card .key__points {
   padding: 40px 25px 35px !important;
   }
   .plan_new .new__design.card__blocks {
   padding-top: 20px;
   }
   .new__design.card__blocks .plan__card .key__points {
   margin-top: -30px;
   }
   }
</style>
<!-- Plan page start -->

<div class="exclusive__strip" >
    <p>Exclusive Launch Deal for First 1000 Users, only a few left!</p>
    <a class="btn" href="<?=HOST_URL?>signup.php">Start FREE Trial</a>
</div>

<!-- Card Section Start -->
<div class="plan__section new__design plan_new_s india" >
    <div class="section__wrapper">
        <div class="plan__section__wrapper plan_new">
            <div class="heading">
                <h1>Speed Is Not Just a Feature, <br><span>It's a Foundation</span></h1>
                <h2>Websites That Load Faster Excel in SEO, Elevate User Experience,<br> and Optimize Ad Spend: Transform Your Site's Performance Now!</h2>

                <div class="plan__switch">
                    <label class="switch">
                        <input type="checkbox" id="switchPrice" checked>
                        <div class="slider"><span class="monthly">Monthly</span><span class="yearly">Yearly</span></div>
                    </label>
                    <div class="floating__other__text" >
                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" width="256" height="256" viewBox="0 0 256 256" xml:space="preserve" >

                    <defs>
                    </defs>
                    <g style="stroke: none; stroke-width: 0; stroke-dasharray: none; stroke-linecap: butt; stroke-linejoin: miter; stroke-miterlimit: 10; fill: none; fill-rule: nonzero; opacity: 1;" transform="translate(1.4065934065934016 1.4065934065934016) scale(2.81 2.81)">
                        <path d="M 9.763 73.891 c 0.049 0.007 0.095 0.029 0.145 0.029 c 0.029 0 0.055 -0.014 0.084 -0.017 c 0.038 -0.001 0.073 0.012 0.111 0.006 c 0.064 -0.009 0.117 -0.042 0.176 -0.063 c 0.007 -0.003 0.013 -0.004 0.019 -0.007 c 0.21 -0.078 0.383 -0.211 0.498 -0.393 l 8.733 -8.734 c 0.391 -0.391 0.391 -1.023 0 -1.414 s -1.023 -0.391 -1.414 0 l -7.41 7.411 c -1.64 -15.449 4.181 -28.442 16.193 -35.847 c 0.471 -0.29 0.617 -0.906 0.327 -1.376 s -0.906 -0.616 -1.376 -0.326 C 14.547 40.125 8.41 51.72 8.41 65.636 c 0 1.505 0.08 3.04 0.225 4.596 l -6.934 -6.934 c -0.391 -0.391 -1.023 -0.391 -1.414 0 c -0.195 0.195 -0.293 0.451 -0.293 0.707 c 0 0.256 0.098 0.512 0.293 0.707 l 8.914 8.914 c 0.131 0.131 0.298 0.204 0.475 0.247 C 9.704 73.882 9.733 73.885 9.763 73.891 z" style="stroke: none;stroke-width: 1;stroke-dasharray: none;stroke-linecap: butt;stroke-linejoin: miter;stroke-miterlimit: 10;fill: rgb(242 54 64);fill-rule: nonzero;opacity: 1;" transform=" matrix(1 0 0 1 0 0) " stroke-linecap="round"></path>
                        
                    </g>
                    </svg>    
                        Get 2<br> months free 
                    </div>
                </div>
                <div class="free__tag">
                    <span>Setup in</span>
                    <span>just 5</span>
                    <span>Minutes!</span>
                </div>
            </div>


            <div class="new__design card__blocks year" id="cardWrapper" >
                
                <!-- Monthly plan loop start -->
                    <?php 
                        $query = $conn->query(" SELECT * FROM `plans` WHERE status = 1 and interval_plan = 'month' ") ;

                                    if($query->num_rows > 0) 
                                    {
                                        ?>
                    
                    
                                <?php
                                    
                                        $i = 1;
                                        while($data = $query->fetch_assoc() ) 
                                        {
                                            $pid=$data['id'];
                                            $query_cp = $conn->query("select * from `plans_functionality`  where plan_id=$pid") ;
                                             $data_cp = $query_cp->fetch_assoc();
                                        
                                                ?>
                    
                        <div class="month__plan__card plan__card <?php if($data['name']=="Booster Plan"){echo "popular";}  ?>    
                        ">                                  
                                  
                            <div class="top__area">
                                <!-- <div class="area-one" ></div>
                                <div class="area-two" ></div> -->
                                <h4><?php if($data['name']=='Free'){echo "Basic Plan";}else{echo $data['name'];}?></h4>
                                <?php if($data_cp['line15']!=null){   ?> <p class="sub_hs"><?php echo $data_cp['line15']; ?></p> <?php } ?>
                                <div class="price" >
                                    <div class="discount__price">
                                        <span>₹<?=round($data['price'])?></span> 
                                        <span><span class="inr_cur">INR</span>  <?php if($data['name']=='Free'){echo "/ forever";}else{echo "/ month";}?></span>
                                        <span class="d-none">/<?=strtoupper($data['interval'])?></span>
                                    </div>
                                    <div class="actual__price">

                                      <?php if($data['main_p'] != $data['s_price'] ){   ?>
                                        <del>₹<?=$data['main_p']?>  </del>
                                        <!-- <span>/month</span> -->
                                        <span class="d-none">/<?=strtoupper($data['interval'])?></span>
                                 <?php } ?>   

                                    </div>
                                 
                                </div> 
                                   
                                    
                               
                                <div class="popular__text" >Most Popular 50% Discount</div>
                                 <p class="sp_first"><span>Up To <span><span><?php echo $data['page_view']; ?></span> <span><?php if($data_cp['line2']!=null){ ?><?php echo $data_cp['line2']; ?><?php } ?></span>  <span class="q__icon" >?</span><span class="text__hidden" >To get an idea about your monthly page views go to your Google Analytics Dashboard.
                                        To go to page views in google analytics : Go to - Lifecycle > Engagement > Pages & Screens > Pageviews</span></p>
                                                        
                                    <div class="subscribe_free">
                                        <div class="sub_free">
                                            <a id="<?php echo str_replace(' ', '', strtolower($data['name'])); ?><?=$data['id']?>" class="btn__inverse get__started__btn  getStartedBtn<?=$data['id']?>" href="/signup.php" >Get Started<i class="las la-angle-right"></i></a>
                                            <?php 
                                            
                                                $list_of_price = json_decode($data['list_of_price_inr']);
                                    
                                                    if($data['s_type']=='Gold' ) { 
                                                    ?>
                                                    <div class="pr_sd  selectPricingDiv<?php echo $data['id'];?>" style="order: 0;">
                                                    <select name="selectPricingInr" id="selectPricingInr" class="selectPricing">
                                                        <option  style="display:none">Get Started</option>
                                                        <?php
                                                            foreach($list_of_price as $key => $row) {
                                                                ?>
                                                                <option value="<?=$key?>" <?php if($key=='250000'){ echo 'selected';} ?> data-planId="<?=$data['id']?>" > <?php if($key !='Unlimited' && ($key=='250000')){ echo ($key/1000)."k page views";}  if($key=='Unlimited'){ echo $row."s"; }    ?></option>
                                                                <?php  
                                                            } 
                                                        ?>
                                                    </select>
                                                    </div>
                                                    <!-- //123 -->
                                                
                                                    <a class="card__plan__button sales contactSalesBtn"   href="https://tfft.io/81r97R1" target="blank">Contact sales</a>
                                                <?php 
                                                } 
                                            ?>
                                        </div>
                                    </div>            
                                                        
                                                       

                            </div>
                            <div class="key_wrap">
                            <div class="key__points">
                                                <?php                                   
                                                        // $pid=$data['id'];
                                                        // $query_cp = $conn->query("select * from `plans_functionality`  where plan_id=$pid") ;
                                                        //  $data_cp = $query_cp->fetch_assoc();
                                                    
                                                        // print_r($data_cp); 
                                                ?> 
                                                        
                                                        

                                                   <?php if($data_cp['line1']!=null){ ?> <p> <?php echo $data_cp['line1']; ?> </p> <?php } ?>                             
                                                        <?php if($data_cp['line3']!=null){ ?> <p> <span class="first__line"><?php echo $data_cp['line3']; ?></span><span class="second__line"><?php echo $data_cp['line3']; ?></span></p>  <?php } ?>
                                                        <?php if($data_cp['line4']!=null){  ?>  <p><?php echo $data_cp['line4']; ?></p> <?php } ?>
                                                        <?php if($data_cp['line5']!=null){  ?> <p><?php echo $data_cp['line5']; ?></p> <?php } ?>
                                                        <?php if($data_cp['line6']!=null){ ?>  <p><?php echo $data_cp['line6']; ?></p> <?php } ?>
                                                        <?php if($data_cp['line7']!=null){  ?> <p><?php echo $data_cp['line7']; ?></p> <?php } ?>
                                                        <?php if($data_cp['line8']!=null){  ?> <p><?php echo $data_cp['line8']; ?></p> <?php } ?>
                                                        <?php if($data_cp['line9']!=null){   ?>  <p><?php echo $data_cp['line9']; ?></p> <?php } ?>
                                                        <?php if($data_cp['line10']!=null){   ?>  <p><?php echo $data_cp['line10']; ?></p> <?php } ?>
                                                        <?php if($data_cp['line11']!=null){   ?>  <p><?php echo $data_cp['line11']; ?></p> <?php } ?>
                                                        <?php if($data_cp['line12']!=null){   ?>  <p><?php echo $data_cp['line12']; ?></p> <?php } ?>
                                                        <?php if($data_cp['line13']!=null){   ?>  <p><?php echo $data_cp['line13']; ?></p> <?php } ?>
                                                        <?php if($data_cp['line14']!=null){   ?>  <p><?php echo $data_cp['line14']; ?></p> <?php } ?>

                                                        <div class="get__started__btn"><a id="<?php echo str_replace(' ', '', strtolower($data['name'])); ?><?=$data['id']?>" class="btn__inverse" href="/signup.php" >Get started<i class="las la-angle-right"></i></a></div>                        
                            </div>
                             </div>

                                      <div class="slide_btn"><div class="flx_con"><span>What’s included</span> <?xml version="1.0" ?><svg fill="none" stroke-width="1.5" viewBox="0 0 24 24" width="24" xmlns="http://www.w3.org/2000/svg"><path d="M6 9L12 15L18 9" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"/></svg></div></div>


                            <!-- <div class="free__trial__btn"><a class="btn__inverse" href="/signup.php" >Subscribe</a></div> -->
                            
                        </div>
                    
                                            <?php
                                        

                                            $i++;
                                        }
                                        
                                    }
                                        
                                ?>

                    <!-- Monthly plan loop end -->


                    <!-- Yearly plan loop start -->

                            <?php 
                        $query1 = $conn->query(" SELECT * FROM `plans` WHERE status = 1 and interval_plan = 'year' ") ;

                            if($query1->num_rows > 0) 
                            {
                                ?>
            
             
                          <?php
                            
                                $i = 1;
                                while($data = $query1->fetch_assoc() ) 
                                {
                                    // print_r($data);
                                    $pid=$data['id'];
                                    $query_cp = $conn->query("select * from `plans_functionality`  where plan_id=$pid") ;
                                     $data_cp = $query_cp->fetch_assoc();
                                 
                                        ?>
                
            
                <div class="year__plan__card  plan__card <?php if($data['name']=="Booster Plan"){echo "popular";}  ?> ">
                        
                    <div class="top__area">
                        <!-- <div class="area-one" ></div>
                        <div class="area-two" ></div> -->
                        <div class="free__tag__month" >1 months free</div>
                        <h4><?php if($data['name']=='Free'){echo "Basic Plan";}else{echo $data['name'];}?></h4>
                        <?php if($data_cp['line15']!=null){   ?><p class="sub_hs"><?php echo $data_cp['line15']; ?></p> <?php } ?>
                        <div class="price" >    
                                <div class="discount__price">
                                    <span>₹<?=round($data['price'])?></span> 
                                    <span><span class="inr_cur">INR</span> <?php if($data['name']=='Free'){echo "/ forever";}else{echo "/ year";}?> </span>
                                    <span class="d-none">/<?=strtoupper($data['interval'])?></span>
                                </div>
                                <div class="actual__price">

                                    <?php if($data['main_p'] != $data['s_price'] ){   ?>
                                              
                                    <del>₹<?=$data['main_p']?>  </del>
                                    <!-- <span>/year</span> -->
                                    <span class="d-none">/<?=strtoupper($data['interval'])?></span>
                                    <?php } ?>     
                                </div>
                                <!-- <div class="text__paid__anu" >Paid Annually</div> -->

                                <div class="price__changed" >
                                        <div><span>₹ <?=number_format($data['price']/12, 2)?> <span>/month</span></span></div>
                                        <div>Paid Annually <span class="main_p">₹<?=round($data['main_p'])?> <span>/yr</span></span> <span class="d-price">₹<?=$data['price']?> <span>/yr</span></span></div> 
                                    </div>
                        </div> 
                        <div class="popular__text" >Most Popular 50% Discount</div>
                        <p class="sp_first"><span>Up To <span><span><?php echo $data['page_view']; ?></span> <span><?php if($data_cp['line2']!=null){ ?><?php echo $data_cp['line2']; ?><?php } ?></span>  <span class="q__icon" >?</span><span class="text__hidden" >To get an idea about your monthly page views go to your Google Analytics Dashboard. To go to page views in google analytics : Go to - Lifecycle > Engagement > Pages & Screens > Pageviews</span></p>
                                                
                        <div class="subscribe_free">
                                <div class="sub_free">
                                    <a id="<?php echo str_replace(' ', '', strtolower($data['name'])); ?><?=$data['id']?>" class="btn__inverse get__started__btn  getStartedBtn<?=$data['id']?>" href="/signup.php" >Get Started<i class="las la-angle-right"></i></a>
                                    <?php 
                                    
                                        $list_of_price = json_decode($data['list_of_price_inr']);
                            
                                            if($data['s_type']=='Gold' ) { 
                                            ?>
                                            <div class="pr_sd  selectPricingDiv<?php echo $data['id'];?>" style="order: 0;">
                                            <select name="selectPricingInr" id="selectPricingInr" class="selectPricing">
                                                <option  style="display:none">Get Started</option>
                                                <?php
                                                    foreach($list_of_price as $key => $row) {
                                                        ?>
                                                        <option value="<?=$key?>" <?php if($key=='250000'){ echo 'selected';} ?> data-planId="<?=$data['id']?>" > <?php if($key !='Unlimited' && ($key=='250000')){ echo ($key/1000)."k page views";}  if($key=='Unlimited'){ echo $row."s"; }    ?></option>
                                                        <?php  
                                                    } 
                                                ?>
                                            </select>
                                            </div>
                                            <!-- //123 -->
                                        
                                            <a class="card__plan__button sales contactSalesBtn"   href="https://tfft.io/81r97R1" target="blank">Contact sales</a>
                                        <?php 
                                        } 
                                    ?>
                             </div>
                        </div>
                        
                    </div>
                    <div class="key_wrap">
                    <div class="key__points">
                    
                                  <?php  



                                   
                                               
                                              
                                                // print_r($data_cp); 
                                                     ?> 
                                                
                                                   

                                                            <?php if($data_cp['line1']!=null){ ?> <p> <span class="first__line" ><?php echo $data_cp['line1']; ?></span><span class="second__line" ><?php echo $data_cp['line1']; ?></span></p> <?php } ?>                             
                                                 <?php if($data_cp['line3']!=null){ ?> <p> <?php echo $data_cp['line3']; ?> </p>  <?php } ?>
                                                 <?php if($data_cp['line4']!=null){  ?>  <p><?php echo $data_cp['line4']; ?></p> <?php } ?>
                                                 <?php if($data_cp['line5']!=null){  ?> <p><?php echo $data_cp['line5']; ?></p> <?php } ?>
                                                 <?php if($data_cp['line6']!=null){ ?>  <p><?php echo $data_cp['line6']; ?></p> <?php } ?>
                                                 <?php if($data_cp['line7']!=null){  ?> <p><?php echo $data_cp['line7']; ?></p> <?php } ?>
                                                  <?php if($data_cp['line8']!=null){  ?> <p><?php echo $data_cp['line8']; ?></p> <?php } ?>
                                                <?php if($data_cp['line9']!=null){   ?>  <p><?php echo $data_cp['line9']; ?></p> <?php } ?>
                                                  <?php if($data_cp['line10']!=null){   ?>  <p><?php echo $data_cp['line10']; ?></p> <?php } ?>
                                                  <?php if($data_cp['line11']!=null){   ?>  <p><?php echo $data_cp['line11']; ?></p> <?php } ?>
                                                        <?php if($data_cp['line12']!=null){   ?>  <p><?php echo $data_cp['line12']; ?></p> <?php } ?>
                                                        <?php if($data_cp['line13']!=null){   ?>  <p><?php echo $data_cp['line13']; ?></p> <?php } ?>
                                                        <?php if($data_cp['line14']!=null){   ?>  <p><?php echo $data_cp['line14']; ?></p> <?php } ?>


                                                  <div class="get__started__btn"><a id="<?php echo str_replace(' ', '', strtolower($data['name'])); ?><?=$data['id']?>" class="btn__inverse" href="/signup.php" >Get started<i class="las la-angle-right"></i></a></div>

                    </div>
                    </div>
                    <div class="slide_btn"><div class="flx_con"><span>What’s included</span> <?xml version="1.0" ?><svg fill="none" stroke-width="1.5" viewBox="0 0 24 24" width="24" xmlns="http://www.w3.org/2000/svg"><path d="M6 9L12 15L18 9" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"/></svg></div></div>

                   
                    <!-- <div class="free__trial__btn"><a class="btn__inverse" href="/signup.php" >Subscribe</a></div> -->
                    
                
                </div>
                                    <?php
                                   

                                    $i++;
                                }
                                
                            }
                                
                        ?>

                        <!-- Yearly plan loop end -->


               
            </div>
        </div>
    </div>
</div>
<!-- Card Section end -->


<section class="trust-factor-wrapper">
        <div class="section__wrapper">
            <div class="new_usp">
                <h2 class="trust-factor-title">Accelerate Your Online Success with <span>Website <br>Speedy</span>: Fast, Reliable, and Efficient!</h2>
                <div class="trust-factor-list">
                    <div class="trust-factor-tem-new">
                        <div class="trust-factor-icon">
                            <img  src="<?=$bunny_image?>Web-Vitals.webp" alt="Increased Conversion">
                        </div>
                        <p class="trust-factor-desc">Fix Core Web Vitals</p>
                        <p class="trust-factor-text">Enhance your site's search rank and visibility by optimizing core web vitals, and boosting user experience.</p>
                      <img src="<?=$bunny_image?>core-web-vitals-graphic.webp" alt="Fix Core Web Vitals">

                    </div>
                      <div class="trust-factor-tem-new">
                        <div class="trust-factor-icon">
                            <img  src="<?=$bunny_image?>conversion-rate-1.webp" alt="Increased Conversion">

                        </div>
                        <p class="trust-factor-desc">Conversion Rate<p>
                        <p class="trust-factor-text">Maximize e-commerce revenue and conversion funnel metrics by improving your site's conversion rate.</p>
                        <img src="/<?=$bunny_image?>conversion-rate.webp" alt="Conversion Rate">

                    </div>
                    <div class="trust-factor-tem-new">
                        <div class="trust-factor-icon">
                            <img  src="<?=$bunny_image?>ads.webp" alt="Improved Ads"> 
                        </div>
                        <p class="trust-factor-desc">Increase Session Duration</p>
                        <p class="trust-factor-text">Encourage longer user engagement to drive increased views, clicks, and purchases.</p>
                        <img src="<?=$bunny_image?>session-length-graphic.webp" alt="Increase Session Duration">

                    </div>
                    <div class="trust-factor-tem-new">
                        <div class="trust-factor-icon">
                            <img  src="<?=$bunny_image?>bounce-rate-1.webp" alt="Reduced bounce">
                        </div>
                        <p class="trust-factor-desc">Reduce Bounce Rates</p>
                        <p class="trust-factor-text">Decrease marketing costs by minimizing bounce rates and keeping visitors engaged.</p>
                        <img src="<?=$bunny_image?>bounce-rate-graphic.webp" alt="Reduce Bounce Rates">

                    </div>
                </div>
            </div>
        </div>
</section>

<div class="logo__section price__page client__logos">
    <div class="section__wrapper">
        <div class="logo__section__wrapper">
            <div class="heading">
                <h2>Our Happy <span>Clients</span></h2>
            </div>
            <div class="logo__container">
                <div class="logo">
                    <a href="//www.giantteddy.com" target="_blank" ></a>
                        <div class="img__wrapper">
                            <img class="giantteddy" src="<?=$bunny_image?>giant-teddy-logo.webp" alt="Giantteddy Logo">
                        </div>
                </div>
                <div class="logo">
                    <a href="//prestigebotanicals.com" target="_blank" ></a>
                        <div class="img__wrapper">
                            <img class="prestigebotanicals" src="<?=$bunny_image?>prestigebotanicals-logo.webp" alt="Prestigebotanicals Logo">
                        </div>
                    
                </div>
                <div class="logo">
                    <a href="//www.simtopiagolfski.ca" target="_blank" ></a>
                        <div class="img__wrapper">
                            <img class="simtopia" src="<?=$bunny_image?>simtopia-logo.webp" alt="Simtopia Logo">
                        </div>
                    
                </div>
                <div class="logo">
                    <a href="//balancedwheelhealth.com" target="_blank" ></a>
                        <div class="img__wrapper">
                            <img class="balancedwheelhealth" src="<?=$bunny_image?>balancedwheelhealth-logo.webp" alt="Balancedwheelhealth Logo">
                        </div>
                    
                </div>
                <div class="logo">
                    <a href="//www.otherlife.so" target="_blank" ></a>
                    <div class="img__wrapper">
                        <img class="otherlife" src="<?=$bunny_image?>otherlife-logo.webp" alt="Otherlife Logo">
                    </div>
                </div>


            </div>

        </div>
    </div>
</div>


<!-- Testimonial Section start -->
<div class="bg__review__price__page">
    <div class="review__section__pricning" >
        <div class="section__wrapper">
            <div class="single__review">
                <div class="review__img" >
                    <img src="<?=$bunny_image?>plan-page-image.avif" alt="review person one">
                </div>
                <div class="review__text" >
                    <p class="review__para">
                        <strong>Mobile Speed -29 to 81 & Desktop - 85 to 98 on Google page speed insights</strong>
                        I have tested this on Google page speed insights... I tried with and without the WebsiteSpeedy script a few times. Results are consistently a poor score without and consistently a good score with... this has saved me from stripping out some integrations etc.
                    </p>
                    <p class="name">Ray Khosravi</p>
                    <p class="designation">CEO @ <a href="//www.giantteddy.com/" target="_blank">www.giantteddy.com</a></p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Testimonial Section end -->

<!-- Price FAQ start-->


<div class="faq__review price__page" >
    <div class="section__wrapper" >
        <div class="faq__inner__wrapper">
            <div class="heading">
                <h2>Frequently <span>Asked</span> Questions</h2>
            </div>

            <div class="faqs__main">
                <div class="faq__wrapper"  >
                    <h5 class="que"> Which Platforms does Website Speedy support?</h5>
                    <div class="ans" >
                        <p>Website speedy works with all major platforms like Shopify, Bigcommerce, Wix, Shift4shop, Opencart, Prestashop, Dotnetnew, Ekm, Americommerce and Square space. If you have a custom or SaaS website, please contact us for installation.</p>
                    </div>
                </div>
                <div class="faq__wrapper"  >
                    <h5 class="que"> How are page views counted?</h5>
                    <div class="ans" >
                        <p>Each visitor is counted as a single-page view, whether a person views 10 pages or 100 pages in a single day, we only consider that as one-page view.</p>
                    </div>
                </div>
                <div class="faq__wrapper"  >
                    <h5 class="que"> How many websites can I Speed up with one plan?</h5>
                    <div class="ans" >
                        <p>With each Website Speedy plan you purchase, you can speed up one website.</p>
                    </div>
                </div>
                <div class="faq__wrapper"  >
                    <h5 class="que"> What access do you need to speed up my website?</h5>
                    <div class="ans" >
                        <p> Typically, we do not require access to your website to speed it up. However, depending on the advanced architecture of your website, we may need access to update advance parameters in the theme file.</p>
                    </div>
                </div>
                <div class="faq__wrapper"  >
                    <h5 class="que"> How can I talk to the Support team?</h5>
                    <div class="ans" >
                        <p> You can talk to our support team by submitting a support ticket or sending an email to support@websitespeedy.com, To submit a support ticket, simply login to your account.</p>
                    </div>
                </div>
                <div class="faq__wrapper"  >
                    <h5 class="que"> Will you help me install Website Speedy?</h5>
                    <div class="ans" >
                        <p>  Absolutely! If you are not technical or need some help with the installation, our highly trained engineers will assist you. Just create a support ticket from within your Website Speedy dashboard with the relevant details, and we’ll reach out to you asap!</p>
                    </div>
                </div>
                <div class="faq__wrapper"  >
                    <h5 class="que"> Can I change my plan subscription at any time?</h5>
                    <div class="ans" >
                        <p>  Yes, you can cancel your subscription or downgrade/upgrade to any plan.</p>
                    </div>
                </div>
                <div class="faq__wrapper"  >
                    <h5 class="que"> Do you offer any discounts?</h5>
                    <div class="ans" >
                        <p>  You can get two months free if you choose the super plan annually.</p>
                    </div>
                </div>
                <div class="faq__wrapper"  >
                    <h5 class="que"> Do you have a free plan?</h5>
                    <div class="ans" >
                        <p>  All our plans come with a 7-day free trial.</p>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<!-- Price FAQ end-->

<div class="logo__section">
    <div class="section__wrapper">
        <div class="logo__section__wrapper">
            <div class="heading">
                <h2>Our <span>WebsiteSpeedy Script</span> is Compatible with All Major Platforms</h2>
            </div>
            <div class="logo__container">
                <div class="logo">
                    <a href="/shopify-speed-optimization.php" ></a>
                        <div class="img__wrapper">
                            <img src="<?=$bunny_image?>shopify-full.webp" alt="Shopify Logo">
                        </div>
                    
                </div>
                <div class="logo">
                    <a href="/wix-editor-x-speed-optimization.php" ></a>
                        <div class="img__wrapper">
                            <img src="<?=$bunny_image?>wix.webp" alt="Wix Logo">
                        </div>
                    
                </div>
                <div class="logo">
                    <a href="/bigcommerce-speed-optimization.php" ></a>
                        <div class="img__wrapper">
                            <img src="<?=$bunny_image?>bigcommerce-full.webp" alt="BigCommerce Logo">
                        </div>
                    
                </div>
                <div class="logo">
                    <a href="/squarespace-speed-optimization.php"></a>
                        <div class="img__wrapper">
                            <img class="squarespace" src="<?=$bunny_image?>squarespace-full.webp" alt="Squarespace Logo">
                        </div>
                    
                </div>
                <div class="logo">
                    <a href="/shift4shop-speed-optimization.php"></a>
                    <div class="img__wrapper">
                        <img src="<?=$bunny_image?>Shift4Shop-full.webp" alt="Shift4 Shop Logo">
                    </div>
                </div>
                <div class="logo">
                <a href="/duda-speed-optimization.php" ></a>
                    <div class="img__wrapper">
                        <img class="duda" src="<?=$bunny_image?>duda.webp" alt="duda Logo">
                    </div>
                </div>
                
                <div class="logo">
                <a href="javascript:void(0)" ></a>
                    <div class="img__wrapper">
                        <img class="prestashop" src="<?=$bunny_image?>prestashop.webp" alt="prestashop Logo">
                    </div>
                </div>
                <div class="logo">
                <a href="/weebly-speed-optimization.php" ></a>
                    <div class="img__wrapper">
                        <img class="weebly" src="<?=$bunny_image?>weebly-logo-full.webp" alt="Weebly Logo">
                    </div>
                </div>


                <div class="logo">
                <a href="/webflow-speed-optimization.php" ></a>
                    <div class="img__wrapper">
                        <img class="webflow" src="<?=$bunny_image?>webflow-logo-full.webp" alt="Webflow Arrow">
                    </div>
                </div>

            </div>

        </div>
    </div>
</div>



<!-- Plan page end -->

<script>

$('.slide_btn').click(function(){
    $(this).parents('.plan__card').find('.key_wrap').slideToggle();
    $(this).toggleClass('open_key');
})

</script>

<script>
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

    console.log(country);
    if(country!="IN"){
        window.location.href = "<?=HOST_URL?>website-speed-optimiation-cost-us.php";
    }
});


</script>

<script>
    var switchPrice = document.getElementById('switchPrice');
    var cardWrapper = document.getElementById('cardWrapper');

    switchPrice.addEventListener('click', () => {
        cardWrapper.classList.toggle('year');
    })

</script>



<script>
    if(window.location.href === '<?=HOST_URL?>website-speed-optimiation-cost-new.php') {
        document.querySelector('.website__test__section').remove();
        document.querySelector('.contact__form__only').remove();
    }
</script> 

<script>
    const x = document.querySelectorAll('.second__line'); 
    const z = document.querySelectorAll('.first__line');
    for (let i = 0; i < x.length; i++) {
       var y = x[i].innerText.slice(3);
       x[i].innerText = y
    }
    for (let j = 0; j < z.length; j++) {
       var k = z[j].innerText.slice(0, 3)
       z[j].innerText = k
    }

    //123
$(document).on('change','#selectPricingInr',function(){
         is_valid = true;
          var price = $(this).val();
          var planId = $(this).find(':selected').data('planid'); // Using find(':selected') to get the selected option
          var planSelectInr = 'Select';
         
          if(planId=='4'){
            if (parseInt(price) == 250000) {
             is_valid = true;
             console.log('if4');
             $('.getStartedBtn4').show();
             $('.contactSalesBtn').show();
            } else if (price === 'Unlimited') {
               console.log('elsif4');
               $('.getStartedBtn4').hide();
               $('.contactSalesBtn').show();
               is_valid = false;
            } else {
               is_valid = true;  // Set is_valid to false if none of the conditions match
            }

          }else if(planId=='15'){
            if (parseInt(price) == 250000) {
             is_valid = true;
             console.log('if15');
             $('.getStartedBtn15').show();
             $('.contactSalesBtn').show();
            }  else if (price === 'Unlimited') {
               console.log('elsif15');
               $('.getStartedBtn15').hide();
               $('.contactSalesBtn').show();
               is_valid = false;
            } else {
               is_valid = true;  // Set is_valid to false if none of the conditions match
            }

          }
         
         if(is_valid){
        //   $.ajax({
        //     url : 'adminpannel/common.php',
        //     type : 'post',
        //     data : {
        //        price : price,
        //        planId : planId,
             
        //        planSelectInr : planSelectInr
        //     },
        //     success : function(response){
        //        console.log(response);
        //        var obj = $.parseJSON(response);
        //        console.log(obj.message.price);
        //        if(obj.status =='1' && obj.message.id=='4' ){
        //           // console.log(1)
        //           $('.amount4').html(obj.message.price);
        //        }
        //        else if(obj.status =='1' && obj.message.id=='15'){
        //           // console.log(2)
        //           $('.amount15').html(obj.message.price);
        //        }else{
        //           // console.log(3)
        //           $('.price-tag.amount4').html();
        //           $('.price-tag.amount15').html();
        //        }
        //     }
        //   })
         }
})
</script>
<?php require_once 'include/footer.php';?>

