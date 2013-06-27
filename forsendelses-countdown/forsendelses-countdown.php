<?php
/**
 * Plugin Name: Forsendelses Widget
 * Description: Et widget der viser tid til næste forsendelse
 * Version: 1.0
 * Author: Martin Kollerup
 * Author URI: https://github.com/martinkolle/wp-forsendelses-widget/
 */


add_action( 'widgets_init', 'forsendelses_countdown' );


function forsendelses_countdown() {
	register_widget( 'forsendelses_countdown' );
}

class forsendelses_countdown extends WP_Widget {

	static $hours;
	static $minutes;
	static $append = false;

	function forsendelses_countdown() {
		$widget_ops = array( 'classname' => 'forsendelses_countdown', 'description' => __('Nedtælling til næste forsendelse', 'forsendelses_countdown') );
		
		$control_ops = array( 'width' => 300, 'height' => 350, 'id_base' => 'forsendelses_countdown-widget' );
		
		$this->WP_Widget( 'forsendelses_countdown-widget', __('Forsendelses nedtælling', 'forsendelses_countdown'), $widget_ops, $control_ops );
	}
	
	function widget( $args, $instance ) {
		extract( $args );

		//Our variables from the widget settings.
		$title = apply_filters('widget_title', $instance['title'] );
		$hours = isset( $instance['hours'] ) ? $instance['hours'] : 16;
		$minutes = isset( $instance['minutes'] ) ? $instance['minutes'] : 00;
		$append = isset($instance['append']) ? $instance['append'] : false;


		//used for the init
		self::$hours = $hours;
		self::$minutes = $minutes;
		self::$append = $append;


		echo $before_widget;

		// Display the widget title 
		if ($title)
			echo $before_title . $title . $after_title;

		add_action('wp_footer',array($this,'countdown_init'));
		wp_enqueue_script("forsendelses-countdown", plugins_url('js/countdown.js',__FILE__),array("jquery"));
		wp_enqueue_style("forsendelses-countdown", plugins_url('css/countdown.css',__FILE__));

		if(!$append)
			echo '<div id="countdown"></div><p id="note"></p>';

		echo $after_widget;
	}

	//Update the widget 
	 
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;

		//Strip tags from title and name to remove HTML 
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['hours'] = strip_tags( $new_instance['hours'] );
		$instance['minutes'] = strip_tags( $new_instance['minutes'] );
		$instance['append'] = strip_tags( $new_instance['append'] );



		return $instance;
	}

	/**
	* Create the edit form for the widget
	* @author Martin Kollerup
	*/
	
	function form( $instance ) {

		//Set up some default widget settings.
		$defaults = array( 'title' => __('Nedtælling', 'forsendelses_countdown'), 'hours' => 16, 'minutes' => 00, 'append' =>"");
		$instance = wp_parse_args( (array) $instance, $defaults ); ?>

		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Title:', 'forsendelses_countdown'); ?></label>
			<input id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" style="width:100%;" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'hours' ); ?>"><?php _e('Hours:', 'forsendelses_countdown'); ?></label>
			<input id="<?php echo $this->get_field_id( 'hours' ); ?>" placeholder="Hours" name="<?php echo $this->get_field_name( 'hours' ); ?>" value="<?php echo $instance['hours']; ?>" style="width:50%;" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'minutes' ); ?>"><?php _e('Minutes:', 'forsendelses_countdown'); ?></label>
			<input id="<?php echo $this->get_field_id( 'minutes' ); ?>" placeholder="Minutes" name="<?php echo $this->get_field_name( 'minutes' ); ?>" value="<?php echo $instance['minutes']; ?>" style="width:50%;" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'append' ); ?>"><?php _e('Append to:', 'forsendelses_countdown'); ?></label>
			<input id="<?php echo $this->get_field_id( 'append' ); ?>" placeholder="#main-nav #top" name="<?php echo $this->get_field_name( 'append' ); ?>" value="<?php echo $instance['append']; ?>" style="width:100%;" />
		</p>

	<?php
	}

	/**
	* Add the javascript to the header
	* @author Martin Kollerup
	*/

	function countdown_init(){

		$append = (self::$append) ? 'jQuery("'.self::$append.'").append(\'<div id="countdown"><span id="forsend">Næste forsendelse</span> </div>\');' : "";

		echo '<script type="text/javascript">jQuery(function(){
			'.$append.'
			var curTime = new Date();
			//date string for today
			ts = new Date(curTime.getFullYear(), curTime.getMonth(), curTime.getDate(), '.self::$hours.', '.self::$minutes.')

			//is it past 16:00 we are counting for tommorrow. 
			if((new Date()) > ts){
				var curTime = new Date();
				ts = new Date(curTime.getFullYear(), curTime.getMonth(), curTime.getDate() + 1, '.self::$hours.', '.self::$minutes.')
			}
			
			jQuery("#countdown").countdown({
				timestamp	: ts,
				callback	: function(days, hours, minutes, seconds){
					//note.html(message);
				}
			});
		});</script>';

	}
}

?>