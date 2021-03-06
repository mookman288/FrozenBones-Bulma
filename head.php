<!DOCTYPE html>
<html <?php language_attributes(); ?> class="no-js" data-template="<?php bloginfo('template_directory'); ?>">
	<head>
		<title><?php wp_title('|', true, 'right'); ?></title>
		<meta charset="<?php bloginfo( 'charset' ); ?>" />
		<meta http-equiv="X-UA-Compatible" content="IE=edge" />
		<meta name="author" content="<?php bloginfo('name'); ?>" />
		<meta name="HandheldFriendly" content="True" />
		<meta name="MobileOptimized" content="320" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0" />
		<link rel="icon" href="<?php echo bloginfo('template_directory'); ?>/images/favicon.png" />
		<link rel="shortcut icon" href="<?php bloginfo('template_directory'); ?>/favicon.ico" />
		<link rel="profile" href="http://gmpg.org/xfn/11" />
		<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
		<?php wp_head(); ?>
	</head>