	<?php 
		global $woo_options; 
		if ( apply_filters( 'woo_nav_search', true ) && ( isset( $woo_options['woo_nav_search'] ) && 'true' == $woo_options['woo_nav_search'] ) ) { 
	?>
		<div class="menu-search">
			<div class="widget widget_search">
				<div class="search_main">
					<form method="get" class="searchform" action="<?php echo esc_url( home_url() ); ?>">
						<input type="text" id="s" name="s" placeholder="Search..." class="field s" value="" required="">
						<button type="submit" class="fa fa-search submit" name="submit" value="Search"></button>
					</form>
					<div class="fix"></div>
				</div>
			</div>
		</div>
	<?php } ?>