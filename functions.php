<?php
// TODO: Allow discounts to be specified based on how many liters of solution
	
/**
 * TODO: Registers home page template widget areas
 */
function tanvas_widgets_init() {
	// register_sidebar( array(
	// 	'name' 	=> 'Home Doorway Buttons'))
}

/**
 * Adds social media and newsletter icons to nav menu
 */
function tanvas_add_social_icons () {
	echo "<ul><li class='fr fa-facebook'><i class='facebook-official'></i></lu></ul>";
}
//add_action( 'woo_nav_inside', 'tanvas_add_social_icons', 20);

function woo_options_add($options){
	//if(WP_DEBUG) error_log("woo_options_add called with :".serialize($options));
	return $options;
}

?>