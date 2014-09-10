<?php		
		global $wpdb;
		$zone=array();
		if(isset($_GET['action'])){$action=$_GET['action'];}else{$action="";}
		$zone_id=intval($_GET['zid']);
		$zone_msg="";
		$zone_input_error=false;
		if($zone_id)
		{
			if(isset($_POST['save']))
			{ 
			#do updating data
				$input_validation=$this->check_zone_input_validation();
				$input_error_fields=$input_validation['errors'];
				$msg=$input_validation['message']; if($msg!=""){$input_error=true;}	
				if(!$input_error)
				{
				$sql="UPDATE ".ADGURU_ZONES_TABLE." 
				SET 
				name='".esc_sql(stripslashes(trim($_POST['zone_name'])))."',  
				description='".esc_sql(stripslashes(trim($_POST['description'])))."', 
				width=".intval($_POST['width'])." , 
				height=".intval($_POST['height'])." , 
				active=".intval($_POST['active'])." 
				WHERE id=".$zone_id;				
				
				$wpdb->query($sql);
				$msg="Zone has been saved";
				#getting date from db.
				$sql="SELECT * FROM ".ADGURU_ZONES_TABLE." WHERE id=".$zone_id;
				$zone=$wpdb->get_row($sql, ARRAY_A);							
				}					
			
			}
			else
			{
				#getting date from db.
				$sql="SELECT * FROM ".ADGURU_ZONES_TABLE." WHERE id=".$zone_id;
				$zone=$wpdb->get_row($sql, ARRAY_A);			
			
			}#end if(isset($_POST['save']))
		}
		elseif(isset($_POST['save']))
		{
		#add new
			$input_validation=$this->check_zone_input_validation();
			$input_error_fields=$input_validation['errors'];
			$msg=$input_validation['message']; if($msg!=""){$input_error=true;}	
			if(!$input_error)
			{
			$sql="INSERT INTO ".ADGURU_ZONES_TABLE." 
			(name, description, width , height , active ) 
			VALUES (
			'".esc_sql(stripslashes(trim($_POST['zone_name'])))."', 
			'".esc_sql(stripslashes(trim($_POST['description'])))."', 
			 ".intval($_POST['width']).", 
			 ".intval($_POST['height']).", 
			 ".intval($_POST['active'])."
			 )";			
			$wpdb->query($sql);
			$msg="New zone has been saved";							
			}		
		
		}
		elseif($action =='copy')
		{
			if(isset($_GET['cp_from_id']))
			{
			$copy_zone_id=intval($_GET['cp_from_id']);
			}
			if($copy_zone_id)
			{
			#getting date from db.
			$sql="SELECT * FROM ".ADGURU_ZONES_TABLE." WHERE id=".$copy_zone_id;
			$zone=$wpdb->get_row($sql, ARRAY_A);
			$zone['name']=$zone['name'].'_copy';
			}
		}#end if ($zone_id)
		
	if($input_error)
		{
			#getting data from $_POST[];
			$zone=array(
			"name"=>stripslashes($_POST['zone_name']),
			"description"=>stripslashes($_POST['description']),
			"width"=>$_POST['width'],
			"height"=>$_POST['height'],
			"active"=>$_POST['active']
			);		
		}				
		
		
		if($msg!=""){if($input_error){echo '<div id="adguru_msg" class="msg_error" style="width:688px">'.$msg.'</div>';}else{echo '<div id="adguru_msg" class="msg_success" style="width:688px">'.$msg.'</div>';}}
		?>
		
		<form action="" method="post">
		<table class="widefat" id="zone_editor" style="width:700px;">
			<thead>
				<tr>
					<th width="120">&nbsp;</th>
					<th>&nbsp;</th>
				</tr>
			</thead>
			<tr>
				<td><label>Zone Name</label></td>
				<td>
				<input type="text" name="zone_name"  size="30" value="<?php echo $zone['name'];?>" class="input_long<?php echo ($input_error_fields['zone_name'])?' input_error':''?>" />
				</td>
			</tr>
			<tr><td><label>Description</label></td><td><textarea name="description"  class="input_long<?php echo ($input_error_fields['description'])?' input_error':''?>" cols="15" rows="4"><?php echo $zone['description'];?></textarea></td></tr>
			
			<tr><td><label>Size</label></td>
				<td>
				<?php 
					if(($zone['width']=="" || $zone['height']=="") && !isset($_POST['save'])){$zone['width']=300;$zone['height']=250;}
					$size_txt=$zone['width']."x".$zone['height'];
					if(!in_array($size_txt,array("300x250", "468x60", "120x600", "728x90", "120x90", "160x600", "120x60", "125x125", "180x150"))){ $custom_size=true; }
				?>
				<select id="size_list" style="width:312px;">
					<option value="300x250" <?php echo ($size_txt=="300x250")?' selected="selected"':'';?>>Medium Rectangle (300 x 250)</option>                                    
					<option value="468x60" <?php echo ($size_txt=="468x60")?' selected="selected"':'';?>>Full Banner (468 x 60)</option>
					<option value="120x600" <?php echo ($size_txt=="120x600")?' selected="selected"':'';?>>Skyscraper (120 x 600)</option>
					<option value="728x90" <?php echo ($size_txt=="728x90")?' selected="selected"':'';?>>Leaderboard (728 x 90)</option>
					<option value="120x90" <?php echo ($size_txt=="120x90")?' selected="selected"':'';?>>Button 1 (120 x 90)</option>
					<option value="160x600" <?php echo ($size_txt=="160x600")?' selected="selected"':'';?>>Wide Skyscraper (160 x 600)</option>
					<option value="120x60" <?php echo ($size_txt=="120x60")?' selected="selected"':'';?>>Button 2 (120 x 60)</option>
					<option value="125x125" <?php echo ($size_txt=="125x125")?' selected="selected"':'';?>>Square Button (125 x 125)</option>
					<option value="180x150" <?php echo ($size_txt=="180x150")?' selected="selected"':'';?>>Rectangle (180 x 150)</option>
					<option value="custom" <?php echo ($custom_size)?' selected="selected"':'';?>>Custom</option>
				</select>
				<span id="custom_size_box">
				Width <input type="text" name="width"  id="width" size="4"  value="<?php echo $zone['width'];?>" class="<?php echo ($input_error_fields['width'])?' input_error':''?>" <?php echo (!$custom_size)?' readonly="readonly"':'';?> /> 
				Height <input type="text" name="height" id="height" size="4" value="<?php echo $zone['height'];?>" class="<?php echo ($input_error_fields['height'])?' input_error':''?>" <?php echo (!$custom_size)?' readonly="readonly"':'';?>/>
				</span>
				</td>
			</tr>
			<tr><td><label>Active</label></td><td><input type="checkbox" name="active" value="1"  <?php echo  ($zone['active'])? 'checked="checked"':''?> /></td></tr>
			<tr>
				<td colspan="2">
					<input type="submit" name="save" value="Save" class="button-primary" />
				</td>
			</tr>
			</table>
			</form>