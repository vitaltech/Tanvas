<?php
/**
 * Footer Template
 *
 * Here we setup all logic and XHTML that is required for the footer section of all screens.
 *
 * @package WooFramework
 * @subpackage Template
 */

 global $woo_options;

 //woo_footer_top();
 	woo_footer_before();
?>

	</div><!-- /#inner-wrapper -->

</div><!-- /#wrapper -->
</div><!-- /#wrapper-section -->

<div class="fix"></div><!--/.fix-->

	<footer id="footer-section" class="foundation">
	
		<div class="f-row">
			<div id="about-technotan" class="large-3 columns">
				<?php dynamic_sidebar('widget-one'); ?>
			</div>
			<div id="footer-link" class="large-9 columns">
				<ul class="small-block-grid-1 medium-block-grid-2 large-block-grid-4">
					<li class="address-technotan"><?php dynamic_sidebar('widget-two'); ?></li>
					<li class="info-technotan"><?php dynamic_sidebar('widget-three'); ?></li>
					<li class="contact-technotan"><?php dynamic_sidebar('widget-four'); ?></li>
					<li class="terms-technotan"><?php dynamic_sidebar('widget-five'); ?></li>
				</ul>
			</div>
			
		</div>
		
		<div id="footer-bottom" class="f-row">
			<div id="copyright" class="medium-6 columns">
				<?php woo_footer_left(); ?>
			</div>
			<div id="designby" class="medium-6 columns">
				<?php //woo_footer_right(); ?> &nbsp;
			</div>
		</div>
	</footer>
	
	
<!-- close the off-canvas menu -->
<a class="exit-off-canvas"></a>

</div>
</div>
	
	<?php woo_footer_after(); ?>

	<?php wp_footer(); ?>

	<?php woo_foot(); ?>
	
	<script type="text/javascript">
	jQuery(document).ready(function( $ ) {
		$(document).foundation();
		
		$( "body.page table, body.single table" ).addClass( "table" );
		$( "body.woocommerce-page table" ).removeClass( "table" );
		$( "#off-canvas-list1 .menu-item-has-children, #off-canvas-list1 .page_item_has_children" ).addClass( "has-submenu" );
		$( "#off-canvas-list1 ul.sub-menu" ).addClass( "left-submenu" );
		$("#off-canvas-list1 ul.sub-menu li:first-child").before(' <li class="back"><a href="#">Back</a></li>');

		$( "#off-canvas-list2 .menu-item-has-children, #off-canvas-list2 .page_item_has_children" ).addClass( "has-submenu" );
		$( "#off-canvas-list2 ul.sub-menu" ).addClass( "left-submenu" );
		$("#off-canvas-list2 ul.sub-menu li:first-child").before(' <li class="back"><a href="#">Back</a></li>');
		var n = $("#off-canvas-list2 li").length + 1 ;
		  $("#off-canvas-list2 li").each(function(n) {
			$(this).attr("id", "count-"+ n );
		});
		$("#off-canvas-list2 li#count-0").before(' <li><label>&nbsp;</label></li>');
		
		var n = $(".primary-navigation .nav-menu li:first-child").length + 1 ;
		  $(".primary-navigation .nav-menu li:first-child").each(function(n) {
			$(this).attr("id", "home-"+ n );
		});
		
		$('ul.slides li .content').wrapInner('<div class="f-row" />');
		
		$("#testimonial-slider").owlCarousel({
			autoPlay : 10000,
			stopOnHover : true,
			navigation:true,
			pagination:false,
			paginationSpeed : 1000,
			goToFirstSpeed : 2000,
			singleItem : true,
			autoHeight : true,
			transitionStyle:"fade"
		});
		
		$(".nav-entries span.nav-prev.fl").html("Next");
		$(".nav-entries span.nav-next.fr").html("Previous");
		
		$("form.woocommerce-shipping-calculator p a").after("<span class='office-hours'>pickup from jandakot<br/>office hours: 8:00AM - 5:00PM</span>");
	});
	</script>
	<div id="fb-root"></div>
	<script>(function(d, s, id) {
	  var js, fjs = d.getElementsByTagName(s)[0];
	  if (d.getElementById(id)) return;
	  js = d.createElement(s); js.id = id;
	  js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.5";
	  fjs.parentNode.insertBefore(js, fjs);
	}(document, 'script', 'facebook-jssdk'));</script>
</body>

</html>