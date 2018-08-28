			<footer class="section">
				<div class="container has-text-centered">
					<?php footerNavigation(); ?>
					<p class="copyright">
						<?php _e('Copyright', 'bonestheme'); ?> &copy; <?php echo date('Y'); ?>
						<?php bloginfo('name'); _e('.'); ?> <?php _e('All Rights Reserved.'); ?>
					</p>
				</div>
			</footer>
<?php require_once(get_template_directory() . '/foot.php'); ?>