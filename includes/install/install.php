<?php

function adGuru_db_tables_create()
{

require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
	global $wpdb;
	$adguru_zones_table=$wpdb->prefix.'adguru_zones';
	$adguru_ads_table=$wpdb->prefix.'adguru_ads';
	$adguru_links_table=$wpdb->prefix.'adguru_links';
		  
   $adguru_zones_sql = "CREATE TABLE $adguru_zones_table (
	  `id` bigint(20) NOT NULL AUTO_INCREMENT,
	  `name` varchar(50) NOT NULL,
	  `description` varchar(512) DEFAULT NULL,
	  `width` int(11) DEFAULT '0',
	  `height` int(11) DEFAULT '0',
	  `ad_campaign_ids` varchar(512) DEFAULT NULL,
	  `active` int(1) DEFAULT '1', 
	  UNIQUE KEY id (id)
	) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
	";
   dbDelta($adguru_zones_sql);
   
   $adguru_ads_sql = "CREATE TABLE $adguru_ads_table (
	  `id` bigint(20) NOT NULL AUTO_INCREMENT,
	  `ad_type` varchar(20) NOT NULL DEFAULT 'banner',
	  `name` varchar(100) NOT NULL,
	  `description` varchar(300) DEFAULT NULL,
	  `width` int(11) DEFAULT '300',
	  `height` int(11) DEFAULT '250',
	  `active` int(11) NOT NULL DEFAULT '1',
	  `code_type` varchar(20) DEFAULT 'html',
	  `html_code` text,
	  `image_source` varchar(512) DEFAULT NULL,
	  `image_link` varchar(512) DEFAULT NULL,
	  `link_target` varchar(10) DEFAULT '_blank',
	  `iframe_source` varchar(512) DEFAULT NULL,
	  `own_html` text,
	  `popup_timing` int(11) DEFAULT NULL,
	  `enable_stealth_mode` int(1) DEFAULT '0',
	  `enable_exit_popup` int(1) DEFAULT '0',
	  `popup_options` varchar(512) DEFAULT NULL COMMENT 'array', 
	  UNIQUE KEY id (id)
	) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
	";
   dbDelta($adguru_ads_sql);
	
   $adguru_links_sql = "CREATE TABLE $adguru_links_table (
	  `id` bigint(20) NOT NULL AUTO_INCREMENT,
	  `ad_type` varchar(20) NOT NULL DEFAULT 'banner',
	  `zone_id` bigint(20) NOT NULL DEFAULT '0',
	  `page_type` varchar(20) NOT NULL DEFAULT '--',
	  `taxonomy` varchar(50) NOT NULL DEFAULT '--',
	  `term` varchar(50) NOT NULL DEFAULT '--',
	  `object_id` bigint(20) NOT NULL DEFAULT '0',
	  `country_code` varchar(3) NOT NULL DEFAULT '--',
	  `slide` tinyint(4) DEFAULT '1',
	  `ad_id` bigint(20) NOT NULL,
	  `percentage` tinyint(4) DEFAULT '100',
	  UNIQUE KEY id (id)
	) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
	";
   dbDelta($adguru_links_sql);
		

}#end function adGuru_db_tables_create

function adGuru_install() {adGuru_db_tables_create();}
register_activation_hook(ADGURU_PLUGIN_FILE,'adGuru_install'); 
?>