				<footer id="footer">
					<?php get_template_part('extra/modules/totop/front/totop'); ?>
					<div class="footer-wrapper">
						<div class="first-line line">
							<?php get_template_part('extra/modules/footer/front/values'); ?>
						</div>

						<div class="second-line line">
							<?php get_template_part('extra/modules/footer/front/filters'); ?>
							<nav id="menu-footer-wrapper" class="column column-four">
								<h4><?php _e("Compte & informations", 'extra'); ?></h4>
								<?php
								/**********************
								 *
								 * NAVIGATION
								 *
								 *********************/
								$args = array(
									'theme_location' 	=> 'footer',
									'menu_class'		=> 'menu-footer',
									'menu_id'			=> 'menu-footer',
									'container'			=> false
								);
								wp_nav_menu($args); ?>
							</nav><!-- .menu-footer-wrapper -->

							<?php get_template_part('extra/modules/footer/front/contact'); ?>
						</div>
					</div>

					<div class="subfooter">
						<div class="third-line line">
							<?php get_template_part('extra/modules/footer/front/logo'); ?>
							<?php get_template_part('extra/modules/footer/front/newsletter'); ?>
							<?php get_template_part('extra/modules/footer/front/social-network'); ?>
						</div>
					</div>
				</footer><!-- #footer -->
				<?php
				// TODO DO NOT WORK TO DISPLAY TRANSACTION MESSAGES
				//get_template_part("extra/modules/flash-messages/front/flash-messages");
				?>
			</div><!-- #wrapper -->

			<?php wp_footer(); ?>

		</div><!-- #table-wrapper -->
	</div><!-- #scrollable -->
</div><!-- #scrollable-wrapper -->
</body>
</html>