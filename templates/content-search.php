	<?php 
		global $woo_options; 
		if ( apply_filters( 'woo_nav_search', true ) && ( isset( $woo_options['woo_nav_search'] ) && 'true' == $woo_options['woo_nav_search'] ) ) { 
	?>
		<div class="menu-search">
			<?php
				$args = array(
					'title' => ''
				);
				
				if ( isset( $woo_options['woo_header_search_scope'] ) && 'products' == $woo_options['woo_header_search_scope'] ) {
					the_widget( 'WC_Widget_Product_Search', $args );
				} else {
					the_widget( 'WP_Widget_Search', $args );
				}
			?>
		</div>
	<?php } ?>