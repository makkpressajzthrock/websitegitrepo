<?php
/**
 * The template for displaying search results pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#search-result
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
						if ( have_posts() ) : ?>


							<?php
							/* Start the Loop */
							while ( have_posts() ) : the_post();

								/**
								 * Run the loop for the search to output the results.
								 * If you want to overload this in a child theme then include a file
								 * called content-search.php and that will be used instead.
								 */
								get_template_part( 'template-parts/content', 'search' );

							endwhile;
						else :

							get_template_part( 'template-parts/content', 'none' );

						endif; ?>
					</div>
				<?php the_posts_pagination(); ?>
				</main><!-- #main -->
			</div><!-- #primary -->

			<?php get_sidebar(); ?>
		</div><!-- .wrapper -->
	</div><!-- .section-gap -->

<?php
get_footer();