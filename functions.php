<?php

define( 'TANVAS_DOMAIN', 'tanvas');
if(!defined('TANVAS_DEBUG') ){
	define( 'TANVAS_DEBUG', 'true');
}


include_once('widgets/doorway-button-widget.php');
include_once('widgets/custom-latest-posts-widget.php');
include_once('widgets/custom-social-media-widget.php');
include_once('widgets/woocommerce-my-account-widget.php');
include_once('includes/warnings.php');
include_once('includes/PNG_Reader.php');

$woo_options = get_option( 'woo_options' );

// TODO: Allow discounts to be specified based on how many liters of solution

/* pretends to be canvas then quits if woocommerce not installed */
function theme_enqueue_styles(){
	wp_enqueue_style('foundation', get_stylesheet_directory_uri() . '/css/foundation.css' );
	wp_enqueue_style('owl.carousel', get_stylesheet_directory_uri() . '/css/owl.carousel.css');
	wp_enqueue_style('owl.theme', get_stylesheet_directory_uri() . '/css/owl.theme.css');
	wp_enqueue_style('design-style', get_stylesheet_directory_uri() . '/design-style.css');
	
	wp_enqueue_style('parent-style', get_template_directory_uri() . '/style.css' );
	wp_enqueue_style('flexboxgrid', get_stylesheet_directory_uri() . '/css/flexboxgrid.css');

	// wp_enqueue_style('this-style', get_stylesheet_uri() );
	wp_enqueue_style( 'prefix-font-awesome', '//netdna.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css', array(), '4.4.0' );
	global $is_IE;
	if ( $is_IE ) {
	    wp_enqueue_style( 'prefix-font-awesome-ie', '//netdna.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome-ie7.min.css', array('prefix-font-awesome'), '4.4.0' );
	    // Add IE conditional tags for IE 7 and older
	    global $wp_styles;
	    $wp_styles->add_data( 'prefix-font-awesome-ie', 'conditional', 'lte IE 7' );
	}
}
add_action('wp_enqueue_scripts', 'theme_enqueue_styles');
	
function Tanvas_noticeWoocommerceNotInstalled() {
    echo 
        '<div class="updated fade">' .
        __('Error: Theme "Tanvas" requires WooCommerce to be installed',  'LaserCommerce') .
        '</div>';
}

function Tanvas_WoocommerceCheck() {
    if( !in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
        add_action('admin_notices', 'Tanvas_noticeWoocommerceNotInstalled');
        return false;
    }
    return true;
}

function Tanvas_noticeLasercommerceNotInstalled() {
    echo 
        '<div class="updated fade">' .
        __('Error: Theme "Tanvas" requires LaserCommerce to be installed',  'LaserCommerce') .
        '</div>';
}

