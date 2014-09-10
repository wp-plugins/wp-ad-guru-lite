<?php
		global $wpdb;
		$camp=array();
		$camp['active']=1;
		if(isset($_GET['action'])){$action=$_GET['action'];}else{$action="";}
		$camp_id=intval($_GET['cid']);
		$camp_msg="";
		$camp_input_error=false;
		if($camp_id)
		{
			if(isset($_POST['save']))
			{ 
			#do updating data
				$input_validation=$this->check_ad_input_validation();
				$input_error_fields=$input_validation['errors'];
				$msg=$input_validation['message']; if($msg!=""){$input_error=true;}	
				if(!$input_error)
				{
				$sql="UPDATE ".ADGURU_ADS_TABLE." 
				SET 
				name='".esc_sql(stripslashes(trim($_POST['camp_name'])))."',  
				description='".esc_sql(stripslashes(trim($_POST['description'])))."', 
				width=".intval($_POST['width'])." , 
				height=".intval($_POST['height'])." , 
				active=".intval($_POST['active'])." , 
				code_type='".esc_sql(stripslashes(trim($_POST['code_type'])))."', 
				html_code='".esc_sql(stripslashes(trim($_POST['html_code'])))."', 
				image_source='".esc_sql(stripslashes(trim($_POST['image_source'])))."', 
				image_link='".esc_sql(stripslashes(trim($_POST['image_link'])))."', 
				link_target='".esc_sql(stripslashes(trim($_POST['link_target'])))."', 
				iframe_source='".esc_sql(stripslashes(trim($_POST['iframe_source'])))."', 
				own_html='".esc_sql(stripslashes(trim($_POST['own_html'])))."' 
				WHERE ad_type='banner' AND id=".$camp_id;				
				
				$wpdb->query($sql);
				$msg="Banner has been saved";
				#getting date from db.
				$sql="SELECT * FROM ".ADGURU_ADS_TABLE." WHERE ad_type='banner' AND id=".$camp_id;
				$camp=$wpdb->get_row($sql, ARRAY_A);							
				}					
			
			}
			else
			{
				#getting date from db.
				$sql="SELECT * FROM ".ADGURU_ADS_TABLE." WHERE ad_type='banner' AND id=".$camp_id;
				$camp=$wpdb->get_row($sql, ARRAY_A);
				if(!$camp){$camp=array();$camp['active']=1;}
			
			}#end if(isset($_POST['save']))
		}
		elseif(isset($_POST['save']))
		{
		#add new
			$input_validation=$this->check_ad_input_validation();
			$input_error_fields=$input_validation['errors'];
			$msg=$input_validation['message']; if($msg!=""){$input_error=true;}	
			if(!$input_error)
			{
			$sql="INSERT INTO ".ADGURU_ADS_TABLE." 
			(ad_type, name, description, width , height , active , code_type, html_code, image_source, image_link, link_target, iframe_source, own_html ) 
			VALUES (
			'banner', 
			'".esc_sql(stripslashes(trim($_POST['camp_name'])))."', 
			'".esc_sql(stripslashes(trim($_POST['description'])))."', 
			 ".intval($_POST['width']).", 
			 ".intval($_POST['height']).", 
			 ".intval($_POST['active']).", 
			 '".esc_sql(stripslashes(trim($_POST['code_type'])))."', 
			 '".esc_sql(stripslashes(trim($_POST['html_code'])))."', 
			 '".esc_sql(stripslashes(trim($_POST['image_source'])))."', 
			 '".esc_sql(stripslashes(trim($_POST['image_link'])))."', 
			 '".esc_sql(stripslashes(trim($_POST['link_target'])))."', 
			 '".esc_sql(stripslashes(trim($_POST['iframe_source'])))."', 
			 '".esc_sql(stripslashes(trim($_POST['own_html'])))."' 
			 )";			
			$wpdb->query($sql);
			$msg="New banner has been saved";							
			}		
		
		}
		elseif($action =='copy')
		{
			if(isset($_GET['cp_from_id']))
			{
			$copy_camp_id=intval($_GET['cp_from_id']);
			}
			if($copy_camp_id)
			{
			#getting date from db.
			$sql="SELECT * FROM ".ADGURU_ADS_TABLE." WHERE ad_type='banner' AND id=".$copy_camp_id;
			$camp=$wpdb->get_row($sql, ARRAY_A);
			$camp['name']=$camp['name'].'_copy';
			}
		}#end if ($camp_id)
		
	if($input_error)
		{
			#getting data from $_POST[];
			$camp=array(
			"name"=>stripslashes($_POST['camp_name']),
			"description"=>stripslashes($_POST['description']),
			"width"=>$_POST['width'],
			"height"=>$_POST['height'],
			"active"=>$_POST['active'], 
			"code_type"=>stripslashes($_POST['code_type']), 
			"html_code"=>stripslashes($_POST['html_code']), 
			"image_source"=>stripslashes($_POST['image_source']),
			"image_link"=>stripslashes($_POST['image_link']), 
			"link_target"=>stripslashes($_POST['link_target']), 
			"iframe_source"=>stripslashes($_POST['iframe_source']), 
			"own_html"=>stripslashes($_POST['own_html'])
			);		
		}	
		
		echo "<br /><br />";
		if($msg!=""){if($input_error){echo '<div id="adguru_msg" class="msg_error" style="width:788px">'.$msg.'</div>';}else{echo '<div id="adguru_msg" class="msg_success" style="width:788px">'.$msg.'</div>';}}
		?>
