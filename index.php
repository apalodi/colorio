<?php
/**
 * The main template file.
 *
 * @package Colorio
 */

get_header(); ?>

<div id="content" class="site-content">
	<div id="primary" class="content-area">

		<?php
		if ( have_posts() ) :
			if ( ! is_paged() ) :
				/**
				 * Hooked functions
				 *
				 * @hooked apalodi_featured_section - 10
				 * @hooked apalodi_blog_fullwidth_widgets - 15
				 */
				do_action( 'apalodi_between_blog_posts' );
			endif;
			if ( have_posts() ) :
				?>
				<div class="section section-posts">
					<div class="container d-container posts-container">
						<div class="posts-block posts-block-main content">
							<div class="row">
								<?php
								while ( have_posts() ) :
									the_post();
									the_title();
								endwhile;
								?>
							</div>
						</div>
						<?php get_sidebar( 'blog' ); ?>
					</div>
				</div>
				<?php
			endif;
		else :
			?>

			none

		<?php endif; ?>

	</div><!-- #primary -->

</div><!-- #content -->

<?php
get_footer();
