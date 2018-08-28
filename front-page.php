<?php
	/**
	 * The front page template file
	 *
	 * If the user has selected a static page for their homepage, this is what will
	 * appear.
	 *
	 * Learn more: https://codex.wordpress.org/Template_Hierarchy
	 *
	 * @package WordPress
	 * @subpackage FrozenBones
	 * @since 1.0
	 * @version 1.0
	 */

//If this is the static front page.
if (get_option('show_on_front') != 'posts') {
	get_header();
?>
			<section class="section">
				<div class="container">
					<section class="hero">
						<div class="hero-body">
							<?php if (is_home() && is_front_page()) { ?>
								<h1 class="title is-size-1"><?php bloginfo('name'); ?></h1>
								<h2 class="subtitle is-size-3"><?php bloginfo('description'); ?></h2>
							<?php } ?>
						</div>
					</section>
				</div>
				<div class="container">
					<div class="columns">
					<section id="main" class="column is-9">
						<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
							<?php get_template_part('templates/page/content', 'front-page'); ?>
						<?php endwhile; else : ?>
							<section id="page-not-found">
								<?php _frozen_not_found(); ?>
							</section>
						<?php endif; ?>
						</section>
						<?php get_sidebar(); ?>
					</div>
				</div>
			</section>
<?php get_footer(); ?>
<?php } else { ?>
	<?php require_once(get_stylesheet_directory() . '/index.php'); ?>
<?php } ?>