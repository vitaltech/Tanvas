<?php
// TODO: Allow discounts to be specified based on how many liters of solution

/* pretends to be canvas then quits if woocommerce not installed */
function theme_enqueue_styles(){
	wp_enqueue_style('parent-style', get_template_directory_uri() . '/style.css' );
	// wp_enqueue_style('this-style', get_stylesheet_uri() );
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
	if(WP_DEBUG) error_log("woo_load_slider_js filter called wit h" . ($load_slider_js?"T":"F"));
	if(is_page_template( 'template-home.php')){
		$load_slider_js = true;
	} 
	if(WP_DEBUG) error_log("woo_load_slider_js returning ". ($load_slider_js?"T":"F"));
	return $load_slider_js;

}, 999, 1);


/**
 *  Home Page and Doorway Button Widget / Widget Areas
 */

class lc_doorway_button extends WP_Widget
{
	
	function __construct()
	{
		parent::__construct(
			'lc_doorway_button',
			__('Doorway Button', 'lasercommerce'),
			array(
				'description' => __('Widget for adding doorway buttons to home page', 'lasercommerce'),
			)
		);
	}

	public function widget( $args, $instance ){
		$title = apply_filters( 'widget_title', $instance['title']);
		if(isset($instance['url'])){
			$url = esc_attr($instance['url']);
		} else {
			$url = '';
		}
		if(isset($instance['img'])){
			$img = esc_attr($instance['img']);
		} else {
			$img = '';
		}
		if( !$img ){
			$img = 'http://staging.technotan.com.au/wp-content/themes/tanvas/img/logo-transparent-zoomed-out.png';
		}
		if(isset($instance['alt'])){
			$alt = esc_attr($instance['alt']);
		} else {
			$alt = '';
		}		

		if( !$alt ){
			$alt=$title;
		} 

		echo $args['before_widget'];
		echo "<a href='$url' >";
		echo "<img src='$img' alt='$alt' width=180 max-width=300, max-height=300>";
		if( !empty($title) ){
			echo $args['before_title'] . $title . $args['after_title'];
		}
		echo "</a>";
		echo $args['after_widget'];
	}

	public function form ( $instance ) {
		if( isset($instance['title'])) {
			$title = $instance['title'];
		} else {
			$title = __('New Title', 'lasercommerce');
		}

		if(isset($instance['url'])){
			$url = $instance['url'];
		} else {
			$url = '';
		}

		if(isset($instance['img'])){
			$img = $instance['img'];
		} else {
			$img = '';
		}

		if(isset($instance['alt'])){
			$alt = $instance['alt'];
		} else {
			$alt = '';
		}

		?>
		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:' ); ?></label>
			<input 
				class="widefat" 
				id="<?php echo $this->get_field_id('title'); ?>" 
				name="<?php echo $this->get_field_name('title'); ?>" 
				type="text" 
				value="<?php echo esc_attr($title); ?>" 
			/>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('url'); ?>"><?php _e('URL:' ); ?></label>
			<input
				class="widefat"
				id="<?php echo $this->get_field_id('url'); ?>"
				name="<?php echo $this->get_field_name('url');?>"
				type="text"
				value="<? echo esc_attr($url); ?>"
			/>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('img'); ?>"><?php _e('Image:'); ?></label>
			<input
				class="widefat"
				id="<?php echo $this->get_field_id('img');?>"
				name="<?php echo $this->get_field_name('img') ; ?>"
				type="text"
				value="<?php echo esc_attr($img); ?>"
			/>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('alt'); ?>"><?php _e('Alt Text:'); ?></label>
			<input
				class="widefat"
				id="<?php echo $this->get_field_id('alt');?>"
				name="<?php echo $this->get_field_name('alt') ; ?>"
				type="text"
				value="<?php echo esc_attr($alt); ?>"
			/>
		</p>
		<?php
	}

	public function update($new_instance, $old_instance)
	{
		$instance = array();
		$instance['title'] = ( ! empty($new_instance['title'])) ? strip_tags( $new_instance['title']) : '';
		$instance['url'] = ( ! empty($new_instance['url'])) ? strip_tags( $new_instance['url']) : '' ;
		$instance['img'] = ( ! empty($new_instance['img'])) ? strip_tags( $new_instance['img']) : '' ;
		$instance['alt'] = ( ! empty($new_instance['alt'])) ? strip_tags( $new_instance['alt']) : '' ;
		return $instance;
	}
}

