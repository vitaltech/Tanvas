<?php
/**
 *  Home Page and Doorway Button Widget / Widget Areas
 */

class lc_doorway_button extends WP_Widget
{
    
    function __construct()
    {
        WP_Widget::__construct(
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
            $img = 'https://staging.technotan.com.au/wp-content/themes/tanvas/img/logo-transparent-zoomed-out.png';
        }
        if(isset($instance['alt'])){
            $alt = esc_attr($instance['alt']);
        } else {
            $alt = '';
        }       

        if( !$alt ){
            $alt=$title;
        } 
        
        if(isset($instance['view'])){
            $view = esc_attr($instance['view']);
        } else {
            $view = 'View All';
        }

        $before_widget  = isset($args['before_widget']) ? $args['before_widget']    : '<li><div class="doorway-container">' ;
        $after_widget   = isset($args['after_widget'])  ? $args['after_widget']     : '</div></li>' ;
        $before_title   = isset($args['before_title'])  ? $args['before_title']     : '<h5 class="doorway-title">' ;
        $after_title    = isset($args['after_title'])   ? $args['after_title']      : '</h5>';
        
        echo $before_widget;
            echo "<img id='doorway-img' src='$img' alt='$alt' />";
            echo "<div class='doorway-title-section'>";
                if( !empty($title) ){
                    echo $before_title . $title . $after_title;
                }
                echo "<a id='doorway-link' href='$url' > $view ";
                echo "</a>";
            echo "</div>";      
        echo $after_widget;
        
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
        
        if(isset($instance['view'])){
            $view = $instance['view'];
        } else {
            $view = 'View All';
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
                value="<?php echo esc_attr($url); ?>"
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
        <p>
            <label for="<?php echo $this->get_field_id('view'); ?>"><?php _e('View Text:'); ?></label>
            <input
                class="widefat"
                id="<?php echo $this->get_field_id('view');?>"
                name="<?php echo $this->get_field_name('view') ; ?>"
                type="text"
                value="<?php echo esc_attr($view); ?>"
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
        $instance['view'] = ( ! empty($new_instance['view'])) ? strip_tags( $new_instance['view']) : '' ;
        return $instance;
    }
}
?>