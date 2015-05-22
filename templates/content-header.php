	<div class="header">
		<div class="f-row">
			<div class="medium-6 columns">	
				<div class="logo">
				<?php 
					$settings = woo_get_dynamic_values( array( 'logo' => '' ) );
					if ( ( '' != $settings['logo'] ) ) {
					$logo_url = $settings['logo'];
					if ( is_ssl() ) $logo_url = str_replace( 'http://', 'https://', $logo_url );

					echo '<a href="' . esc_url( $site_url ) . '" title="' . esc_attr( $site_description ) . '"><img src="' . esc_url( $logo_url ) . '" alt="' . esc_attr( $site_title ) . '" /></a>' . "\n";
					} // End IF Statement
				?>				
				</div>
			</div>
			<div class="medium-6 columns">	
				<ul class="contact-us">
					<li class="first">
						<!--a href="tel://61894123000" class="phone">+618 9412 3000</a-->
						<a href="tel://1300135941" class="phone">1300 135 941</a>
					</li>
					<li>
						<ul class="social-icons">
							<li><a href="" class="facebook"></a></li>
							<li><a href="" class="youtube"></a></li>
							<li><a href="" class="instagram"></a></li>
							<li><a href="" class="gmail"></a></li>
						</ul>
					</li>
				</ul>
				<a href="<?php echo esc_url( $site_url );?>shop/" class="shop">SHOP ONLINE</a>
			</div>
		</div>
	</div>