<?php $code_type = $camp['code_type']; if(!$code_type) $code_type="html";?>
<script type="text/javascript">
	jQuery(document).ready(function($) {
		jQuery('.code_type_valbox').hide();
		<?php if($code_type=="html"){?>
			jQuery("#valbox_html").show('slow');
			 <?php	
			}
		elseif($code_type=="link_with_image"){?> 
			jQuery("#valbox_image_source").show('slow');
			jQuery("#valbox_image_link").show('slow');
			jQuery("#valbox_link_target").show('slow');
			<?php
			}
		elseif($code_type=="link_in_iframe"){?>
			jQuery("#valbox_iframe_source").show('slow');	
		 <?php 
		 }
		 else{
		 ?>
		 jQuery("#valbox_own_html").show('slow');
		 <?php }?>
						
		});//end $(document).ready()...
			
	function show_code_type_valbox()
	{
		var code_type=jQuery("#code_type").val();
		jQuery(".code_type_valbox").hide();
		switch(code_type)
		{
			case "html":
			{
			jQuery("#valbox_html").show('slow');			
			break;
			}
			case "link_with_image":
			{
			jQuery("#valbox_image_source").show('slow');
			jQuery("#valbox_image_link").show('slow');
			jQuery("#valbox_link_target").show('slow');			
			break;
			}			
			case "link_in_iframe":
			{
			jQuery("#valbox_iframe_source").show('slow');			
			break;
			}
			case "create_your_own":
			{
			jQuery("#valbox_own_html").show('slow');			
			break;
			}					
		}
	
	}

