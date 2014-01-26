<?php
/*
	Plugin Name: WP Ad Guru Lite
	Description: Complete advertising management system for WordPress. Manage banner ads, modal popup and window popup. Ad zones, ad rotator, GeoLocation tracker, ads carousel-slider, different ads by multiple conditions of visited page
	Plugin URI: http://wpadguru.com
	Author: oneTarek
	Author URI: http://onetarek.com
	Version: 1.2.0
	
*/

define ( 'ADGURU_VERSION', '1.2.0');
define ( 'ADGURU_DOCUMENTAION_URL', 'http://wpadguru.com/documentation/');
define ( 'ADGURU_PLUGIN_FILE', __FILE__);
define ( 'ADGURU_PLUGIN_DIR', dirname(__FILE__)); // Plugin Directory
define ( 'ADGURU_PLUGIN_URL', plugin_dir_url(__FILE__)); // with forward slash (/). Plugin URL (for http requests).
define ( 'ADGURU_PLUGIN_SLUG', 'adguru');
define ( 'ADGURU_API_URL', ADGURU_PLUGIN_URL."api/api.php");
define ( 'ADGURU_ADMIN_API_URL', ADGURU_PLUGIN_URL."api/admin_api.php");+
define ( 'ADGURU_COOKIE_PREFIX', "adguru_");

global $wpdb;
#adguru tables
define( 'ADGURU_ZONES_TABLE',$wpdb->prefix.'adguru_zones');
define( 'ADGURU_ADS_TABLE',$wpdb->prefix.'adguru_ads');
define( 'ADGURU_LINKS_TABLE',$wpdb->prefix.'adguru_links');


require_once(ADGURU_PLUGIN_DIR."/includes/install/install.php");
require_once(ADGURU_PLUGIN_DIR."/includes/updater/updater.php");


require_once(ADGURU_PLUGIN_DIR."/includes/classes/main_class.php");
$adGuru= new WPAdGuru;

require_once(ADGURU_PLUGIN_DIR."/includes/widgets/widget.php");
require_once(ADGURU_PLUGIN_DIR."/includes/metaboxes/metabox.php");
require_once(ADGURU_PLUGIN_DIR."/includes/shortcodes/shortcode.php");

function adguru_show_zone($zone_id, $show=true)
{
	if(!intval($zone_id))return false;
	global $adGuru;
	$zones=$adGuru->get_zones();
	$output="";
	if(!isset($zones[$zone_id])){$output.="Zone not found"; if($show){ echo $output; return false;}else{return $output;}}	
	 $current_zone= $zones[$zone_id];
	$links=$adGuru->get_appropiate_ad_links("banner", $zone_id);
	if(is_array($links))
	{
		$tot_slide=count($links);
		if($tot_slide==0)
		{
			#nothing to do
			return false;
		}
		elseif($tot_slide==1)
		{
			#show single ad
			$ad_id=intval($adGuru->get_ad_by_percentage_probability($links[0]));
			$adGuru->instance_number++;
			$output.= adguru_show_ad($ad_id, false);
			
		
		}
		
	}#end if(is_array($links))
	if($show){ echo $output;}else{return $output;}
}#end function adguru_show_zone

function adguru_show_ad($ad_id , $show=true)
{
	if(!intval($ad_id))return false;
	global $adGuru;
	$ad=$adGuru->get_ad($ad_id);
	if(!$ad) return false;
	
	$output="";
	switch($ad->code_type)
	{
		case "html":
		{
			$output.= $ad->html_code;
		break;
		}				
	}#end switch
	
	if($show){ echo $output;}else{return $output;}
}



