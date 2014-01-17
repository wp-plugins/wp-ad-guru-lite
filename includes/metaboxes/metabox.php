<?php
function adguru_add_metabox() {

		global $adGuru;
		if(!$adGuru->is_permitted_user()){return;}
		$post_types=get_post_types('','names'); 
		$rempost = array('attachment','revision','nav_menu_item');
		$post_types = array_diff($post_types,$rempost);	
			
	$screens = $post_types;
    foreach ( $screens as $screen ) {

        add_meta_box(
            'adguru_setup',
            __( 'AdGuru Setup', 'ADGURU' ),
            'adguru_metabox',
            $screen
        );
    }
}
add_action( 'add_meta_boxes', 'adguru_add_metabox' );

function adguru_metabox( $post ) 
{
?>
	<a href="javascript:void(0)" onclick="alert('Premium Feature')" class="button-primary">Banner Ads</a>
	<a href="javascript:void(0)" onclick="alert('Premium Feature')" class="button-primary">Modal Popups</a>
	<a href="javascript:void(0)" onclick="alert('Premium Feature')" class="button-primary">Window Popups</a>

<?php   
}
?>