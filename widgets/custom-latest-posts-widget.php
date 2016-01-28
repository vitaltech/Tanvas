<?php 

class CUSTOM_LATEST_POSTS_WIDGETS extends WP_Widget {

    function __construct() {
        $widget_ops = array( 'classname' => 'custom_latest_posts_widget', 'description' => __('A widget that displays recent posts', 'custom_latest_posts_widget') );
        
        $control_ops = array( 'width' => 300, 'height' => 350, 'id_base' => 'custom_latest_posts_widget' );
        
        // $this->WP_Widget( 'custom_latest_posts_widget', __('Technotan: Latest Posts', 'custom_latest_posts_widget'), $widget_ops, $control_ops );
        parent::__construct( 'custom_latest_posts_widget', __('Technotan: Latest Posts', 'custom_latest_posts_widget'), $widget_ops, $control_ops );
    }
    
    function widget( $args, $instance ) {
        extract( $args );

        //Our variables from the widget settings.
        $title = apply_filters('widget_title', $instance['title'] );
        $number_of_posts = $instance['number_of_posts'];
        $display_thumbnails = isset( $instance['display_thumbnails'] ) ? $instance['display_thumbnails'] : false;
        
        echo $before_widget;


        $number_of_posts = ( ! empty( $instance['number_of_posts'] ) ) ? absint( $instance['number_of_posts'] ) : 10;
        if ( ! $number_of_posts )
            $number_of_posts = 10;
        
        $viewall = $instance['viewall'];
?>      
        <?php 
        
        query_posts(array('posts_per_page' => $number_of_posts)); ?>
        <div id="recent-posts-section" class="f-row">
            <div id="recent-posts" class="large-10 columns">
                
                <?php 
                    // Display the widget title 
                    if ( $title )
                    echo '<center class="title">'.$before_title . $title . $after_title.'</center>';
                ?>
            
                <ul id="recent-posts-items" class="small-block-grid-1 medium-block-grid-3 large-block-grid-3">
                <?php                       
                    while ( have_posts() ) : the_post();    
                    global $post;       
                    $featured_image = wp_get_attachment_url( get_post_thumbnail_id($post->ID) );                    
                ?>
                    <li class="list">                                                           
                        <?php if ( $display_thumbnails ) : ?>
                            <?php if ( has_post_thumbnail() ) { ?>
                                <center><img class="recent-posts-img" src="<?php echo esc_url( get_stylesheet_directory_uri() ) ;?>/timthumb.php?src=<?= $featured_image; ?>&amp;w=91&amp;h=91" /></center>
                            <?php } ?>
                        <?php endif;?>
                                        
                        <center><h5 class="title"><?php the_title(); ?></h5></center>
                                                
                        <p><?php echo excerpt(10); ?></p>
                        <a class="read-more" href="<?php the_permalink(); ?>">Read more</a>
                                                
                    </li>
                <?php endwhile; wp_reset_query();?>
                </ul>
                
                <?php if($viewall) : ?>
                    <center>
                        <a class="view-all" href="<?php echo $viewall; ?>">View All</a>
                    </center>
                <?php endif;?>
                
            </div>
        </div>
<?php
        echo $after_widget;
    }

    //Update the widget 
    function update( $new_instance, $old_instance ) {
        $instance = $old_instance;

        //Strip tags from title and name to remove HTML 
        $instance['title'] = strip_tags( $new_instance['title'] );
        $instance['number_of_posts'] = strip_tags( $new_instance['number_of_posts'] );
        $instance['viewall'] = strip_tags( $new_instance['viewall'] );
        $instance['display_thumbnails'] = isset( $new_instance['display_thumbnails'] ) ? (bool) $new_instance['display_thumbnails'] : false;

        return $instance;
    }

    function form( $instance ) {

        //Set up some default widget settings.
        $display_thumbnails = isset( $instance['display_thumbnails'] ) ? (bool) $instance['display_thumbnails'] : false;
        $defaults = array();
        $instance = wp_parse_args( (array) $instance, $defaults ); 
        ?>
        
        <p>
            <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Title:', 'custom_latest_posts_widget'); ?></label>
            <input id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" style="width:100%;" />
        </p>
        
        <p>
            <label for="<?php echo $this->get_field_id( 'number_of_posts' ); ?>"><?php _e('Number of posts to show:', 'custom_latest_posts_widget'); ?></label>
            <input id="<?php echo $this->get_field_id( 'number_of_posts' ); ?>" name="<?php echo $this->get_field_name( 'number_of_posts' ); ?>" value="<?php echo $instance['number_of_posts']; ?>" style="width:100%;" />
        </p>
        
        <p>
            <label for="<?php echo $this->get_field_id( 'display_thumbnails' ); ?>"><?php _e('Display post thumbnail?', 'custom_latest_posts_widget'); ?></label>
            <input class="checkbox" type="checkbox" <?php checked($display_thumbnails); ?> id="<?php echo $this->get_field_id( 'display_thumbnails' ); ?>" name="<?php echo $this->get_field_name( 'display_thumbnails' ); ?>" /> 
            
        </p>
        
        <p>
            <label for="<?php echo $this->get_field_id( 'viewall' ); ?>"><?php _e('View All Link:', 'custom_latest_posts_widget'); ?></label>
            <input id="<?php echo $this->get_field_id( 'viewall' ); ?>" name="<?php echo $this->get_field_name( 'viewall' ); ?>" value="<?php echo $instance['viewall']; ?>" style="width:100%;" />
        </p>
    <?php
    }
}

?>