</script>
<form action="" method="post">
<table class="widefat" id="ad_editor" style="width:800px;">
	<thead>
		<tr>
		<th width="150">&nbsp;</th>
		<th>&nbsp;</th>
		</tr>
	</thead>
	<tr style="display:none;">
		<td><label>Active</label></td>
		<td>
		<input type="checkbox" name="active" value="1" <?php echo (intval($camp['active']))?' checked="checked"':''?>  />
		</td>
	</tr>
	<tr><td><label>Banner Name</label></td><td><input type="text" name="camp_name" class="input_long<?php echo ($input_error_fields['camp_name'])?' input_error':''?>" size="30" value="<?php echo $camp['name'];?>" /></td></tr>
	<tr><td><label>Description</label></td><td><textarea name="description"  class="input_long<?php echo ($input_error_fields['description'])?' input_error':''?>" cols="15" rows="4"><?php echo $camp['description'];?></textarea></td></tr>
	<tr><td><label>Size</label></td>
		<td>
		<?php 
			if(($camp['width']=="" || $camp['height']=="") && !isset($_POST['save'])){$camp['width']=300;$camp['height']=250;}
			$size_txt=$camp['width']."x".$camp['height'];
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
		Width <input type="text" name="width"  id="width" size="4"  value="<?php echo $camp['width'];?>" class="<?php echo ($input_error_fields['width'])?' input_error':''?>" <?php echo (!$custom_size)?' readonly="readonly"':'';?> /> 
		Height <input type="text" name="height" id="height" size="4" value="<?php echo $camp['height'];?>" class="<?php echo ($input_error_fields['height'])?' input_error':''?>" <?php echo (!$custom_size)?' readonly="readonly"':'';?>/>
		</span>
		</td>
	</tr>		
	<tr>
		<td>
		<label>Code Type</label>
		</td>
		<td>
		<?php //echo "<pre>"; print_r($camp); echo "</pre>"; ?>
		<select id="code_type" name="code_type" onchange="show_code_type_valbox()" class="<?php echo ($input_error_fields['code_type'])?' input_error':''?>">
			<option value="html"<?php echo ($camp['code_type']=="html")? ' selected="selected"':''?> >HTML</option>
			<option style="color:#999999" value="link_with_image"<?php echo ($camp['code_type']=="link_with_image")? ' selected="selected"':''?> >Link With Image</option>
			<option style="color:#999999" value="link_in_iframe"<?php echo ($camp['code_type']=="link_in_iframe")? ' selected="selected"':''?> >Link in iFrame</option>
			<option style="color:#999999"value="create_your_own"<?php echo ($camp['code_type']=="create_your_own")? ' selected="selected"':''?> >Create Your Own</option>
		</select>
		</td>
	</tr>
	<tr id="valbox_html" class="code_type_valbox">
		<td>
		<label>HTML/JavaScript Code</label>
		</td>
		<td>
		<textarea  cols="25" rows="4" name="html_code" class="html_code input_long<?php echo ($input_error_fields['html_code'])?' input_error':''?>"><?php echo $camp['html_code']?></textarea>
		</td>
	</tr>
	<tr>
	<tr id="valbox_image_source" class="code_type_valbox">
		<td><label>Image Source</label></td>
		<td>
		<input type="text" size="50" name="image_source" class="image_source input_long"  value="Premium Feature" readonly="readonly"/>&nbsp;
		<input  disabled="disabled" class="button" type="button" value="Upload/Select Image" />
		<?php if($camp['image_source']!=""){?><br /><br /><img src="<?php echo $camp['image_source']?>"  width="300"  /><?php }?>
	
		
		</td>
	</tr>
	<tr id="valbox_image_link" class="code_type_valbox">
		<td><label>Image Link</label></td><td><input type="text" size="50" name="image_link" class="image_link input_long"  value="Premium Feature" readonly="readonly"/></td>
	</tr>
	<tr id="valbox_link_target" class="code_type_valbox">
	<td><label>Target</label></td><td><select name="link_target" class="link_target" disabled="disabled">
				<option value="_blank"<?php echo ($camp['link_target']=="_blank")? ' selected="selected"':''?> >_blank</option>
				<option value="_self"<?php echo ($camp['link_target']=="_self")? ' selected="selected"':''?> >_self</option>
				<option value="_parent"<?php echo ($camp['link_target']=="_parent")? ' selected="selected"':''?> >_parent</option>
				<option value="_top"<?php echo ($camp['link_target']=="_top")? ' selected="selected"':''?> >_top</option>
			</select>
		</td>
	</tr>
	
	<tr id="valbox_iframe_source" class="code_type_valbox">
		<td><label>Link in iFrame</label></td><td><input type="text" size="50" name="iframe_source" class="iframe_source input_long"  value="Premium Feature" readonly="readonly"/></td>
	</tr>
	<tr id="valbox_own_html" class="code_type_valbox">
		<td><label>Create your own</label></td>
		<td>
			<img src="<?php echo ADGURU_PLUGIN_URL;?>images/wpeditor.jpg" />
		</td>
	</tr>
	<tr><td colspan="2"><input type="submit" name="save" class="button-primary" value="Save" style="width:100px;" /></td></tr>
</table>
</form>