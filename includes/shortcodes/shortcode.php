<?php
#http://codex.wordpress.org/Shortcode_API
function adguru_shortcode_handler($atts)
{
	global $adGuru;
	global $wpdb;
	extract( shortcode_atts( array(
			'adid' => '0',
			'zoneid' => '0',
		), $atts ) );
		$zoneid=intval($zoneid);
		$adid=intval($adid);
		if($zoneid){return adguru_show_zone($zoneid, false);}
		if($adid){return adguru_show_ad($adid, false);}
		
		return "";
}


add_shortcode( 'adguru', 'adguru_shortcode_handler' );
add_shortcode( 'ADGURU', 'adguru_shortcode_handler' );
?>