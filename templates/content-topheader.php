	<div id="<?php if ( is_user_logged_in() ) { echo 'registered-user'; } else { echo 'visitor'; } ?>" class="top-header">
		<div class="f-row">

			<div class="small-12 columns">
				<ul class="woo-contact-us">
					<li id="cphone">
						<a href="tel://0477764985" class="cphone">(+61) 0477 764 985 </a>
					</li>
					<li class="border">
						<!--a href="tel://1300135941" class="call">1300 135 941</a-->
						<p class="woo-link">
						<?php 
						$account_url = get_site_url(0,"my-account");
						if( is_user_logged_in() ){
						$logout_url = wp_logout_url();
						$admin_url = get_admin_url();
						echo "<a href='$account_url'>My Account</a> | ";
						echo "<a href='$admin_url'>Admin</a> | ";
						echo "<a href='$logout_url'>Log Out</a>";
						echo "  ";
						global $current_user;
						get_currentuserinfo();
						$display_name = $current_user->display_name;
						echo "Hello <strong>".$display_name."</strong>";
						$user_ID = $current_user->ID;
						$user = new WP_User( $user_ID );
						if ( !empty( $user->roles ) && is_array( $user->roles ) ) {
							echo " | " . $user->roles[0];
						}
						} else {
							$login_url = wp_login_url( $account_url );
							$request_url = get_site_url(0,"create-account");;
							echo "<a href='$login_url'>Log In</a> | <a href='$request_url'>Request Account</a>";
						}
						?>
						</p>
					</li>
					<li class="woo-items">				
						<a class="bag" href="<?php echo WC()->cart->get_cart_url(); ?>" title="<?php _e( 'View your shopping cart' ); ?>">
							<?php echo sprintf (_n( '%d item', '%d items', WC()->cart->cart_contents_count ), WC()->cart->cart_contents_count ); ?>
						</a> 
						|
						<a class="" href="<?php echo WC()->cart->get_cart_url(); ?>" title="<?php _e( 'View your shopping cart' ); ?>">
							<?php echo WC()->cart->get_cart_total(); ?>
						</a>
					</li>
				</ul>	
			</div>
		</div>
	</div>