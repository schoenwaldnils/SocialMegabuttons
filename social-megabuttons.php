<?php 
    /*
    Plugin Name: Social Mega Buttons
    Description: Plugin for displaying Mega Social Buttons in the widget-area.
    Author: Nils Sch&ouml;nwald
    Version: 1.0
    Author URI: http://www.schoenwald-media.de
    */

global $socialnetworks;
$socialnetworks = array("twitter", "facebook", "googleplus");

class megabutton_plugin extends WP_Widget {


	// constructor
	function megabutton_plugin() {
		parent::WP_Widget(false, $name = __('Social Megabutton', 'megabutton_plugin') );
	}

	// widget form creation
	function form($instance) {	
    
        global $socialnetworks;
        
        // Check values
        if( $instance) {
             $title = esc_attr($instance['title']);
             foreach ($socialnetworks as &$value) {
                $$value = esc_attr($instance[$value]);
             }
        } else {
            $title = '';
            foreach ($socialnetworks as &$value) {
                $$value = '';
            }
        } ?>
        
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', 'wp_widget_plugin'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
        </p>
        <br />
        <h4>Insert profilname, like 'JohnDoe'</h4>
        <?php
        foreach ($socialnetworks as &$value) { ?>
            <p>
                <label for="<?php echo $this->get_field_id($value); ?>"><?php echo ucfirst(strtolower($value)); ?>:</label>
                <input class="widefat" id="<?php echo $this->get_field_id($value); ?>" name="<?php echo $this->get_field_name($value); ?>" type="text" value="<?php echo ${$value}; ?>" />
            </p>
        <?php }
    }

	// widget update
	function update($new_instance, $old_instance) {
	    
	    global $socialnetworks;
	    
	    $instance = $old_instance;
        // Fields
        $instance['title'] = strip_tags($new_instance['title']);
        foreach ($socialnetworks as &$value) {
            $instance[$value] = strip_tags($new_instance[$value]);
        }
        return $instance;
	}

	// widget display
	function widget($args, $instance) {

	    global $socialnetworks;
		
        extract( $args );
        // these are the widget options
        $title = apply_filters('widget_title', $instance['title']);
        foreach ($socialnetworks as &$value) {
            if ($value == 'twitter') {
                $$value = array('user' => $instance[$value], 'url' => 'https://twitter.com/', 'button' => '<a href="https://twitter.com/'.$instance[$value].'" class="twitter-follow-button" data-show-count="false" data-show-screen-name="false">Follow Me</a>');
            } elseif ($value == 'facebook') {
                $$value = array('user' => $instance[$value], 'url' => 'http://www.facebook.com/', 'button' => '<div class="fb-like" data-href="http://www.facebook.com/'.$instance[$value].'" data-layout="button" data-action="like" data-show-faces="false" data-share="false"></div><div id="fb-root"></div>');
            } elseif ($value == 'googleplus') {
                $$value = array('user' => $instance[$value], 'url' => 'https://plus.google.com/u/0/', 'button' => '<div class="g-plusone" data-href="https://plus.google.com/u/0/'.$instance[$value].'" data-size="standard" data-annotation="none" data-recommendations="false" data-align="left"></div>');
            }
        }
        echo $before_widget;
        
        // Check if title is set
        if ( $title ) {
            echo $before_title . $title . $after_title;
        }

        foreach ($socialnetworks as &$value) {
            if( ${$value} ) {
                echo '<div class="megabutton megabutton-'.$value.' fa-'.$value.'"><div class="megabutton-button">'.${$value}['button'].'</div><a class="megabutton-profil fa-link" href="'.${$value}['url'].${$value}['user'].'" target="_blank"></a></div>'; 
            }
        }
        echo $after_widget;
	}
}

// register widget
add_action('widgets_init', create_function('', 'return register_widget("megabutton_plugin");'));
add_action('wp_enqueue_scripts', 'megabutton_scripts');

function megabutton_scripts() {
    $dir_url = plugin_dir_url( __FILE__ );
	$suffix  = ( ! defined( 'SCRIPT_DEBUG' ) || ! SCRIPT_DEBUG ) ? '.min' : '';

	wp_enqueue_style( 'social-megabuttons', $dir_url . 'css/social-megabuttons' . $suffix . '.css', false );
	wp_enqueue_style( 'font-awesome', '//netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css' );
	wp_enqueue_script( 'social-megabuttons', $dir_url . 'js/social-megabuttons' . $suffix . '.js', '', $ver, true );
}

?>