function adguru_print_modal_popup_script()
{
	global $adGuru;
	$links=$adGuru->get_appropiate_ad_links("modal_popup");
	if(is_array($links))
	{
		$tot_slide=count($links);
		if($tot_slide==0)
		{
			#nothing to do
			return false;
		}
		elseif($tot_slide==1)
		{
			#show modal popup
			$ad_id=intval($adGuru->get_ad_by_percentage_probability($links[0]));
			if(!intval($ad_id))return false;
			if(!isset($adGuru->ads[$ad_id])) return false;
			$ad=$adGuru->ads[$ad_id];
			if($ad->enable_stealth_mode && !(isset($_GET['kw'])|| isset($_GET['KW']) )){return false;}
			
			$popup_options=unserialize($ad->popup_options);
			if(!is_array($popup_options))
			{
				$popup_options=array(
								  'repeat_mode'=>'day', 
								  'cookie_duration'=>7,
								  'cookie_num_view'=>1								  
								  );
			}
			if(!isset($popup_options['repeat_mode'])){$popup_options['repeat_mode']="day"; $popup_options['cookie_duration']=7;$popup_options['cookie_num_view']=1;}
			$ad->popup_options=$popup_options;
			//echo "<pre>"; print_r($ad); echo "</pre>";			
			
			echo '<span id="adguru_modal_content">';
			switch($ad->code_type)
			{
				case "html":
				{
					echo $ad->html_code;
				break;
				}				
			}#end switch			
			echo '</span>';?>
			<!-- preload the images -->
			<div style='display:none'>
				<img src='<?php echo ADGURU_PLUGIN_URL ?>js/simplemodal-1.4.4/img/basic/x.png' alt='' />
			</div>
			<style type="text/css">#adguru_modal_content{ background:#ffffff; display:none; width:<?php echo $ad->width ?>px;  height:<?php echo $ad->height ?>px; overflow:hidden;}</style>
			<link type='text/css' href='<?php echo ADGURU_PLUGIN_URL ?>js/simplemodal-1.4.4/css/basic.css?var=<?php echo ADGURU_VERSION ?>' rel='stylesheet' media='screen' />
			<!-- IE6 "fix" for the close png image -->
			<!--[if lt IE 7]>
			<link type='text/css' href='<?php echo ADGURU_PLUGIN_URL ?>js/simplemodal-1.4.4/css/basic_ie.css' rel='stylesheet' media='screen' />
			<![endif]-->
			<script type='text/javascript' src='<?php echo ADGURU_PLUGIN_URL ?>js/simplemodal-1.4.4/js/jquery.simplemodal.js'></script>
			<script type="text/javascript" src="<?php echo ADGURU_PLUGIN_URL ?>js/bowser-master/bowser.min.js"></script>
			 <script type="text/javascript" src="<?php echo ADGURU_PLUGIN_URL ?>/js/modalengine.js?var=<?php echo ADGURU_VERSION ?>"></script>	
		
				<script type="text/javascript">				
				if(adguru_should_show(<?php echo $ad->id; ?>, "<?php echo $popup_options['repeat_mode']; ?>" , <?php echo $popup_options['cookie_duration']; ?>, <?php echo $popup_options['cookie_num_view']; ?>))
				{
					adguru_modal_engine.configure({
						width : "<?php echo $ad->width ?>",
						height : "<?php echo $ad->height ?>",
						delay : "<?php echo $ad->popup_timing?>",
						<?php if($ad->enable_exit_popup){?>enableExitAlert: "1", <?php }?>
						exitAlertText: "Wait! Before you go...<br><br>Please take a look at this special offer."
					
					})
				}
				</script>
					
		<?php 
		}#end if($tot_slide==0)
	}#end if(is_array($links))	

}#end function adguru_print_modal_script

function adguru_print_window_popup_script()
{
	global $adGuru;
	$links=$adGuru->get_appropiate_ad_links("window_popup");
	if(is_array($links))
	{
		$tot_slide=count($links);
		if($tot_slide==0)
		{
			#nothing to do
			return false;
		}
		elseif($tot_slide==1)
		{
			#show modal popup
			$ad_id=intval($adGuru->get_ad_by_percentage_probability($links[0]));
			if(!intval($ad_id))return false;
			if(!isset($adGuru->ads[$ad_id])) return false;
			$ad=$adGuru->ads[$ad_id];
			
			if($ad->enable_stealth_mode && !(isset($_GET['kw'])|| isset($_GET['KW']) )){return false;}
			
			$popup_options=unserialize($ad->popup_options);
			if(!is_array($popup_options))
			{
				$popup_options=array(  
								  'scrollbar' =>0, 
								  'locationbar' =>0, 
								  'directories' =>0, 
								  'statusbar' =>0, 
								  'menubar' =>0, 
								  'toolbar' =>0, 
								  'resizable' =>0,
								  'repeat_mode'=>'day', 
								  'cookie_duration'=>7,
								  'cookie_num_view'=>1								  
								  
								  );
			}
			$ad->popup_options=$popup_options;
			//echo "<pre>"; print_r($ad); echo "</pre>";
			
			$scrollbar=($popup_options['scrollbar'])?'yes':'no';
			$locationbar=($popup_options['locationbar'])?'yes':'no';
			$directories=($popup_options['directories'])?'yes':'no';
			$statusbar=($popup_options['statusbar'])?'yes':'no';
			$menubar=($popup_options['menubar'])?'yes':'no';
			$toolbar=($popup_options['toolbar'])?'yes':'no';
			$resizable=($popup_options['resizable'])?'yes':'no';
			if($ad->code_type=="link_in_iframe")#we use same db field to store popup window url.
			{
				$popup_url=$ad->iframe_source;
			}
			if(!isset($popup_options['repeat_mode'])){$popup_options['repeat_mode']="day"; $popup_options['cookie_duration']=7;$popup_options['cookie_num_view']=1;}
			?>
				<script type="text/javascript">
				var adGuruPopupWindowCalled=false;
				if(!adguru_should_show(<?php echo $ad->id; ?>, "<?php echo $popup_options['repeat_mode']; ?>" , <?php echo intval($popup_options['cookie_duration']); ?>, <?php echo intval($popup_options['cookie_num_view']); ?>))
				{
				adGuruPopupWindowCalled=true;
				}
				function callAdGuruPopupWindow()
				{
					if(adGuruPopupWindowCalled==true){return false;}
					adGuruPopupWindowCalled=true;
					var title=""; //kept this blank for IE issue http://stackoverflow.com/questions/710756/ie8-var-w-window-open-message-invalid-argument
					adGuruPopupWindow('<?php echo $popup_url; ?>',title, <?php echo $ad->width; ?>, <?php echo $ad->height; ?>, 'center', '<?php echo $scrollbar; ?>', '<?php echo $locationbar; ?>',	'<?php echo $directories; ?>','<?php echo $statusbar; ?>', 	'<?php echo $menubar; ?>', '<?php echo $toolbar; ?>', '<?php echo $resizable; ?>');
					
				}
				<?php if($ad->popup_timing==0){ ?>
				jQuery(document).ready(function(){callAdGuruPopupWindow();});
				<?php }elseif($ad->popup_timing==-1){ ?>
					jQuery('body').click(function(){callAdGuruPopupWindow();});
				<?php }else{?>
					jQuery(document).ready(function(){setTimeout(callAdGuruPopupWindow,<?php echo $ad->popup_timing ?>*1000);});
				<?php }?>
				</script>

					
		<?php 
		}#end if($tot_slide==0)
	}#end if(is_array($links))	

}#end adguru_print_window_popup_script