function Tanvas_LasercommerceCheck() {
    if( !in_array( 'lasercommerce/lasercommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
        add_action('admin_notices', 'Tanvas_noticeLasercommerceNotInstalled');
        return false;
    }
    return true;
}

if(!Tanvas_WoocommerceCheck() or !Tanvas_WoocommerceCheck()) {
	return;
}

/**
 * Log In Mods
 */
function tanvas_login_message(){
	echo "<p>Forgot your email? <a href='/contact-us'>Contact head office</a></p>";
}

add_action('login_form', 'tanvas_login_message');


/**
 * Demo Store Notice Mods
 */

/* Loads script to move site notice to within wrapper */
add_action('wp_enqueue_scripts', function(){
		wp_enqueue_script( 
			'reposition-site-message', 
			get_stylesheet_directory_uri().'/js/reposition-site-message.js',
			array('jquery'),
			0.1
		);
	}
);

/* Ensure slider js is loaded */
add_filter('woo_load_slider_js', function($load_slider_js){
	// if(WP_DEBUG) error_log("woo_load_slider_js filter called wit h" . ($load_slider_js?"T":"F"));
	if(is_page_template( 'template-home.php')){
		$load_slider_js = true;
	} 
	// if(WP_DEBUG) error_log("woo_load_slider_js returning ". ($load_slider_js?"T":"F"));
	return $load_slider_js;

}, 999, 1);

function tanvas_widgets_init() {
	register_sidebar( array(
		'name' 			=> 'Home Doorway Buttons',
		'id' 			=> 'tanvas_home_doorway',
		'before_widget'	=> '<li><div class="doorway-container">',
		'after_widget'	=> '</div></li>',
		'before_title'	=> '<h5 class="doorway-title">',
		'after_title'	=> '</h5>'
	));
	register_sidebar( array (
		'name' 			=> 'Home Doorway Sidebar',
		'id'			=> 'tanvas_home_doorway_sidebar',
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget'	=> '</div>',
		'before_title'	=> '<h2>',
		'after_title'	=> '</h2>'
	));
	register_sidebar( array (
		'name' 			=> 'Home Below Doorway Left',
		'id'			=> 'tanvas_home_left',
		'before_widget' => '<div class="widget">',
		'after_widget'	=> '</div>',
		'before_title'	=> '<h2>',
		'after_title'	=> '</h2>'
	));	
	register_sidebar( array (
		'name' 			=> 'Home Below Doorway Right',
		'id'			=> 'tanvas_home_right',
		'before_widget' => '<div class="widget">',
		'after_widget'	=> '</div>',
		'before_title'	=> '<h2>',
		'after_title'	=> '</h2>'
	));	
	register_sidebar( array (
		'name' 			=> 'Home Doorway Bottom',
		'id'			=> 'tanvas_home_doorway_bottom',
		'before_widget' => '<div class="widget-doorway-bottom">',
		'after_widget'	=> '</div>',
		'before_title'	=> '<h2>',
		'after_title'	=> '</h2>'
	));	
	
	register_sidebar(array(
		'name' => __( 'Footer Widgets One', TANVAS_DOMAIN ),
		'id' => 'widget-one',
		'before_widget' => '<div class="footer-wigget">',
		'after_widget' => '</div>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>'
	));
	
	register_sidebar(array(
		'name' => __( 'Footer Widgets Two', TANVAS_DOMAIN ),
		'id' => 'widget-two',
		'before_widget' => '<div class="footer-wigget">',
		'after_widget' => '</div>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>'
	));
	
	register_sidebar(array(
		'name' => __( 'Footer Widgets Three', TANVAS_DOMAIN ),
		'id' => 'widget-three',
		'before_widget' => '<div class="footer-wigget">',
		'after_widget' => '</div>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>'
	));
	
	register_sidebar(array(
		'name' => __( 'Footer Widgets Four', TANVAS_DOMAIN ),
		'id' => 'widget-four',
		'before_widget' => '<div class="footer-wigget">',
		'after_widget' => '</div>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>'
	));
	
	register_sidebar(array(
		'name' => __( 'Footer Widgets Five', TANVAS_DOMAIN ),
		'id' => 'widget-five',
		'before_widget' => '<div class="footer-wigget">',
		'after_widget' => '</div>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>'
	));

	register_widget( 'lc_doorway_button');
	register_widget( 'CUSTOM_SOCIAL_MEDIA_WIDGETS' );
	register_widget( 'CUSTOM_LATEST_POSTS_WIDGETS' );
	register_widget( 'WooCommerceMyAccountWidget');

}
add_action('widgets_init', 'tanvas_widgets_init');



/**
 * Adds social media and newsletter icons to nav menu
 */

// function tanvas_add_social_icons () {
// 	echo "<ul><li class='fr fa-facebook'><i class='facebook-official'></i></lu></ul>";
// }
//add_action( 'woo_nav_inside', 'tanvas_add_social_icons', 20);

// function woo_options_add($options){
// 	//if(WP_DEBUG) error_log("woo_options_add called with :".serialize($options));
// 	return $options;
// }

/**
 * Product Category / Taxonomy Display Mods
 */

/** Add category image to category archive page */

// add_action( 'woocommerce_archive_description', 'woocommerce_category_image', 2 );
function woocommerce_category_image() {
    if ( is_product_category() ){
	    global $wp_query;
	    $cat = $wp_query->get_queried_object();
	    $thumbnail_id = get_woocommerce_term_meta( $cat->term_id, 'thumbnail_id', true );

	    // if( $thumbnail_id ){
	    // 	echo wp_get_attachment_image( $thumbnail_id, 'full' );
	    // }

	    // echo "<h2>" . __("subcategories") . "</h2>";

	    /*$image = wp_get_attachment_url( $thumbnail_id );
	    if ( $image ) {
		    echo '<img src="' . $image . '" alt="" />';
		}*/
	}
}

/** Add log in warning to category **/


/** Removes sort by dropdown **/
remove_action( 'woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30 );


/** allow html in category / tax descriptions */

foreach ( array( 'pre_term_description' ) as $filter ) {
    remove_filter( $filter, 'wp_filter_kses' );
}
 
foreach ( array( 'term_description' ) as $filter ) {
    remove_filter( $filter, 'wp_kses_data' );
}

/* add category description after subcategory title */

// add_action('woocommerce_before_subcategory', function($category){
// 	if(is_product_category() && !woocommerce_products_will_display()){
// 		echo "<style>body.archive.tax-product_cat ul.products { display: table; } </style>";
// 	}
// });

// add_action('woocommerce_before_subcategory_title', function($category){

// 	if(is_product_category() && !woocommerce_products_will_display()){
// 		echo '<div class="product-category-description">';
// 	}
// });

// add_action('woocommerce_after_subcategory_title', function($category){

// 	if(is_product_category() && !woocommerce_products_will_display()){
// 		$desc = esc_attr($category->description);
// 		echo "<p>$desc</p>";
// 		echo '</div> <!-- end product-category-description-->';
// 	}
// });

// add_filter(
// 	'loop_shop_columns', 
// 	function($cols){
// 		if(is_product_category() && !woocommerce_products_will_display()){
// 			return 1;
// 		} else {
// 			return $cols;
// 		}
// 	},
// 	999, 
// 	1
// );


/**
 * Dynamic pricing customization
 */

// function tanvas_remove_dynamic_cumulative( $default, $module_id, $cart_item, $cart_item_key){
// 	// error_log("tanvas dynamic cumulative: ");
// 	// error_log(" -> def: ".serialize($default));
// 	// error_log(" -> mod: ".serialize($module_id));
// 	// error_log(" -> car: ".serialize($cart_item));
// 	// error_log(" -> cak: ".serialize($cart_item_key));
// 	return $default;
// }

// add_filter('woocommerce_dynamic_pricing_is_cumulative', 'tanvas_remove_dynamic_cumulative', 10, 4);

/**
 * Login Customizations
 */

function my_login_logo() { ?>
    <style type="text/css">
        .login h1 a {
            background-image: url(<?php echo get_stylesheet_directory_uri(); ?>/img/TechnoTan-Logo.png);
            padding-bottom: 30px;
            background-size: 240px;
            width: 240px;
            padding-bottom: 0px;
        }
    </style>
<?php }
add_action( 'login_enqueue_scripts', 'my_login_logo' );

function my_login_stylesheet() {
    wp_enqueue_style( 'custom-login', get_stylesheet_directory_uri() . '/css/style-login.css' );
}
add_action( 'login_enqueue_scripts', 'my_login_stylesheet' );

/**
 * change loop shop columns
 */
// THIS IS NOT NEEDED NOW THAT WE USE PRODUCT ARCHIVE CUSTOMIZER
// if (!function_exists('change_loop_columns')) {
// 	function change_loop_columns() {
// 		return 3; // 3 products per row
// 	}
// }
// add_filter('loop_shop_columns', 'change_loop_columns', 999, 3);

// change the css if there are 3 columns
// function inject_column_css(){
// 	// if(WP_DEBUG) error_log("called inject_column_css callback");	
// 	$columns = apply_filters( 'loop_shop_columns', 3);
// 	// if(WP_DEBUG) error_log("-> columns: $columns");
// 	if ($columns == 3 ){ 
// 		<!-- <style type="text/css">
// 		ul.products li.product {
// 			width: 30%;
// 		}
// 		</style> -->
// 	  }
// }
// add_action('woocommerce_before_shop_loop', 'inject_column_css', 999, 0);
/**
 * title customizations for products in category
 */

function shrink_product_title($title, $id){
	// if(WP_DEBUG) error_log("called shrink_product_title callback | title: $title, id: $id");
	if(is_product_category()){
		$title_length = strlen($title);
		$title = preg_replace("/^(.*)( &#8212; | - | &#8211; | — )(.*)$/u", '<span>$1</span><small>$3</small>', $title );
		if ($title_length > 64){
			$title = "<span class='long-title'>".$title."</span>";
		}
	}
	// if(WP_DEBUG) error_log("-> returning $title");
	return $title;
}
add_action('the_title', 'shrink_product_title', 9999, 2);


/*
 * wc_remove_related_products
 * 
 * Clear the query arguments for related products so none show.
 * Add this code to your theme functions.php file.  
 */
// function wc_remove_related_products( $args ) {
// 	return array();
// }
// add_filter('woocommerce_related_products_args','wc_remove_related_products', 10);

//TODO: show "available in ..." on variable product page
//TODO: Change "Select Options" and "READ MORE" to "VIEW" when product is unavailable

/**
 * Clear Attribute Select box if no available Variations
 */

// do_action( 'woocommerce_before_add_to_cart_form' ); 
function maybe_clear_attribute_select_box( ) { 
	global $product;
	if( isset($product) and $product->is_type('variable')){
		$available_variations = $product->get_available_variations();
		$any_available = False;
		foreach ($available_variations as $variation_data) {
			if(isset($variation_data['variation_is_visible']) and $variation_data['variation_is_visible']){
				$any_available = True;
				//stop output of option box
			}
		}
		if(!$any_available){ ?>
			<p><?php echo __('Please', 'tanvas') . " <a href=''>" . __('sign in', 'tanvas') . "</a> " . __('or', 'tanvas') . " <a href=''>". __('register', 'tanvas'). "</a> ". __("to view prices", 'tanvas') ?></p>
			<style type="text/css">
				form.variations_form.cart{
					display:none;
				}
			</style>
		<?php }
	}
}
add_action('woocommerce_before_add_to_cart_form', 'maybe_clear_attribute_select_box');

function tanvas_output_login_help(){
	$help_link = get_site_url(0,"/my-account/help");
	echo do_shortcode( '[button link="'.$help_link.'" bg_color="#d1aa67"]account help[/button]');
}

add_action( 'woocommerce_login_form_end', 'tanvas_output_login_help');

add_action( 'init', 'register_my_menu' );
function register_my_menu() {
    register_nav_menu( 'new-menu', __( 'New Menu' ) );
}

/**
 * Woocommerce cart prices notice
 */
function tanvas_output_cart_price_notice(){
	echo do_shortcode(
		'[box type="info"]'.
			__('Dear customer our new cart has just been launched, while we have endeavored to ensure all pricing is correct we reserve the right to revise all pricing in line with our current listed prices. We thank you for your understanding.', TANVAS_DOMAIN).'<br/>'.
		'[/box]'		
	);
}
add_action( 'woocommerce_before_cart', 'tanvas_output_cart_price_notice');


//custom excerpt length
function excerpt($limit) {
  $excerpt = explode(' ', get_the_excerpt(), $limit);
  if (count($excerpt)>=$limit) {
    array_pop($excerpt);
    $excerpt = implode(" ",$excerpt).'...';
  } else {
    $excerpt = implode(" ",$excerpt);
  }
  $excerpt = preg_replace('`[[^]]*]`','',$excerpt);
  return $excerpt;
}

// Fix twitter button

function lc_shortcode_twitter($atts, $content = null) {
   	global $post;
   	extract(shortcode_atts(array(	'url' => '',
   									'style' => '',
   									'source' => '',
   									'text' => '',
   									'related' => '',
   									'lang' => '',
   									'float' => 'left',
   									'use_post_url' => 'false',
   									'recommend' => '',
   									'hashtag' => '',
   									'size' => '',
   									 ), $atts));
	$output = '';

	if ( $url )
		$output .= ' data-url="' . esc_url( $url ) . '"';

	if ( $source )
		$output .= ' data-via="' . esc_attr( $source ) . '"';

	if ( $text )
		$output .= ' data-text="' . esc_attr( $text ) . '"';

	if ( $related )
		$output .= ' data-related="' . esc_attr( $related ) . '"';

	if ( $hashtag )
		$output .= ' data-hashtags="' . esc_attr( $hashtag ) . '"';

	if ( $size )
		$output .= ' data-size="' . esc_attr( $size ) . '"';

	if ( $lang )
		$output .= ' data-lang="' . esc_attr( $lang ) . '"';

	if ( $style != '' ) {
		$output .= 'data-count="' . esc_attr( $style ) . '"';
	}

	if ( $use_post_url == 'true' && $url == '' ) {
		$output .= ' data-url="' . get_permalink( $post->ID ) . '"';
	}

	$output = '<div class="woo-sc-twitter ' . esc_attr( $float ) . '"><a href="' . esc_url( 'https://twitter.com/share' ) . '" class="twitter-share-button"'. $output .'>' . __( 'Tweet', 'woothemes' ) . '</a><script type="text/javascript" src="' . esc_url ( 'https://platform.twitter.com/widgets.js' ) . '"></script></div>';
	return $output;

} // End woo_shortcode_twitter()

add_shortcode( 'twitter-https', 'lc_shortcode_twitter' );

/**
 * Remove deprecated constructor warnings
 */
add_filter('deprecated_constructor_trigger_error', '__return_false');

/**
 * Remove product count
 */

remove_filter('woocommerce_before_shop_loop', 'woocommerce_result_count', 20);


?>
