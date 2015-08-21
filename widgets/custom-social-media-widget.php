<?php
class CUSTOM_SOCIAL_MEDIA_WIDGETS extends WP_Widget {
    function CUSTOM_SOCIAL_MEDIA_WIDGETS() {
        $widget_ops = array( 'classname' => 'custom_social_media_widget', 'description' => __('A widget that displays social media ', 'custom_social_media_widget') );
        
        $control_ops = array( 'width' => 300, 'height' => 350, 'id_base' => 'custom_social_media_widget' );
        
        $this->WP_Widget( 'custom_social_media_widget', __('Technotan: Social Media', 'custom_social_media_widget'), $widget_ops, $control_ops );
    }
    
    function widget( $args, $instance ) {
        extract( $args );

        //Our variables from the widget settings.
        $title = apply_filters('widget_title', $instance['title'] );
        $facebook = $instance['facebook'];
        $youtube = $instance['youtube'];
        $ig = $instance['ig'];
        $googleplus = $instance['googleplus'];
        
        
        echo $before_widget;

        // Display the widget title 
        //if ( $title )
            //echo $before_title . $title . $after_title;
        ?>
        
        <ul id="<?php if($title) : ?>show-title<?php endif;?>" class="social-icons clearfix">
            <?php if($title) : ?>
                <li class="title"><?php echo $before_title . $title . $after_title; ?></li>
            <?php endif;?>
            <?php if($facebook) : ?>
                <li class="icon"><a class="facebook" title="Facebook" href="<?php echo $facebook; ?>" target="_blank"></a></li>
            <?php endif;?>
            
            <?php if($youtube) : ?>
                <li class="icon"><a class="youtube" title="Youtube" href="<?php echo $youtube; ?>" target="_blank"></a></li>
            <?php endif;?>
            
            <?php if($ig) : ?>
                <li class="icon"><a class="ig" title="Instagram" href="<?php echo $ig; ?>" target="_blank"></a></li>
            <?php endif;?>

            <?php if($googleplus) : ?>
                <li class="icon"><a class="googleplus" title="Googleplus" href="<?php echo $googleplus; ?>" target="_blank"></a></li>
            <?php endif;?>

        </ul>
    
    <?php   
        echo $after_widget;
    }
    //Update the widget 
    function update( $new_instance, $old_instance ) {
        $instance = $old_instance;

        //Strip tags from title and name to remove HTML 
        $instance['title'] = strip_tags( $new_instance['title'] );
        $instance['facebook'] = strip_tags( $new_instance['facebook'] );
        $instance['youtube'] = strip_tags( $new_instance['youtube'] );
        $instance['ig'] = strip_tags( $new_instance['ig'] );
        $instance['googleplus'] = strip_tags( $new_instance['googleplus'] );
        
        return $instance;
    }
    function form( $instance ) {
        ?>
        <p>
            <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Title:', 'custom_social_media_widget'); ?></label>
            <input id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" style="width:100%;" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'facebook' ); ?>"><?php _e('Facebook:', 'custom_social_media_widget'); ?></label>
            <input id="<?php echo $this->get_field_id( 'facebook' ); ?>" name="<?php echo $this->get_field_name( 'facebook' ); ?>" value="<?php echo $instance['facebook']; ?>" style="width:90%;" />
        </p>
        
        <p>
            <label for="<?php echo $this->get_field_id( 'youtube' ); ?>"><?php _e('Youtube:', 'custom_social_media_widget'); ?></label>
            <input id="<?php echo $this->get_field_id( 'youtube' ); ?>" name="<?php echo $this->get_field_name( 'youtube' ); ?>" value="<?php echo $instance['youtube']; ?>" style="width:90%;" />
        </p>
        
        <p>
            <label for="<?php echo $this->get_field_id( 'ig' ); ?>"><?php _e('Instagram:', 'custom_social_media_widget'); ?></label>
            <input id="<?php echo $this->get_field_id( 'ig' ); ?>" name="<?php echo $this->get_field_name( 'ig' ); ?>" value="<?php echo $instance['ig']; ?>" style="width:90%;" />
        </p>
        
        <p>
            <label for="<?php echo $this->get_field_id( 'googleplus' ); ?>"><?php _e('Googleplus:', 'custom_social_media_widget'); ?></label>
            <input id="<?php echo $this->get_field_id( 'googleplus' ); ?>" name="<?php echo $this->get_field_name( 'googleplus' ); ?>" value="<?php echo $instance['googleplus']; ?>" style="width:90%;" />
        </p>
    <?php
    }
}
?>