	<div class="header">
		<div class="f-row">
			<div class="medium-6 columns">	
				<div class="logo">
				<?php 
					$site_url = home_url( '/' );
					$site_title = get_bloginfo( 'name' );
					$site_description = get_bloginfo( 'description' );
					$settings = woo_get_dynamic_values( array( 'logo' => '') );
					if ( ( '' != $settings['logo'] ) ) {
						$logo_url = $settings['logo'];
						if ( is_ssl() ) $logo_url = str_replace( 'http://', 'https://', $logo_url );
						echo '<a href="' . esc_url( $site_url ) . '" title="' . esc_attr( $site_description ) . '"><img src="' . esc_url( $logo_url ) . '" alt="' . esc_attr( $site_title ) . '" /></a>' . "\n";
					} 
				?>				
				</div>
			</div>
			<div class="medium-6 columns">	
				<ul class="contact-us">
					<li>
						<a href="tel://1300135941" class="phone"><i class="fa fa-phone"></i> 1300 135 941</a>
						<a href="tel://61894123000" class="phone"><i class="fa fa-phone"></i><i class="fa fa-globe"></i> (+61) 08 9412 3000</a>
						<!-- <a href="tel://61477764985" class="phone">SMS <i class="fa fa-mobile"></i> (+61) 04 7776 4985</a> -->
					</li>
					<!--li>
						<ul class="social-icons">
							<li><a href="" class="facebook"></a></li>
							<li><a href="" class="youtube"></a></li>
							<li><a href="" class="instagram"></a></li>
							<li><a href="" class="gmail"></a></li>
						</ul>
					</li-->
				</ul>
				<?php 
					$retail_url = esc_url( home_url( '/shop/retail/' ) );
					$trade_url = esc_url( home_url( '/shop/trade/' ) );
					$shop_url = esc_url( home_url( '/shop/' ) );
					if(tanvas_is_user_wholesale()){ ?>
						<a href="<?php echo $shop_url; ?>" class="shop">TRADE STORE</a>
					<?php } else { ?>
						<div id="split-shop-container">
							<a href="<?php echo $trade_url; ?>" class="shop split-shop">TRADE STORE</a>
							<a href="<?php echo $retail_url ; ?>" class="shop split-shop">RETAIL STORE</a>
						</div>
					<?php }
				?>
			</div>
		</div>
	</div>