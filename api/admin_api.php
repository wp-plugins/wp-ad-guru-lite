<?php 
require_once("__inc_wp.php"); #Include Wordpress Core Enviroment. It Loads Theme Functions and Plugins also. 
global $wpdb;
global $adGuru;
$action = trim($_REQUEST['action']);
$response=array();
if($action=="")
{
	$response['status']='fail';
	$response['message']='No action was selected';
	echo json_encode($response);
	exit;
}

if(!$adGuru->is_permitted_user())
{
	$response['status']='fail';
	$response['message']='No permission, either your are not permitted for this action or you are logged out';
	echo json_encode($response);
	exit;
}

switch($action)
{
	
	case "save_ad_zone_links":
	{	
		
		$zone_id=intval($_POST['zone_id']);
		$ad_type=trim($_POST['ad_type']); if($ad_type==""){$ad_type="banner";}
		
		if($zone_id || $ad_type!="banner" )
		{
			$set=array();
			$post_id=intval($_POST['post_id']);
			$page_type=trim($_POST['page_type']); if($page_type=="")$page_type="--";
			$taxonomy=trim($_POST['taxonomy']); if($taxonomy=="")$taxonomy="--";
			$term=trim($_POST['term']); if($term=="")$term="--";
			$object_id=0;
			if($post_id)
			{
				$page_type="singular";
				$taxonomy="--";
				$term="--";
				$object_id=$post_id;
				
			}
			else
			{
				switch($page_type)
				{
					case "--":
					{
						$page_type="--";
						$taxonomy="--";
						$term="--";	
						$object_id=0;				
					break;
					}
					case "home":
					{
						$page_type="home";
						$taxonomy="--";
						$term="--";	
						$object_id=0;					
					break;
					}
					case "singular":
					{
						#no change					
					break;
					}
					case "taxonomy":
					{
						#no change
					break;
					}					
					case "author":
					{
						#no change
					break;
					}
					case "404":
					{
						#no change
					break;
					}
					case "search":
					{
						#no change
					break;
					}					
									
				}#end switch($page_type)
			
			}#end if($post_id)
			
			$ad_zone_link_set=$_POST['ad_zone_link_set'];
			foreach($ad_zone_link_set as $link_set_item)
			{
				$country_code=$link_set_item['country_code'];
				$ad_slide_set=$link_set_item['ad_slide_set'];
				$slide=0;
				if(is_array($ad_slide_set) && count($ad_slide_set))
				{
					foreach($ad_slide_set as $ad_slide)
					{
						$slide++;
						foreach($ad_slide as $ad)
						{
							$ad_id=$ad['ad_id'];
							$percentage=intval($ad['percentage']);
							$xx=array("ad_type"=>$ad_type, "zone_id"=>$zone_id, "page_type"=>$page_type, "taxonomy"=>$taxonomy, "term"=>$term, "object_id"=>$object_id, "country_code"=>$country_code, "slide"=>$slide, "ad_id"=>$ad_id, "percentage"=>$percentage);
							$set[]=$xx;
						}
					}
				}
			
			}#end foreach($ad_zone_link_set
			#at first delete all exisiting record for this zone_id and post_id
			$wpdb->query("DELETE FROM ".ADGURU_LINKS_TABLE." WHERE ad_type='".$ad_type."' AND zone_id=".$zone_id." AND page_type='".$page_type."' AND taxonomy='".$taxonomy."' AND term='".$term."' AND object_id=".$object_id);	
			#insert new record. here we are using multiple query for all new record, but we can inseart all at once. 
			foreach($set as $s)
			{
				$SQL="INSERT INTO ".ADGURU_LINKS_TABLE." (ad_type, zone_id, page_type, taxonomy, term, object_id, country_code, slide, ad_id, percentage) 
					VALUES(
						'".$s['ad_type']."', 
						".$s['zone_id'].",  
						'".$s['page_type']."', 
						'".$s['taxonomy']."', 
						'".$s['term']."', 
						".$s['object_id'].", 
						'".$s['country_code']."', 
						".$s['slide'].", 
						".$s['ad_id'].", 
						".$s['percentage']."   	
						)";
				$wpdb->query($SQL);		
			}
			
		$response['status']='success';
		$response['message']="Saved";			
		}
		else
		{
		$response['status']='fail';
		$response['message']="No ad zone given";			
		}#end if($zone_id || $ad_type!="banner" )
	break;
	}#end case save_ad_zone_links
	
}#end switch($action)
echo json_encode($response);
?>