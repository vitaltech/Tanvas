<?php
/**
 * Template Name: Home
 *
 * The home page template displays your posts with a "business"-style
 * content slider at the top and has space for custom doorway buttons
 *
 * @package WooFramework
 * @subpackage Template
 */

global $woo_options, $wp_query;
get_header();

$page_template = woo_get_page_template();
?>
    <!-- #content Starts -->
	<?php woo_content_before(); ?>
	<?php if ( ( isset( $woo_options['woo_slider_biz'] ) && 'true' == $woo_options['woo_slider_biz'] ) && ( isset( $woo_options['woo_slider_biz_full'] ) && 'true' == $woo_options['woo_slider_biz_full'] ) ) { $saved = $wp_query; woo_slider_biz(); $wp_query = $saved; } ?>
    <div id="content" class="col-full home">

    	<div id="main-sidebar-container">

            <!-- #main Starts -->
            <?php woo_main_before(); ?>

			<?php if ( ( isset( $woo_options['woo_slider_biz'] ) && 'true' == $woo_options['woo_slider_biz'] ) && ( isset( $woo_options['woo_slider_biz_full'] ) && 'false' == $woo_options['woo_slider_biz_full'] ) ) { $saved = $wp_query; woo_slider_biz(); $wp_query = $saved; } ?>

			<!-- doorway button & sidebar widgets -->
			<?php 
				$left_active = is_active_sidebar('tanvas_home_doorway');
				$right_active = is_active_sidebar('tanvas_home_doorway_sidebar');
				if( $left_active|| $right_active){
					echo '<div class="widget-area-container home col-full" id="doorway-button-sidebar-container">';
					if($left_active) {
						$class = 'widget-area flex-container doorway-buttons';
						$class .= $right_active ? ' left col-3' : ' col-4';
						echo "<div class='$class' id='tanvas-home-doorway-buttons'  role='complementary'>";
						dynamic_sidebar('tanvas_home_doorway');
						echo '</div>';
					}
					if($right_active){
						$class = 'widget-area sidebar';
						$class .= $left_active ? ' right' : '';
						echo "<div class='$class' id='tanvas-home-doorway-sidebar'>";
						dynamic_sidebar('tanvas_home_doorway_sidebar');
						echo '</div>';
					}
					echo '</div>';
				} 
			?>

			<!-- middle widget areas -->
			<?php 
				$left_active = is_active_sidebar('tanvas_home_left');
				$right_active = is_active_sidebar('tanvas_home_right');
				if( $left_active|| $right_active){
					echo "<div class='widget-area-container home col-full'>";
					if($left_active){
						$class = 'widget-area';
						$class .= $right_active ? ' left' : ' col-full';
						echo "<div class='$class'  id='tanvas_home_left'>";
						dynamic_sidebar('tanvas_home_left');
						echo '</div>';
					}
					if($right_active){
						$class = 'widget-area sidebar';
						$class .= $left_active ? ' right' : ' col-full';
						echo "<div class='$class' id='tanvas_home_right'>";
						dynamic_sidebar('tanvas_home_right');
						echo '</div>';
					}
					echo "</div>";
				} 
			?>

            <section id="main">
<?php
	woo_loop_before();

	if ( have_posts() ) { $count = 0;
		while ( have_posts() ) { the_post(); $count++;
			//woo_get_template_part( 'content', 'page-template-business' ); // Get the page content template file, contextually.
		}
	}

	woo_loop_after();
?>
            </section><!-- /#main -->
            <?php //woo_main_after(); ?>

			<?php // get_sidebar(); ?>

		</div><!-- /#main-sidebar-container -->

		<?php get_sidebar( 'alt' ); ?>

    </div><!-- /#content -->
	<?php woo_content_after(); ?>

<?php get_footer(); ?>