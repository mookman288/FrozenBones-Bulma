<?php
	/**
	 * The template for displaying 404: Not Found pages.
	 *
	 * @link https://codex.wordpress.org/Creating_an_Error_404_Page
	 *
	 * @package WordPress
	 * @subpackage FrozenBones
	 * @since 1.0
	 * @version 1.0
	 */

	 get_header();
?>
			<section class="section">
				<div class="container">
					<div class="columns">
						<section id="main" class="column is-9 page-404">
							<?php _frozen_not_found(); ?>
						</section>
						<?php get_sidebar(); ?>
					</div>
				</div>
			</section>
<?php get_footer(); ?>
