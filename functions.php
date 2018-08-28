<?php
	/**
	 * FrozenBones Bulma
	 *
	 * @author PxO Ink (http://pxoink.net)
	 * @uses FrozenBones
	 * @package bonestheme
	 */

	/**
	 * Custom breadcrumbs based on Cazue breadcrumbs.
	 */
	function _frozen_breadcrumbs() {
		//Depending on the page.
		if (!is_home() && !is_front_page()) {
			?>
				<nav class="breadcrumb" aria-label="<?php _e('breadcrumbs', 'bonestheme'); ?>">
					<ul>
						<li>
							<a href="<?php print(home_url('/')); ?>">Home</a>
						</li>
						<?php
							//Depending on the page.
							if (is_category() || is_single()) {
								//Get the category.
								$cats	=	get_the_category();

								//For each catgory.
								foreach($cats as $cat) {
									?>
										<li>
											<a href="<?php print(get_category_link($cat -> term_id)); ?>"
												title="<?php _e($cat -> name); ?>">
												<?php _e($cat -> name); ?></a>
										</li>
									<?php
								}
							} elseif (is_page()) {
								//If this post doesn't have a parent.
								if (!$post -> post_parent) {
									//Get ancestors.
									$ancestors	=	get_post_ancestors($post -> ID);

									//Sort the ancestors.
									krsort($ancestors);

									//For each ancestor.
									foreach($ancestors as $anc) {
										?>
											<li>
												<a href="<?php print(get_permalink($anc)); ?>"
													title="<?php print(get_the_title($anc)); ?>">
													<?php print(get_the_title($anc)); ?></a>
											</li>
										<?php
									}
								}
							}
						?>
						<li class="is-active">
							<a href="#main" aria-current="<?php _e('page', 'bonestheme'); ?>">
							<?php
								//Get the title.
								the_title();
							?>
							</a>
						</li>
					</ul>
				</nav>
			<?php
		}
	}

	/**
	 * Implement custom nested comments.
	 *
	 * @param string $comment
	 * @param string $args
	 * @param string $depth
	 */
	function	_frozen_comments($comment, $args, $depth) {
		//Declare globals.
		$GLOBALS['comment']	=	$comment;
		?>
			<li <?php comment_class(); ?>>
				<div id="comment-<?php comment_ID(); ?>" class="card">
					<div class="card-content">
						<div class="media">
							<div class="media-left">
								<?php get_avatar($comment, 125); ?>
							</div>
							<div class="media-content">
								<span class="is-size-5 author"><?php get_comment_author_link(); ?></span><br />
								<span><?php printf('Posted <time class="updated" datetime="%1$s" pubdate>%2$s</time>',
									get_comment_time('Y-m-j'), get_comment_time(get_option('date_format'))); ?></span>
							</div>
						</div>
						<div class="content">
							<?php if ($comment -> comment_approved == '0') { ?>
								<p class="notification is-warning">
									<?php _e('Your comment is awaiting moderation.', 'bonestheme'); ?>
								</p>
							<?php } ?>
							<?php comment_text(); ?>
							<p class="has-text-right">
								<?php edit_comment_link(__('Edit', 'bonestheme'), ' ', ''); ?>
								<?php comment_reply_link(array_merge($args, array('reply_text' => __('Reply'),
									'login_text' => __('Login'),
									'depth' => $depth,
									'max_depth' => $args['max_depth']))); ?>
								<?php printf(__('<a href="%s" target="_blank">Permalink</a>'),
									htmlspecialchars(get_comment_link($comment -> comment_ID))); ?>
							</p>
						</div>
					</div>
				</div>
			</li>
		<?php
	}

	/**
	 * Fallback navigation.
	 */
	function _frozen_navigation() {
		?>
			<nav class="navbar is-light" role="navigation" aria-label="<?php _e('navigation', 'bonestheme'); ?>">
				<div class="container">
					<div class="navbar-brand">
						<a role="button" class="navbar-burger" data-target="%1$s" aria-label="menu" aria-expanded="false">
							<span aria-hidden="true"></span>
							<span aria-hidden="true"></span>
							<span aria-hidden="true"></span>
						</a>
					</div>
					<div class="navbar-menu">
						<ul class="navbar-start">
							<?php wp_list_pages(array('title_li' => '')); ?>
						</ul>
					</div>
				</div>
			</nav>
		<?php
	}

	/**
	 * Displays the not found HTML.
	 *
	 * @param string $header
	 */
	function _frozen_not_found($header = '404: Not Found!') {
		//Start output buffer.
		ob_start();

		?>
						<header>
		<?php if (is_front_page() && is_home() && get_option('show_on_front') == 'posts') { ?>
							<h2 class="subtitle"><?php _e($header, 'bonestheme'); ?></h2>
		<?php } else { ?>
							<h1 class="title"><?php _e($header, 'bonestheme'); ?></h1>
		<?php } ?>
						</header>
						<section class="content">
							<p><?php _e('Please try the following: ', 'bonestheme'); ?></p>
							<ul>
								<li>
									<?php _e('Double check the address or search terms for syntax errors.', 'bonestheme'); ?>
								</li>
								<li><?php _e('Clear your cache and cookies.', 'bonestheme'); ?></li>
								<li><?php _e('Use the search form below or adjust your search:', 'bonestheme'); ?></li>
							</ul>
							<?php get_search_form(); ?>
						</section>
						<footer>
							<p>
								<?php _e("Still can't find what you're looking for? Return to the", 'bonestheme'); ?>
								<a href="<?php print(home_url()); ?>"><?php _e('homepage', 'bonestheme'); ?>.
							</a>
						</footer>
		<?php

		//Print the output.
		print(ob_get_clean());
	}

	/**
	 * Queue scripts and styles.
	 */
	function _frozen_queue() {
		//Declare global variables.
		global	$wp_styles;

		//Declare variables.
		$styleDir	=	get_stylesheet_directory_uri() . "/css";
		$scriptDir	=	get_stylesheet_directory_uri() . "/js";
		$stylePath	=	get_stylesheet_directory() . "/css";
		$scriptPath	=	get_stylesheet_directory() . "/js";

		//If this isn't the admin panel.
		if (!is_admin()) {
			//Get all CSS files.
			$css	=	preg_grep('/login\.css/', glob("$stylePath/*.{css}", GLOB_BRACE), PREG_GREP_INVERT);

			//If there are CSS files.
			if (is_array($css) && count($css) > 0) {
				//For each CSS file.
				foreach($css as $file) {
					//Get the filename.
					$file		=	basename($file);
					$fileName	=	preg_replace('/[^a-z0-9]+/', '-', strtolower($file));

					//Register the stylesheet.
					wp_register_style($fileName, "$styleDir/$file", array(), null, 'all');

					//Queue the stylesheet.
					wp_enqueue_style($fileName);
				}
			}

			//Queue jQuery.
			wp_enqueue_script('jquery');

			//Get all JS files.
			$js		=	glob("$scriptPath/*.{js}", GLOB_BRACE);

			//If there are JS files.
			if (is_array($js) && count($js) > 0) {
				//For each JS file.
				foreach($js as $file) {
					//Get the filename.
					$file		=	basename($file);
					$fileName	=	preg_replace('/[^a-z0-9]+/', '-', strtolower($file));

					//Register scripts.
					wp_register_script($fileName, "$scriptDir/$file", array('jquery'), null, true);

					//Queue custom JS.
					wp_enqueue_script($fileName);
				}
			}

			//Queue the comment script for threaded comments.
			if (is_singular() AND comments_open() AND (get_option('thread_comments') == 1))	wp_enqueue_script('comment-reply');
		}
	}

	/**
	 * Custom WordPress search.
	 *
	 * @param string $form
	 */
	function _frozen_search_form($form = '') {
		//Start the output buffer.
		ob_start();

		?>
			<form role="search" method="get" action="<?php print(home_url('/')); ?>" class="search">
				<div class="field has-addons">
					<div class="control">
						<input type="text" name="s" value="<?php print(get_search_query()); ?>"
							placeholder="<?php _e("Search", 'bonestheme'); ?>" class="input" />
					</div>
					<div class="control">
						<input type="submit" value="<?php _e('Search', 'bonestheme'); ?>" class="button is-link" />
					</div>
				</div>
			</form>
		<?php

		//Get the output buffer.
		$form	=	ob_get_clean();

		//Return the form.
		return $form;
	}

	//If the function exists.
	if (!function_exists('_frozen_navigation_menu_class')) {
		/**
		 * Classes for navigation menues.
		 */
		function _frozen_navigation_menu_class($classes, $item, $args) {
			//Add the default class.
			$classes[]		=	'navbar-item';

			//If this is a parent of a child.
			if (!empty($item -> current_item_parent) || !empty($item -> current_item_ancestor)) {
				//Add the dropdown class.
				$classes[]	=	'has-dropdown';
			}

			//Return the classes.
			return $classes;
		}
	}

	//If the function exists.
	if (!function_exists('_frozen_wp_list_pages_menu_class')) {
		/**
		 * Classes for wp list pages menues.
		 */
		function _frozen_wp_list_pages_menu_class($output, $r, $pages) {
			//Adjust the output and return.
			return str_replace('page-item-', 'navbar-item page-item-', $output);
		}
	}

	/**
	 * Header navigation.
	 */
	function headerNavigation() {
		//Output navigation.
		wp_nav_menu(array(
				'container' => false,
				'container_class' => false,
				'menu' => __('Header Navigation', 'bonestheme'),
				'menu_class' => 'nav-main',
				'theme_location' => 'sub-nav',
				'before' => '',
				'after' => '',
				'link_before' => '',
				'link_after' => '',
				'items_wrap' => '<nav id="%1$s" class="navbar is-light" role="navigation" aria-label="'
									. __('header navigation') . '">
									<div class="container">
										<div class="navbar-brand">
											<a role="button" class="navbar-burger" data-target="%1$s" aria-label="menu"
												aria-expanded="false">
												<span aria-hidden="true"></span>
												<span aria-hidden="true"></span>
												<span aria-hidden="true"></span>
											</a>
										</div>
										<div class="navbar-menu">
											<ul id="%2$s" class="navbar-end">
												%3$s
											</ul>
										</div>
									</div>
								</nav>',
				'depth' => 0,
				'fallback_cb' => '_frozen_wasteland'
				));
	}

	/**
	 * Footer navigation.
	 */
	function footerNavigation() {
		//Output navigation.
		wp_nav_menu(array(
				'container' => false,
				'container_class' => false,
				'menu' => __('Footer Navigation', 'bonestheme'),
				'menu_class' => 'nav-main',
				'theme_location' => 'footer-nav',
				'before' => '',
				'after' => '',
				'link_before' => '',
				'link_after' => '',
				'items_wrap' => '<nav id="%1$s"><ul id="%2$s">%3$s</ul></nav>',
				'depth' => 1,
				'fallback_cb' => '_frozen_wasteland'
				));
	}

	/**
	 * Main navigation.
	 */
	function mainNavigation() {
		//Get the custom logo ID.
		$logoID	=	get_theme_mod('custom_logo');

		//If there is a logo ID.
		if ($logoID) {
			//Get the attachment image source.
			$attachments	=	wp_get_attachment_image_src($logoID, 'full');

			//If there is an attachment.
			if (isset($attachments[0]) && $attachments[0]) {
				//Set the image.
				$image		=	$attachments[0];
			}
		}

		//Set the image.
		$image	=	(!isset($image)) ? sprintf("%s/images/logo.png", get_bloginfo('template_directory')) : $image;

		//Set the image html.
		$html	=	sprintf('<img src="%s" alt="%s %s" />', $image, get_bloginfo('name'), __('logo'));

		//Output navigation.
		wp_nav_menu(array(
				'container' => false,
				'container_class' => false,
				'menu' => __('Main Navigation', 'bonestheme'),
				'menu_class' => 'nav-main',
				'theme_location' => 'main-nav',
				'before' => '',
				'after' => '',
				'link_before' => '',
				'link_after' => '',
				'items_wrap' => '<nav id="%1$s" class="navbar is-light" role="navigation" aria-label="'
								. __('header navigation') . '">
								<div class="container">
									<div class="navbar-brand">
										<a href="' . home_url() . '" rel="nofollow" class="navbar-item">' . $html . '</a>
										<a role="button" class="navbar-burger" data-target="%1$s" aria-label="menu"
											aria-expanded="false">
											<span aria-hidden="true"></span>
											<span aria-hidden="true"></span>
											<span aria-hidden="true"></span>
										</a>
									</div>
									<div class="navbar-menu">
										<ul id="%2$s" class="navbar-start">
											%3$s
										</ul>
									</div>
								</div>
							</nav>',
				'depth' => 0,
				'fallback_cb' => '_frozen_navigation'
				));
	}

	//Add filters.
	add_filter('nav_menu_css_class', '_frozen_navigation_menu_class', 1, 3);
	add_filter('wp_list_pages','_frozen_wp_list_pages_menu_class', 10, 3);
?>