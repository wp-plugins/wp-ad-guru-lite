<?php
add_action( 'widgets_init', 'adguru_widget_init' );


function adguru_widget_init() {
	register_widget( 'ADGURU_Widget' );
}

class ADGURU_Widget extends WP_Widget {

	function ADGURU_Widget() {
		$widget_ops = array( 'classname' => 'adguru', 'description' => __('A widget that displays adguru ad zones ', 'ADGURU') );
		
		$control_ops = array( 'width' => 300, 'height' => 350, 'id_base' => 'adguru-zone-widget' );
		
		$this->WP_Widget( 'adguru-zone-widget', __('adGuru Zone', 'ADGURU'), $widget_ops, $control_ops );
	}
	
	function widget( $args, $instance ) {
		extract( $args );

		//Our variables from the widget settings.
		$title = apply_filters('widget_title', $instance['title'] );
		$zone_id = $instance['zone_id'];

		echo $before_widget;

		// Display the widget title 
		if ( $title )
			echo $before_title . $title . $after_title;

		//Display the name 
		if ( $zone_id )
			{		
			adguru_show_zone($zone_id);
			}
			else
			{
			echo "No zone is selected for this widget. Go to your dashboard widgets page and select a zone for this adGuru widget";
			}
			echo $after_widget;
			
	}

	//Update the widget 
	 
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;

		//Strip tags from title and name to remove HTML 
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['zone_id'] = intval( $new_instance['zone_id'] );
		
		return $instance;
	}

	
	function form( $instance ) {
		global $wpdb;
		//Set up some default widget settings.
		$defaults = array( 'title' => __('Ads', 'ADGURU'), 'zone_id' =>0);
		$instance = wp_parse_args( (array) $instance, $defaults ); ?>
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Title:', 'ADGURU'); ?></label>
			<input id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" style="width:100%;" />
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'zone_id' ); ?>"><?php _e('Select a zone:', 'ADGURU'); ?></label>
			<select name="<?php echo $this->get_field_name( 'zone_id' ); ?>" id="<?php echo $this->get_field_id( 'zone_id' ); ?>" style="width:100%;" >
				<option value="0" selected="selected">Select a zone</option>
				<?php
				$sql="SELECT * FROM ".ADGURU_ZONES_TABLE." WHERE active=1";
				$zones=$wpdb->get_results($sql);
				foreach($zones as $zone)
				{
				echo '<option value="'.$zone->id.'"'; if($zone->id==$instance['zone_id'])echo ' selected="selected"'; echo '>'.$zone->name.'-'.$zone->width.'x'.$zone->height.'</option>';	
				}
				?>
			</select>
			
		</p>

	<?php
	}
}

?>