<?php #popup_links_controller.php called inside main class 
		global $wpdb;
		$zone_id=0;
		$ad_type=$arg['ad_type']; 
		$post_id=intval($arg['post_id']);
		if($arg['page_type']=="")$arg['page_type']="--";
		if($arg['taxonomy']=="")$arg['taxonomy']="--";
		if($arg['term']=="")$arg['term']="--";
		$page_type=$arg['page_type']; 
		$taxonomy=$arg['taxonomy']; 
		$term=$arg['term']; 
		$object_id=$post_id;				
		if($ad_type=="modal_popup"){$popup_type_name="Modal Popup"; $add_new_popup_link="admin.php?page=adguru_modal_popups&tab=2";}
		if($ad_type=="window_popup"){$popup_type_name="Window Popup"; $add_new_popup_link="admin.php?page=adguru_window_popups&tab=2";}
		
?>
<script type="text/javascript" src="<?php echo ADGURU_PLUGIN_URL; ?>js/popup_links_controller.js?var=<?php echo ADGURU_VERSION ?>"></script>	

	<link rel="stylesheet" href="<?php echo ADGURU_PLUGIN_URL; ?>css/popup_links_controller.css?var=<?php echo ADGURU_VERSION ?>" />
	
	<?php
			  
			  $all_country_list=$this->get_country_list();
			  $country_code_list=array('--');#we need default country -- at the begining of array
			  $country_list=array();
			  $ad_zone_link_set=array("--"=>array(array(array())));
			  $SQL="SELECT * FROM ".ADGURU_LINKS_TABLE." WHERE ad_type='".$ad_type."' AND zone_id=0 AND page_type='".$page_type."' AND taxonomy='".$taxonomy."' AND term='".$term."' AND object_id=".$post_id;
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
				<div class="ad_slide">
					<div class="slide_header">
						<div class="sl_hd_left">Popup Name</div>
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
						<div class="sl_ft_left"><span class="add_ad_btn" onclick="show_ad_list_modal(this)">Add new popup</span></div>
						<div class="sl_ft_middle">&nbsp;</span></div>
						<div class="sl_ft_right">&nbsp;</div>
						<div class="clear"></div>
					</div>
				</div>
				<?php } ?>
			</div>
		</div>	 
	 <?php 
	 }#end generate_ad_slide_set($slide_set_arr)
	 ?>
	 
	<div id="premium_box_modal" title="Premium Feature" style="display:none;"><?php echo $this->print_permium_offer_box(); ?></div>
	 <div id="ad_list_modal" title="Insert Popup" style="display:none;">
	 	<div>
		<div style="width:240px; float:left;"><strong>Select a popup and click insert</strong></div>
		<div style="float:right; width:200px; margin-right:22px; text-align:right;"><input style="width:180px;" placeholder="Search" type="text" size="15" id="search_ad_list" /></div>
		</div>
		<div style="clear:both"></div>

			<div id="ads_list">
			<?php
			$SQL="SELECT * FROM ".ADGURU_ADS_TABLE." WHERE ad_type='".$ad_type."' ORDER BY id DESC";
			$ad_camps=$wpdb->get_results($SQL);
			if(!count($ad_camps)){echo '<span style="color:#cc0000;">You have no '.$popup_type_name.'. <a href="'.$add_new_popup_link.'">Enter new '.strtolower($popup_type_name).'</a></span>';}
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
		<h2>Set <?php echo $popup_type_name ?> for this page</h2>
		</div>
		<div id="link_editor_body">
		<table class="widefat" style="width:630px;">
			<thead>
				<tr>
					<th width="20%">Country</th>
					<th width="80%">Set <?php echo $popup_type_name ?> For This Page <span class="ad_zone_link_loading" style="float:right; display:none;"><img src="<?php echo ADGURU_PLUGIN_URL ?>images/loading.gif" alt="loading.." /></span></th>
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
					<input type="hidden" id="set_ad_type" value="<?php echo $arg['ad_type'] ?>"  />
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
					<input type="button" class="button-primary" value="Save Change" style="width:100px;" onclick="update_modal_popup_link(this)" />
					<span class="ad_zone_link_msg"></span>
				</td>
			</tr>
		</table>
		</div><!--#link_editor_body-->
	</div><!--#link_editor-->