#===================SOME ACTIONS======================================

function adguru_enqueue_scripts() {
	wp_enqueue_script('jquery');
}

add_action( 'wp_enqueue_scripts', 'adguru_enqueue_scripts' );

function adguru_head()
{?>
	<script language="javascript" type="text/javascript">
	var ADGURU_COOKIE_PREFIX="<?php echo ADGURU_COOKIE_PREFIX ?>";
	<!--
	var adGuruWin=null;
	function adGuruPopupWindow(url,title,width,height,position, scroll, locationbar, directories, status , menubar, toolbar , resizable)
	{
	if(position=="random"){LeftPosition=(screen.width)?Math.floor(Math.random()*(screen.width-width)):100;TopPosition=(screen.height)?Math.floor(Math.random()*((screen.height-height)-75)):100;}
	if(position=="center"){LeftPosition=(screen.width)?(screen.width-width)/2:100;TopPosition=(screen.height)?(screen.height-height)/2:100;}
	else if((position!="center" && position!="random") || position==null){LeftPosition=0;TopPosition=20}
	var settings='width='+width+',height='+height+',top='+TopPosition+',left='+LeftPosition+',scrollbars='+scroll+',location='+locationbar+',directories='+directories+',status='+status+',menubar='+menubar+',toolbar='+toolbar+',resizable='+resizable+'';
	adGuruWin=window.open(url,title,settings);
	}
	// -->
	
	function adguru_setCookie(cname,cvalue,exdays)
	{
	var d = new Date();
	d.setTime(d.getTime()+(exdays*24*60*60*1000));
	var expires = "expires="+d.toGMTString();
	document.cookie = cname + "=" + cvalue + "; " + expires + "; path=/";
	} 	

	function adguru_getCookie(cname)
	{
	var name = cname + "=";
	var ca = document.cookie.split(';');
	for(var i=0; i<ca.length; i++)
	  {
	  var c = ca[i].trim();
	  if (c.indexOf(name)==0) return c.substring(name.length,c.length);
	  }
	return "";
	}

	function adguru_should_show(ad_id, repeat_mode , cookie_duration, num_view)
	{
		var cname="";var c;	var should_show=false;
		switch(repeat_mode)
		{
		case "day":
			{
			
			cname=ADGURU_COOKIE_PREFIX+"ad_"+ad_id+"_day";c=parseInt(adguru_getCookie(cname));if(isNaN(c)){c=0;}
			if(c!=0){should_show=false;}else{should_show=true; adguru_setCookie(cname,1,cookie_duration);}
			break;
			}
		case "view":
			{
			cname=ADGURU_COOKIE_PREFIX+"ad_"+ad_id+"_view";c=parseInt(adguru_getCookie(cname));if(isNaN(c)){c=0;}
			if(c>=num_view){should_show=false;}else{should_show=true; adguru_setCookie(cname,c+1,365); return should_show;}
			break;
			}
		case "always":
			{
			should_show=true;
			break;
			}	
		
		}
		return should_show;
	}	
		
	</script>	
	
<?php 
}

add_action('wp_head','adguru_head');



function adguru_footer()
{
	adguru_print_modal_popup_script();
	adguru_print_window_popup_script();
}#end function adguru_footer

add_action('wp_footer', 'adguru_footer');

function adguru_delete_post_action($post_id)
{
global $wpdb;
$wpdb->query("DELETE FROM ".ADGURU_LINKS_TABLE." WHERE page_type='singular' AND object_id=".$post_id);
}

add_action('deleted_post', 'adguru_delete_post_action');

#=====================================PUBLIC FUNCTIONS==========================================================
function adguru_ad($ad_id){adguru_show_ad($ad_id);}
function adguru_zone($zone_id){adguru_show_zone($zone_id);}
?>