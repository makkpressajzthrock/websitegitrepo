<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Focus Blog
 */

/**
 *
 * @hooked focus_blog_footer_start
 */
do_action( 'focus_blog_action_before_footer' );

/**
 * Hooked - focus_blog_footer_top_section -10
 * Hooked - focus_blog_footer_section -20
 */
do_action( 'focus_blog_action_footer' );
?>
<!--
<div class="main-footer">
    <div class="wrapper">
        <div class="footer-top">
            <div class="footerlogo">
                <div class="site-logo ">
                <?php 
                    if(has_custom_logo()):
                        the_custom_logo();
                    endif;
                ?>
                </div>
                <div class="address">
                        <p>Website Speedy is a SaaS-based website optimization tool that instantly reduces website loading times.</p>
                </div>
                <a href="mailto:support@websitespeedy.com" class="mail__icon__footer"><span>support@websitespeedy.com</span></a>
            </div>
            <div class="speed-opt-pages">
                <h2>Speed Optimization</h2>
                <ul>
                    <li><a href="https://websitespeedy.com/shopify-speed-optimization.php">Shopify Speed Optimization</a></li>
                    <li><a href="https://websitespeedy.com/wix-editor-x-speed-optimization.php">WIX | Editor X Speed Optimization</a></li>
                    <li><a href="https://websitespeedy.com/bigcommerce-speed-optimization.php">Bigcommerce Speed Optimization</a></li>
                    <li><a href="https://websitespeedy.com/squarespace-speed-optimization.php">Squarespace Speed Optimization</a></li>
                    <li><a href="https://websitespeedy.com/shift4shop-speed-optimization.php">Shift4Shop Speed Optimization</a></li>
                    <li><a href="https://websitespeedy.com/weebly-speed-optimization.php">Weebly Speed Optimization</a></li>
                    <li><a href="https://websitespeedy.com/duda-speed-optimization.php">Duda Speed Optimization</a></li>
                    <li><a href="https://websitespeedy.com/signup.php">Custom Website Speed Optimization</a></li>
                    <li><a href="https://websitespeedy.com/signup.php">Saas Speed Optimization</a></li>
                </ul>
            </div>
            <div class="cwv-pages">
                <h2>Fix Core web Vitals</h2>
                <ul>
                    <li><a href="https://websitespeedy.com/fix-shopify-core-web-vitals.php">Shopify Core Web Vitals</a></li>
                    <li><a href="https://websitespeedy.com/fix-wix-editor-x-core-web-vitals.php">Wix/Editor Core Web Vitals</a></li>
                    <li><a href="https://websitespeedy.com/fix-bigcommerce-core-web-vitals.php">Bigcommerce Core Web Vitals</a></li>
                    <li><a href="https://websitespeedy.com/fix-squarespace-core-web-vitals.php">Squarespace Core Web Vitals</a></li>
                    <li><a href="https://websitespeedy.com/fix-shift4shop-core-web-vitals.php">Shift4Shop Core Web Vitals</a></li>
                </ul>
            </div>
            <div class="legal-parter-main">
                <div class="legal-pages">
                    <h2>Legal</h2>
                    <ul>
                        <li><a href="https://websitespeedy.com/privacy-policy.php">Privacy Policy</a></li>
                        <li><a href="https://websitespeedy.com/cookie-policy.php">Cookie Policy</a></li>
                        <li><a href="https://websitespeedy.com/terms-of-use.php">Terms Of Use</a></li>
                        <li><a href="https://websitespeedy.com/cancellation-refund-policy.php">Cancellation and Refund Policy</a></li>
                    </ul>
                </div>
                <div class="parter-pages">
                    <h2>Partnership Programs</h2>
                    <ul>
                        <li><a href="https://websitespeedy.com/agency-partner.php">Agency Partner</a></li>
                        <li><a href="/about-us.php#gotoText">Investor</a></li>
                    </ul>
                </div>
            </div>
            <div class="info-pages">
                <h2>Pages</h2>
                <ul>
                    <li><a href="https://websitespeedy.com/about-us.php">About Us</a></li>
                    <li><a href="https://websitespeedy.com/why-website-speed-matters.php">Why Speed Matters</a></li>
                    <li><a href="https://websitespeedy.com/speed-guarantee.php">Speed Guarantee</a></li>
                    <li><a href="https://websitespeedy.com/website-speed-optimiation-cost.php" class="pricing_link_link">Pricing</a></li>
                    <li><a href="https://help.websitespeedy.com/faqs" target="_blank">FAQ</a></li>
                    <li><a href="https://websitespeedy.com/blogs.php">Blogs</a></li>
                    <li><a href="https://websitespeedy.com/case-study.php">Case Study</a></li>
                    <li><a href="https://websitespeedy.com/contact-us.php">Contact Us</a></li>
                    <li><a href="https://help.websitespeedy.com/" target="_blank">Knowledge Base</a></li>
                    <li><a href="https://websitespeedy.com/platforms.php">Platforms</a></li>
                </ul>
            </div>
        </div>
    </div>
</div>
-->
<?php
/**
 * Hooked - focus_blog_footer_end. 
 */
do_action( 'focus_blog_action_after_footer' );

wp_footer(); ?>

</body>  
</html>