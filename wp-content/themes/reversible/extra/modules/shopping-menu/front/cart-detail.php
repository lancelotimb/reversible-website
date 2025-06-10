<div id="cart-detail-overlay"></div>
<div id="cart-detail-container">
	<div class="overlay-top"></div>
	<div class="scrollable">
		<div class="inner">
			<div class="message message-loading"></div>

			<div class="cart-detail">
				<ul class="cart-products"></ul>
			</div>

			<div class="message message-empty">
				<?php
				$shop_url = get_permalink(wc_get_page_id('shop'));
				?>
				<?php _e('Votre panier est vide...', 'extra'); ?><br>
				<span class="go-to-shop"><?php echo sprintf(__('Vite, <a href="%s" class="cart-shop-link">direction la boutique !</a>', 'extra'), $shop_url); ?></span>
			</div>

			<div class="footer">
				<div class="inner">
					<div class="price">
						<div class="price-inner"></div>
					</div>
					<?php
					global $woocommerce;
					$url = $woocommerce->cart->get_checkout_url();
					?>
					<a href="<?php echo $url; ?>" class="buy-link big-shop-button<?php echo (is_checkout()) ? ' disabled' : ''; ?>">
						<span class="inner">
							<?php _e("Commander", 'extra'); ?>
						</span>
					</a>
				</div>
			</div>
		</div>
	</div>
</div>