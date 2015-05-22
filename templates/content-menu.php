	<div class="main-menu-fixed">
		<div class="main-menu">
				
			<!-- Mobile Menu -->
			<a id="mobile-menu" class="left-off-canvas-toggle" href="#" >MENU
				<span class="mobile-menu-icon">
					<span class="mobile-menu-iconbar"></span>
					<span class="mobile-menu-iconbar"></span>
					<span class="mobile-menu-iconbar"></span>
				</span>
			</a>
			<!-- Off Canvas Menu -->
			<aside class="left-off-canvas-menu">
				<?php get_template_part('templates/content', 'search'); ?>
					
				<?php 
				if ( function_exists('has_nav_menu') && has_nav_menu('new-menu') ) {
					wp_nav_menu( array( 'depth' => 6, 'sort_column' => 'menu_order', 'container' => 'ul', 'menu_id' => 'off-canvas-list1', 'menu_class' => 'off-canvas-list', 'theme_location' => 'new-menu' ) );
				} ?>
					
				<?php
					wp_nav_menu( array( 'depth' => 5, 'sort_column' => 'menu_order', 'container' => 'ul', 'menu_id' => 'off-canvas-list2', 'menu_class' => 'off-canvas-list', 'theme_location' => 'top-menu' ) );
				?>	
			</aside>
		
			<!-- Destop Menu -->
			<div class="f-row primary-navigation">
				<?php if ( function_exists('has_nav_menu') && has_nav_menu('new-menu') ) {
					wp_nav_menu( array( 'depth' => 6, 'sort_column' => 'menu_order', 'container' => 'ul', 'menu_id' => 'new-nav', 'menu_class' => 'nav-menu', 'theme_location' => 'new-menu' ) );
					
					get_template_part('templates/content', 'search');
				} ?>
				
			</div>
		</div>
		
		<div class="main-menu-bottom">
			<div class="f-row">
				<div class="large-12 columns">	
					<?php
						wp_nav_menu( array( 'depth' => 6, 'sort_column' => 'menu_order', 'container' => 'ul', 'menu_id' => 'top-nav', 'menu_class' => 'nav top-navigation', 'theme_location' => 'top-menu' ) );
					?>	
				</div>
			</div>
		</div>
	</div>