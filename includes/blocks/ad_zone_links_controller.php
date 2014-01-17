<?php #ad_zone_links_controller.php called inside main class 
		global $wpdb;
		$zone_id=intval($arg['zone_id']);
		$post_id=intval($arg['post_id']);
		if($arg['page_type']=="")$arg['page_type']="--";
		if($arg['taxonomy']=="")$arg['taxonomy']="--";
		if($arg['term']=="")$arg['term']="--";
		$page_type=$arg['page_type']; 
		$taxonomy=$arg['taxonomy']; 
		$term=$arg['term']; 
		$object_id=$post_id;
		
?>
<script type="text/javascript" src="<?php echo ADGURU_PLUGIN_URL; ?>js/ad_zone_links_controller.js?var=<?php echo ADGURU_VERSION ?>"></script>	

	<link rel="stylesheet" href="<?php echo ADGURU_PLUGIN_URL; ?>css/ad_zone_links_controller.css?var=<?php echo ADGURU_VERSION ?>" />
	
	<?php
			 
			  
			  $sql="SELECT * FROM ".ADGURU_ZONES_TABLE." WHERE id=".$zone_id;
			  $zone=$wpdb->get_row($sql, ARRAY_A);		
			  if($zone){$width=$zone['width']; $height=$zone['height'];}else{$zone=array(); $width=0; $height=0;}
			  $all_country_list=$this->get_country_list();
			  $country_code_list=array('--');#we need default country -- at the begining of array
			  $country_list=array();
			  $ad_zone_link_set=array("--"=>array(array(array())));
			  $SQL="SELECT * FROM ".ADGURU_LINKS_TABLE." WHERE zone_id=".$zone_id." AND ad_type='banner' AND page_type='".$page_type."' AND taxonomy='".$taxonomy."' AND term='".$term."' AND object_id=".$post_id;
			  $ad_zone_links=$wpdb->get_results($SQL);		 	
			 
			  if($ad_zone_links)
			  {
			  	
				
				$adid_list=array();
				foreach($ad_zone_links as $links)
				{
					if($links->country_code !="--")
					{
					$country_code_list[] = $links->country_code;
					}
					$adid_list[]=$links->ad_id;
				}
				$adid_list=array_unique($adid_list);
				$adid_list_line=implode(",",$adid_list);
				$SQL="SELECT * FROM ".ADGURU_ADS_TABLE." WHERE id IN (".$adid_list_line.")";
				$all_linked_ads=$wpdb->get_results($SQL, OBJECT_K);				
				
				$country_code_list=array_unique($country_code_list);
				
				foreach($country_code_list as $code){$country_list[$code] = $all_country_list[$code];}
			  	
				$ad_zone_link_set=array();
				foreach($country_list as $code=>$name)
				{
					
					$ad_zone_link_set[$code]=array(array());
				}
				
				foreach($ad_zone_links as $links)
				{
					$ad_item=array("id"=>$links->ad_id, "name"=>$all_linked_ads[$links->ad_id]->name, "percentage"=>$links->percentage);
					$slide=$links->slide;
					$ad_zone_link_set[$links->country_code][$slide-1][]=$ad_item;
				}
				
				
			  }
			  else
			  {
			  
			  }#end if($ad_zone_links
			  $country_list["--"]="Default";

	 function generate_ad_slide_set($slide_set_arr)
	 {?>
		<div>	
			<div class="ad_slide_set_box">
				<?php $i=0; foreach($slide_set_arr as $slide) {$i++;?>
				<h3>Slide <?php echo $i?></h3>
				<div class="ad_slide">
					<div class="slide_header">
						<div class="sl_hd_left">Banner Name</div>
						<div class="sl_hd_middle"><span class="equal_button" title="Set all percentage fields equal"></span></div>
						<div class="sl_hd_right">&nbsp;</div>
						<div class="clear"></div>
					</div>
					<div class="ad_set">
						<?php foreach($slide as $ad){ if(isset($ad['id'])){?>
						<div class="ad_item" adid="<?php echo $ad['id']?>">
							<div class="ad_item_left"><?php echo $ad['name']?></div>
							<div class="ad_item_middle"><input type="text" size="3" class="percentage" value="100" readonly="readonly" /> %</div>
							<div class="ad_item_right"><span class="remove_ad_btn" title="Remove this ad"></span></div>
							<div class="clear"></div>
						</div>
						<?php } }?>
					</div>
					<div class="slide_footer">
						<div class="sl_ft_left"><span class="add_ad_btn" onclick="show_ad_list_modal(this)">Add new banner</span></div>
						<div class="sl_ft_middle">&nbsp;</span></div>
						<div class="sl_ft_right">&nbsp;</div>
						<div class="clear"></div>
					</div>
				</div>
				<?php } ?>
			</div>
			<div style="margin-top:10px; margin-bottom:10px;"><span class="add_slide_btn">Add New Slide</span></div>
		</div>	 
	 <?php 
	 }#end generate_ad_slide_set($slide_set_arr)
	 ?>
	 
	 <div id="premium_box_modal" title="Premium Feature" style="display:none;"><?php echo $this->print_permium_offer_box(); ?></div>
	 <div id="ad_list_modal" title="Insert Banner" style="display:none;">
	 	<div>
		<div style="width:240px; float:left;"><strong>Select a banner and click insert</strong></div>
		<div style="float:right; width:200px; margin-right:22px; text-align:right;"><input style="width:180px;" placeholder="Search" type="text" size="15" id="search_ad_list" /></div>
		</div>
		<div style="clear:both"></div>
		
			<div id="ads_list">
			<?php
			$SQL="SELECT * FROM ".ADGURU_ADS_TABLE." WHERE ad_type='banner' AND width=".$width." AND height=".$height." ORDER BY id DESC";
			$ad_camps=$wpdb->get_results($SQL);
			if(!count($ad_camps)){echo '<span style="color:#cc0000;">You have no Banner for this zone size <strong>'.$width.'x'.$height.'</strong> . <a href="admin.php?page=adguru_banners&tab=2">Enter new ad</a> in <strong>'.$width.'x'.$height.'</strong> size</span>';}
			else
			{
				foreach($ad_camps as $camp)
				{
					echo '<div class="ads_list_item" adid="'.$camp->id.'" >'.$camp->name.'</div>';
				}
			}
			?>
			</div>		
	 </div>
	 
	 <div id="link_editor" style="margin-top:10px;">	
		<div id="link_editor_header" style="display:none;">
		<h2>Manage ads for this zone</h2>
		</div>
		<div id="link_editor_body">
		<table class="widefat" style="width:630px;">
			<thead>
				<tr>
					<th width="20%">Country</th>
					<th width="80%">Banner Set For This Zone <span class="ad_zone_link_loading" style="float:right; display:none;"><img src="<?php echo ADGURU_PLUGIN_URL ?>images/loading.gif" alt="loading.." /></span></th>
				</tr>
			</thead>
			<tr>
				<td style=" background:#F3F3F3;">
					<ul id="ctab_set">
					<?php 
					$i=0;
					foreach($country_list as $code=>$name)
					{
						$i++;
						$selected=($code=="--")?' class="selected"':'';
						echo '<li tabid="'.$i.'" code="'.$code.'"'.$selected.'>'.$name.'</li>';
					}?>
					</ul>
					<span class="add_country_btn" id="add_new_country_btn">Add new country</span>
				</td>
				<td>
					<input type="hidden" id="set_zone_id" value="<?php echo $zone_id ?>"  />	
					<input type="hidden" id="set_page_type" value="<?php echo $arg['page_type'] ?>"  />
					<input type="hidden" id="set_taxonomy" value="<?php echo $arg['taxonomy'] ?>"  />
					<input type="hidden" id="set_term" value="<?php echo $arg['term'] ?>"  />
					<input type="hidden" id="set_post_id" value="<?php echo $post_id ?>"  />
							
					<div id="ad_zone_link_set_wrap">
						<?php 
						$j=0;
						foreach($country_list as $country_code=>$country_name){$j++;?>
						<div class="ctab_box ad_zone_link_set" tabid="<?php echo $j?>" id="ctab_box_<?php echo $j?>" <?php if($j==1) echo ' style="display:block"';?>>
							<div class="ctab_box_head">
								<div class="ctab_box_title"><?php if($j==1){echo "Default: for all country";}else{echo $country_name;}?></div>
								<div class="ctab_control_box" tabid="<?php echo $j?>"><?php if($j!=1){?><span class="remove_country_btn" title="Remove this country">&nbsp;</span><?php }?></div>
								<div class="clear"></div>
							</div>
							<?php 
							$slide_set_arr=$ad_zone_link_set[$country_code];
							generate_ad_slide_set($slide_set_arr);			
							?>
						</div>
						<?php 
						break;
						}?>
					</div>
				</td>
			</tr>
			<tr>
				<td colspan="2">
					<input type="button" class="button-primary" value="Save Change" style="width:100px;" onclick="update_ad_zone_link(this)" />
					<span class="ad_zone_link_msg"></span>
				</td>
			</tr>
		</table>
		</div><!--#link_editor_body-->
	</div><!--#link_editor-->
	<?php if($post_id==0){?>
	<div style="background:#F0F0F0; width:700px; padding:0px 5px 15px 5px; margin-top:40px; border:1px solid #DFDFDF;">
		<h2>User Guide for This Page</h2>
		<img src="<?php echo ADGURU_PLUGIN_URL;?>images/ad_zone_links_editor_guide.jpg" /><br />

		<strong>1. Country List:</strong> You can set ads for individual country where your visitor come from. Country name <strong>Default</strong> means all country. <br /><br />
		<strong>2. Slide:</strong> You are able to show multiple ads in same place like a courosel/slider. If you have only one slide then the ad will shown normally. If you have one more slide then the ads will be shown in a carousel. Ads will be changed in each 5 seconds.<br /><br />
		<strong>3. New Slide Button:</strong> After clicking on this buton a new slide will be added.  <br /><br />
		<strong>4. An Ad:</strong> This is an ad item you added for this slide. A slide may contains one more ads. But on the front-end only one ad is shown for a slide. If you have one more ads (eg. 4 ads) then ads is roteated based on its percentage you set.<br /><br />
  		<strong>5. New Ad Button:</strong> By clicking on this button a popup box is shown with a list of all ads you created. This list shows all ads those are match in size with the ad zone you selected. Select an ad and click <strong>Insert</strong> button.<br /><br />
		<strong>6. Rotator Percentage:</strong> Number of percentage, how many times this ad will be shown in 100 call. A slide can contain one more ads. Total of percentage value of all ads in same slide must be 100. If you have only one ad then keep it 100%.<br /><br />
		<strong>7. Equal Button:</strong> To auto fill rotator percentage value click on this button.<br /><br />
		<strong>8. Remove ad button:</strong> To remove an ad item click on this button.<br />
	</div>
	<?php } ?>