function tanvas_widgets_init() {
	register_sidebar( array(
		'name' 			=> 'Home Doorway Buttons',
		'id' 			=> 'tanvas_home_doorway',
		'before_widget'	=> '<div class="widget doorway flex-item">',
		'after_widget'	=> '</div>',
		'before_title'	=> '<h2>',
		'after_title'	=> '</h2>'
	));
	// register_sidebar( array(
	// 	'name' 			=> 'Home Doorway Column 1',
	// 	'id' 			=> 'tanvas_home_doorway1',
	// 	'before_widget'	=> '<div class="widget doorway-column flex-item">',
	// 	'after_widget'	=> '</div>',
	// 	'before_title'	=> '<h2>',
	// 	'after_title'	=> '</h2>'
	// ));
	// register_sidebar( array(
	// 	'name' 			=> 'Home Doorway Column 2',
	// 	'id' 			=> 'tanvas_home_doorway2',
	// 	'before_widget'	=> '<div class="widget doorway-column flex-item">',
	// 	'after_widget'	=> '</div>',
	// 	'before_title'	=> '<h2>',
	// 	'after_title'	=> '</h2>'
	// ));
	// register_sidebar( array(
	// 	'name' 			=> 'Home Doorway Column 3',
	// 	'id' 			=> 'tanvas_home_doorway3',
	// 	'before_widget'	=> '<div class="widget doorway-column flex-item">',
	// 	'after_widget'	=> '</div>',
	// 	'before_title'	=> '<h2>',
	// 	'after_title'	=> '</h2>'
	// ));
	// register_sidebar( array(
	// 	'name' 			=> 'Home Doorway Column 4',
	// 	'id' 			=> 'tanvas_home_doorway4',
	// 	'before_widget'	=> '<div class="widget doorway-column flex-item">',
	// 	'after_widget'	=> '</div>',
	// 	'before_title'	=> '<h2>',
	// 	'after_title'	=> '</h2>'
	// ));
	register_sidebar( array (
		'name' 			=> 'Home Doorway Sidebar',
		'id'			=> 'tanvas_home_doorway_sidebar',
		'before_widget' => '<div class="widget">',
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

	register_widget( 'lc_doorway_button');
}
add_action('widgets_init', 'tanvas_widgets_init');



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

/**
 * Product Category / Taxonomy Display Mods
 */

/** Add category image to category archive page */

add_action( 'woocommerce_archive_description', 'woocommerce_category_image', 2 );
function woocommerce_category_image() {
    if ( is_product_category() ){
	    global $wp_query;
	    $cat = $wp_query->get_queried_object();
	    $thumbnail_id = get_woocommerce_term_meta( $cat->term_id, 'thumbnail_id', true );
	    
	    if( $thumbnail_id ){
	    	echo wp_get_attachment_image( $thumbnail_id, 'full' );
	    }

	    // echo "<h2>" . __("subcategories") . "</h2>";

	    /*$image = wp_get_attachment_url( $thumbnail_id );
	    if ( $image ) {
		    echo '<img src="' . $image . '" alt="" />';
		}*/
	}
}

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

/*
 * Hooking in to metadata processor to add png support
 */

class PNG_Reader
{
    private $_chunks;
    private $_fp;

    function __construct($file) {
        if (!file_exists($file)) {
            throw new Exception('File does not exist');
        }

        $this->_chunks = array ();

        // Open the file
        $this->_fp = fopen($file, 'r');

        if (!$this->_fp)
            throw new Exception('Unable to open file');

        // Read the magic bytes and verify
        $header = fread($this->_fp, 8);

        if ($header != "\x89PNG\x0d\x0a\x1a\x0a")
            throw new Exception('Is not a valid PNG image');

        // Loop through the chunks. Byte 0-3 is length, Byte 4-7 is type
        $chunkHeader = fread($this->_fp, 8);

        while ($chunkHeader) {
            // Extract length and type from binary data
            $chunk = @unpack('Nsize/a4type', $chunkHeader);

            // Store position into internal array
            if (isset($this->_chunks[$chunk['type']]) and $this->_chunks[$chunk['type']] === null)
                $this->_chunks[$chunk['type']] = array ();
            $this->_chunks[$chunk['type']][] = array (
                'offset' => ftell($this->_fp),
                'size' => $chunk['size']
            );

            // Skip to next chunk (over body and CRC)
            fseek($this->_fp, $chunk['size'] + 4, SEEK_CUR);

            // Read next chunk header
            $chunkHeader = fread($this->_fp, 8);
        }
    }

    function __destruct() { fclose($this->_fp); }

    // Returns all chunks of said type
    public function get_chunks($type) {
        if (isset($this->_chunks[$type]) and $this->_chunks[$type] === null)
            return null;

        $chunks = array ();

        if(isset($this->_chunks[$type])){
            foreach ($this->_chunks[$type] as $chunk) {
                if ($chunk['size'] > 0) {
                    fseek($this->_fp, $chunk['offset'], SEEK_SET);
                    $chunks[] = fread($this->_fp, $chunk['size']);
                } else {
                    $chunks[] = '';
                }
            }
        }

        return $chunks;
    }
}

// add_filter(
// 	'wp_read_image_metadata_types',
// 	function( $array ){
// 		If(WP_DEBUG) error_log("debug_image_metadata callback | array: ".serialize($array) );
// 		if(defined(IMAGETYPE_PNG)) define(IMAGETYPE_PNG, 3);
// 		array_push($array, IMAGETYPE_PNG);
// 		if(defined(IMAGETYPE_JP2)) define(IMAGETYPE_JP2, 10);
// 		array_push($array, IMAGETYPE_JP2);
// 		return $array;
// 	}
// );

	// return apply_filters( 'wp_read_image_metadata', $meta, $file, $sourceImageType );
add_filter( 
	'wp_read_image_metadata', 
	function ($meta, $file='', $sourceImageType=''){
		If(WP_DEBUG) error_log("debug_image_metadata callback | meta: ".serialize($meta)." file: ".serialize($file)." imgtype: ".serialize($sourceImageType));
		
		if(!preg_match('/\.png/', strtolower($file))){
			return $meta;
		}

		$png = new PNG_Reader($file);
		$rawTextData = $png->get_chunks('tEXt');
		$metadata = array();

		foreach($rawTextData as $data) {
		   $sections = explode("\0", $data);

		   if($sections > 1) {
		       $key = array_shift($sections);
		       $metadata[$key] = implode("\0", $sections);
		   } else {
		       $metadata[] = $data;
		   }
		}

		if(WP_DEBUG) error_log("\nMETADATA: ".serialize($metadata));

		if(isset($metadata['title'])){
			$meta['title'] = $metadata['title'];
		}

		if(isset($metadata['description'])){
			$meta['caption'] = $metadata['description'];
		}

		return $meta;

	}, 
	0, 
	3
);

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
    wp_enqueue_style( 'custom-login', get_stylesheet_directory_uri() . '/style-login.css' );
}
add_action( 'login_enqueue_scripts', 'my_login_stylesheet' );

?>
