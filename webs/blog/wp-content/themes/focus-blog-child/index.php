<?php
/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Focus Blog
 */

get_header(); ?>
	
	<div class="section-gap">
		<div class="wrapper">
			<div class="top-wrap">
				<div class="home-link">
					<a href="https://websitespeedy.com/blog/">All Category</a>
				</div>
				<div class="cate-wrap-cus">
					<div class="tags-list">
						<?php
							$categories = get_categories();

							foreach ($categories as $category) {
								echo '<a href="' . get_category_link($category->term_id) . '">' . $category->name . '</a>';
							}
						?>
					</div>
				</div>
				<div class="cus-search-wrap">
					<form role="search" method="get" class="search-form" action="https://websitespeedy.com/blog/">
						<label>
							<span class="screen-reader-text">Search for:</span>
							<input type="search" class="search-field" placeholder="Search ..." value="" name="s" title="Search for:">
						</label>
						<button type="submit" class="search-submit" value="Search"><i class="fas fa-search"></i></button>
					</form>
				</div>
			</div>
			<div id="primary" class="content-area">
				<main id="main" class="site-main blog-posts-wrapper" role="main">
					<div class="section-content col-3 clear">
						<?php
						if ( have_posts() ) :

							/* Start the Loop */
							while ( have_posts() ) : the_post();

								/*
								 * Include the Post-Format-specific template for the content.
								 * If you want to override this in a child theme, then include a file
								 * called content-___.php (where ___ is the Post Format name) and that will be used instead.
								 */
								get_template_part( 'template-parts/content', get_post_format() );

							endwhile;
						else :

							get_template_part( 'template-parts/content', 'none' );

						endif; ?>
					</div><!-- .blog-archive-wrapper -->
					<?php the_posts_pagination(); ?>
				</main><!-- #main -->
			</div><!-- #primary -->

			<?php get_sidebar(); ?>
		</div><!-- .wrapper -->
	</div><!-- .section-gap -->

<?php
get_footer();