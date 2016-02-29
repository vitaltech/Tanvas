	<div id="<?php if ( is_user_logged_in() ) { echo 'registered-user'; } else { echo 'visitor'; } ?>" class="top-header">
		<div class="f-row">

			<div class="small-12 columns">
				<ul class="woo-contact-us">
					<li id="cphone">
						<!-- <a href="tel://0477764985" class="cphone">(+61) 0477 764 985 </a> -->
						<a href="tel://61477764985" class="cphone">SMS <i class="fa fa-mobile"></i> (+61) 0477 764 985</a> 
					</li>
					<li class="border">
						<!--a href="tel://1300135941" class="call">1300 135 941</a-->
						<p class="woo-link">
						<?php 
						$account_url = get_site_url(0,"my-account");
						if( is_user_logged_in() ){
							global $current_user;
							get_currentuserinfo();
							$display_name = $current_user->display_name;
							echo "Hello <strong>".$display_name."</strong>";
							$user_ID = $current_user->ID;
							$user = new WP_User( $user_ID );
							if ( !empty( $user->roles ) && is_array( $user->roles ) ) {
								echo " (" . $user->roles[0] .")";
							}

							$logout_url = wp_logout_url();
							$admin_url = get_admin_url();
							echo " | <a rel='nofollow' href='$account_url'>My Account</a>";
							if(is_admin()){
								echo " | <a rel='nofollow' href='$admin_url'>Admin</a>";
							}
							echo " | <a rel='nofollow' href='$logout_url'>Log Out</a>";
						} else {
							
							$location= $_SERVER["REQUEST_URI"];
							$login_url = wp_login_url( str_replace($account_url,$location,$account_url ));
							
							// $login_url = $account_url;
							$help_url = get_site_url(0,"my-account/help");
							$request_url = wp_registration_url(); 
							echo "<a rel='nofollow' href='$login_url'>Log In</a>";
							echo " | <a rel='nofollow' href='$request_url'>Register</a>";
							echo " | <a rel='nofollow' href='$help_url'>Account Help</a>";
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
