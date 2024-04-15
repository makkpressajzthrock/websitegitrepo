<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Focus Blog
 */
/**
* Hook - focus_blog_action_doctype.
*
* @hooked focus_blog_doctype -  10
*/
do_action( 'focus_blog_action_doctype' );
?>
<head>
<?php
/**
* Hook - focus_blog_action_head.
*
* @hooked focus_blog_head -  10
*/
do_action( 'focus_blog_action_head' );
?>
<meta name="google-site-verification" content="rfxSRamLmW3fdJrSYcW79JD-EfaNihV8G3nL7rZZE3k" >
<!-- Google Tag Manager -->
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','GTM-MK5VN7M');</script>
<!-- End Google Tag Manager -->
<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php do_action( 'wp_body_open' ); ?>

<?php

/**
* Hook - focus_blog_action_before.
*
* @hooked focus_blog_page_start - 10
*/
do_action( 'focus_blog_action_before' );

/**
*
* @hooked focus_blog_header_start - 10
*/
do_action( 'focus_blog_action_before_header' );

/**
*
*@hooked focus_blog_site_branding - 10
*@hooked focus_blog_header_end - 15 
*/
// do_action('focus_blog_action_header');
?>

<?php

$main_site_url = "https://websitespeedy.com/" ;

?>

<div class="custom-header-new">
    <div class="wrapper">
        <div class="headerList">
            <div class="site-logo">
                <a href="<?=$main_site_url?>" class="custom-logo-link" rel="home" aria-current="page">
                    <img width="300" height="91" src="<?=get_home_url()?>/wp-content/uploads/2023/09/speedylogo.png" class="custom-logo" alt="" decoding="async">
                </a>
            </div>
            <div class="ctaBtn-wrap">
                <a href="<?=$main_site_url?>adminpannel/">Login</a>
                <a href="<?=$main_site_url?>signup.php" class="btn">Start free trial</a>    
            </div>
            <div class="cus-navList desktop">
                <?php
                    do_action('focus_blog_action_header');
                ?>
            </div>
        </div>
    </div>
</div>
<?php

/**
*
* @hooked focus_blog_content_start - 10
*/
do_action( 'focus_blog_action_before_content' );

/**
 * Banner start
 * 
 * @hooked focus_blog_banner_header - 10
*/
do_action( 'focus_blog_banner_header' );  
