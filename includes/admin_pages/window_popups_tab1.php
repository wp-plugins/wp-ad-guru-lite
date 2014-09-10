<?php
#tab1
		global $wpdb;
		$action=$_GET['action'];
		$camp_id=$_GET['camp_id'];
		if($action && $camp_id)
		{
			switch($action)
			{
				case 'delete':
				{
					#deleteing...
					$wpdb->query("DELETE FROM ".ADGURU_ADS_TABLE." WHERE id=".$camp_id);
					$wpdb->query("DELETE FROM ".ADGURU_LINKS_TABLE." WHERE ad_id=".$camp_id);	
					echo '<meta http-equiv="refresh" content="0;URL=?page=adguru_window_popups">';
				break;
				}
			}
		}#end of if($action && $camp_id)
		 if(isset($_GET['skey']))
		 {
			$keyword_list=$this->get_keywords_from_string(stripslashes($_GET['skey']));
			if($keyword_list){$search=true;}
		}
		if($search)
		{
			
			$tot_key=count($keyword_list);
			$i=0;
			$condition=" ";
			foreach($keyword_list as $key)
			{
			$i++;
			$condition.="ad_type='window_popup' AND name LIKE '%".esc_sql($key)."%' ";
				if($i!=$tot_key){$condition.=" AND ";}
			}
			
			$SQL="SELECT * FROM ".ADGURU_ADS_TABLE." WHERE ".$condition." ORDER BY id DESC";
		}
		else #if($search)
		{
		
			$rpp=20; #Record Per page;.
			$npage=1; #current page number
			$offset=0;
			if(isset($_GET['npage'])){$npage=intval($_GET['npage']); $npage=abs($npage);}
			if(!$npage){$npage=1;}
			$offset=($npage-1)*$rpp;
			$total_record= $wpdb->get_var("SELECT COUNT(*) AS total_record FROM ".ADGURU_ADS_TABLE." WHERE ad_type='window_popup'");
			$SQL=" SELECT * FROM ".ADGURU_ADS_TABLE." WHERE ad_type='window_popup' ORDER BY id DESC LIMIT ".$offset." , ".$rpp;
		}#end if($search)
		
		$camps=$wpdb->get_results($SQL, ARRAY_A);
		
		?>

		<script type="text/javascript">
			jQuery(document).ready(function($) {
				//select input field text on click
				jQuery(".all_camp_table input[type=text]").click(function() {
					jQuery(this).select();
				});	
				
				jQuery('.zone_drop_btn').click(function(){
					var ad_drop_cont=jQuery(this).parent().children('.zone_drop_box').first();
					if(jQuery(ad_drop_cont).is(":visible"))
					{
						jQuery(ad_drop_cont).slideUp();
					}
					else
					{
						jQuery(ad_drop_cont).slideDown();
					}
				});					
						
			});
		</script>
		
		<table width="100%">
		<tr><td  width="40%">&nbsp;
			
		</td>
		<td width="60%" align="right">
			<form action="admin.php" method="get">
			<?php if($search){?><a href="?page=adguru_window_popups">Clear Search</a><?php } ?>	
			<input type="hidden" name="page" value="adguru_window_popups" />
			<input type="text" name="skey" value="<?php echo stripslashes($_GET['skey'])?>" class="sbox" size="40" /><input type="submit" value="Search" class="button" /></form>			
		</td>
		</tr>
		</table>
					
		<table class="widefat all_camp_table">
			<thead>
				<tr>
					<th>Name</th>
					<th  width="120">Size</th>
					<th  width="70">Status</th>
					<th  width="120">Controls</th>
				</tr>
			</thead>
				<?php foreach($camps as $camp){ 
							 $ad_id=$camp['id'];
				
				?>
				<tr>
					<td><strong><?php echo $camp['name']; ?></strong></td>
					<td><?php echo $camp['width']."x".$camp['height']?></td>
					<td><?php echo ($camp['active'])?'Active':'Deactive'; ?></td>
								
					<td align="center">
					<a class="camp_control_link camp_control_link_red" title="Preview" href="?page=adguru_window_popups&tab=2&action=copy&cp_from_id=<?php echo $ad_id;?>">Copy</a> | 
					<a class="camp_control_link" href="?page=adguru_window_popups&tab=2&cid=<?php echo $ad_id; ?>">Edit</a> | 
					<a class="camp_control_link camp_control_link_red" href="?page=adguru_window_popups&tab=1&action=delete&camp_id=<?php echo $ad_id;?>" onclick="return confirm('Are you sure you want to delete this modal popup?')">Delete</a>
					</td>
				</tr>
				<?php }?>
		</table>

			<?php if(!count($camps)){?><div style="text-align:center; color:#cc0000; font-weight:normal; margin-top: 5px;"><?php echo ($search)? "No window popup containing your search terms were found.":"No existing window popup. Please <a href=\"?page=adguru_window_popups&tab=2\">add a new window popup</a>." ?></div><?php }?>
			<?php if(!$search){$this->print_page_navi($total_record,$npage,$rpp, "admin.php?page=adguru_window_popups&tab=1");} ?>