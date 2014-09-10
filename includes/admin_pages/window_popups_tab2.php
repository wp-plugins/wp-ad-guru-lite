<?php
#tab2

		global $wpdb;
		

		$camp=array();
		$camp['active']=1;
		$camp["popup_options"]=array(
					"scrollbar"=>0, 
					"locationbar"=>0, 
					"directories"=>0, 
					"statusbar"=>0, 
					"menubar"=>0, 
					"toolbar"=>0, 
					"resizable"=>0,
					"repeat_mode"=>"day", 
					"cookie_duration"=>7,
					"cookie_num_view"=>1
				);
				
		if(isset($_GET['action'])){$action=$_GET['action'];}else{$action="";}
		$camp_id=intval($_GET['cid']);
		$camp_msg="";
		$camp_input_error=false;
		if($camp_id)
		{
			if(isset($_POST['save']))
			{ 
			#do updating data
				$input_validation=$this->check_window_popup_input_validation();
				$input_error_fields=$input_validation['errors'];
				$msg=$input_validation['message']; if($msg!=""){$input_error=true;}	
				if(!$input_error)
				{
				$popup_options=array(
						"scrollbar"=>intval($_POST['scrollbar']), 
						"locationbar"=>intval($_POST['locationbar']), 
						"directories"=>intval($_POST['directories']), 
						"statusbar"=>intval($_POST['statusbar']), 
						"menubar"=>intval($_POST['menubar']), 
						"toolbar"=>intval($_POST['toolbar']), 
						"resizable"=>intval($_POST['resizable']), 
						"repeat_mode"=>($_POST['repeat_mode']),
						"cookie_duration"=>intval($_POST['cookie_duration']),
						"cookie_num_view"=>intval($_POST['cookie_num_view'])
						
					);				
				
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
				own_html='".esc_sql(stripslashes(trim($_POST['own_html'])))."' ,
				popup_timing=".intval($_POST['popup_timing'])." , 
				enable_stealth_mode=".intval($_POST['enable_stealth_mode'])." , 
				popup_options='".esc_sql(serialize($popup_options))."' 
				WHERE ad_type='window_popup' AND id=".$camp_id;				
				
				$wpdb->query($sql);
				$msg="Window popup has been saved";
				#getting date from db.
				$sql="SELECT * FROM ".ADGURU_ADS_TABLE." WHERE ad_type='window_popup' AND id=".$camp_id;
				$camp=$wpdb->get_row($sql, ARRAY_A);
				$camp['popup_options']=unserialize($camp['popup_options']);							
				}					
			
			}
			else
			{
				#getting date from db.
				$sql="SELECT * FROM ".ADGURU_ADS_TABLE." WHERE ad_type='window_popup' AND id=".$camp_id;
				$camp=$wpdb->get_row($sql, ARRAY_A);
				
				if(!$camp)
					{
					$camp=array();
					$camp['active']=1;
					$camp["popup_options"]=array(
								"scrollbar"=>intval($_POST['scrollbar']), 
								"locationbar"=>intval($_POST['locationbar']), 
								"directories"=>intval($_POST['directories']), 
								"statusbar"=>intval($_POST['statusbar']), 
								"menubar"=>intval($_POST['menubar']), 
								"toolbar"=>intval($_POST['toolbar']), 
								"resizable"=>intval($_POST['resizable']), 
								"repeat_mode"=>($_POST['repeat_mode']),
								"cookie_duration"=>intval($_POST['cookie_duration']),
								"cookie_num_view"=>intval($_POST['cookie_num_view'])								
							);					
					}
					else
					{
					$camp['popup_options']=unserialize($camp['popup_options']);	
					}
			
			}#end if(isset($_POST['save']))
		}
		elseif(isset($_POST['save']))
		{
		#add new
			$input_validation=$this->check_window_popup_input_validation();
			$input_error_fields=$input_validation['errors'];
			$msg=$input_validation['message']; if($msg!=""){$input_error=true;}	
			if(!$input_error)
			{
			$popup_options=array(
					"scrollbar"=>intval($_POST['scrollbar']), 
					"locationbar"=>intval($_POST['locationbar']), 
					"directories"=>intval($_POST['directories']), 
					"statusbar"=>intval($_POST['statusbar']), 
					"menubar"=>intval($_POST['menubar']), 
					"toolbar"=>intval($_POST['toolbar']), 
					"resizable"=>intval($_POST['resizable']),
					"repeat_mode"=>($_POST['repeat_mode']),
					"cookie_duration"=>intval($_POST['cookie_duration']),
					"cookie_num_view"=>intval($_POST['cookie_num_view'])
				);			
			
			$sql="INSERT INTO ".ADGURU_ADS_TABLE." 
			(ad_type, name, description, width , height , active , code_type, html_code, image_source, image_link, link_target, iframe_source, own_html, popup_timing, enable_stealth_mode , popup_options) 
			VALUES (
			'window_popup', 
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
			 '".esc_sql(stripslashes(trim($_POST['own_html'])))."', 
			  ".intval($_POST['popup_timing']).", 
			  ".intval($_POST['enable_stealth_mode']).", 
			  '".esc_sql(serialize($popup_options))."'  
			 )";			
			$wpdb->query($sql);
			$msg="New window popup has been saved";							
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
			$sql="SELECT * FROM ".ADGURU_ADS_TABLE." WHERE ad_type='window_popup' AND id=".$copy_camp_id;
			$campCopy=$wpdb->get_row($sql, ARRAY_A);
				if($campCopy)
				{
				$camp=$campCopy;
				$camp['name']=$camp['name'].'_copy';
				$camp['popup_options']=unserialize($camp['popup_options']);
				}
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
			"own_html"=>stripslashes($_POST['own_html']), 
			"popup_timing"=>$_POST['popup_timing'], 
			"enable_stealth_mode"=>$_POST['enable_stealth_mode'] ,
			"popup_options"=>array(
					"scrollbar"=>intval($_POST['scrollbar']), 
					"locationbar"=>intval($_POST['locationbar']), 
					"directories"=>intval($_POST['directories']), 
					"statusbar"=>intval($_POST['statusbar']), 
					"menubar"=>intval($_POST['menubar']), 
					"toolbar"=>intval($_POST['toolbar']), 
					"resizable"=>intval($_POST['resizable']),
					"repeat_mode"=>($_POST['repeat_mode']),
					"cookie_duration"=>intval($_POST['cookie_duration']),
					"cookie_num_view"=>intval($_POST['cookie_num_view'])					
				)
			);		
		}				
			
		
		echo "<br /><br />";
		if($msg!=""){if($input_error){echo '<div id="adguru_msg" class="msg_error" style="width:788px">'.$msg.'</div>';}else{echo '<div id="adguru_msg" class="msg_success" style="width:788px">'.$msg.'</div>';}}
		?>
<?php $code_type = $camp['code_type']; if(!$code_type) $code_type="link_in_iframe";?>
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
	<tr><td><label>Window Popup Name</label></td><td><input type="text" name="camp_name" class="input_long<?php echo ($input_error_fields['camp_name'])?' input_error':''?>" size="30" value="<?php echo $camp['name'];?>" /></td></tr>
	<tr><td><label>Description</label></td><td><textarea name="description"  class="input_long<?php echo ($input_error_fields['description'])?' input_error':''?>" cols="15" rows="4"><?php echo $camp['description'];?></textarea></td></tr>
	<tr><td><label>Size</label></td>
		<td>
		
		Width <input type="text" name="width"  id="width" size="4"  value="<?php echo $camp['width'];?>" class="<?php echo ($input_error_fields['width'])?' input_error':''?>" /> 
		Height <input type="text" name="height" id="height" size="4" value="<?php echo $camp['height'];?>" class="<?php echo ($input_error_fields['height'])?' input_error':''?>"/>
		
		</td>
	</tr>

		
	<tr>
		<td>
		<label>Code Type</label>
		</td>
		<td>
		<?php //echo "<pre>"; print_r($camp); echo "</pre>"; ?>
		<select id="code_type" name="code_type" onchange="show_code_type_valbox()" class="<?php echo ($input_error_fields['code_type'])?' input_error':''?>">
			<option value="link_in_iframe"<?php echo ($camp['code_type']=="link_in_iframe")? ' selected="selected"':''?> >URL in window</option>			
			<option style="color:#999999" value="html"<?php echo ($camp['code_type']=="html")? ' selected="selected"':''?> >HTML</option>
			<option style="color:#999999" value="link_with_image"<?php echo ($camp['code_type']=="link_with_image")? ' selected="selected"':''?> >Link With Image</option>
			<option style="color:#999999" value="create_your_own"<?php echo ($camp['code_type']=="create_your_own")? ' selected="selected"':''?> >Create Your Own</option>
		</select>
		</td>
	</tr>
	<tr id="valbox_html" class="code_type_valbox">
		<td>
		<label>HTML/JavaScript Code</label>
		</td>
		<td>
		<textarea  cols="25" rows="4" name="html_code" class="html_code input_long" disabled="disabled" readonly="readonly">Premium Feature</textarea>
		</td>
	</tr>
	<tr>
	<tr id="valbox_image_source" class="code_type_valbox">
		<td><label>Image Source</label></td>
		<td>
		<input type="text" size="50" name="image_source" class="image_source input_long" value="Premium Feature" readonly="readonly"/>&nbsp;
		<input  disabled="disabled" class="button" type="button" value="Upload/Select Image" />
		<?php if($camp['image_source']!=""){?><br /><br /><img src="<?php echo $camp['image_source']?>"  width="300"  /><?php }?>
	
		
		</td>
	</tr>
	<tr id="valbox_image_link" class="code_type_valbox">
		<td><label>Image Link</label></td><td><input type="text" size="50" name="image_link" class="image_link input_long" value="Premium Feature" readonly="readonly"/></td>
	</tr>
	<tr id="valbox_link_target" class="code_type_valbox">
	<td><label>Target</label></td><td><select name="link_target" class="link_target">
				<option value="_blank"<?php echo ($camp['link_target']=="_blank")? ' selected="selected"':''?> >_blank</option>
				<option value="_self"<?php echo ($camp['link_target']=="_self")? ' selected="selected"':''?> >_self</option>
				<option value="_parent"<?php echo ($camp['link_target']=="_parent")? ' selected="selected"':''?> >_parent</option>
				<option value="_top"<?php echo ($camp['link_target']=="_top")? ' selected="selected"':''?> >_top</option>
			</select>
		</td>
	</tr>
	
	<tr id="valbox_iframe_source" class="code_type_valbox">
		<td><label>URL in popup window</label></td><td><input type="text" size="50" name="iframe_source" class="iframe_source input_long<?php echo ($input_error_fields['iframe_source'])?' input_error':''?>"  value="<?php echo $camp['iframe_source']?>"/></td>
	</tr>
	<tr id="valbox_own_html" class="code_type_valbox">
		<td><label>Create your own</label></td>
		<td>
			<img src="<?php echo ADGURU_PLUGIN_URL;?>images/wpeditor.jpg" />
		</td>
	</tr>	
	<tr>
		<td><label>Popup Timing</label></td>
		<td>
		<select name="popup_timing" id="popup_timing">
			<option value="0" <?php if($camp['popup_timing']=="0"){ ?> selected="selected"<?php }?> >On Page Load</option>
			<option value="-1" <?php if($camp['popup_timing']=="-1"){ ?> selected="selected"<?php }?> >On Click Anywhere</option>
			<option value="3" <?php if($camp['popup_timing']=="3"){ ?> selected="selected"<?php }?> >3 Second Delay</option>
			<option value="5" <?php if($camp['popup_timing']=="5"){ ?> selected="selected"<?php }?> >5 Second Delay</option>
			<option value="10" <?php if($camp['popup_timing']=="10"){ ?> selected="selected"<?php }?> >10 Second Delay</option>
			<option value="15" <?php if($camp['popup_timing']=="15"){ ?> selected="selected"<?php }?> >15 Second Delay</option>
			<option value="30" <?php if($camp['popup_timing']=="30"){ ?> selected="selected"<?php }?> >30 Second Delay</option>
			<option value="60" <?php if($camp['popup_timing']=="60"){ ?> selected="selected"<?php }?> >60 Second Delay</option>
		</select>
		<a href="#" class="tooltip" title="Select the appropriate time for the popup to load."><img class="tipBtn" src="<?php echo ADGURU_PLUGIN_URL;?>images/tip.png" align="bottom" /></a>
		</td>
	</tr>
	<tr>
		<td><label>Show this Popup:</label></td>
		<td>
			<?php 
			$popup_options=$camp['popup_options'];
			if(isset($popup_options['repeat_mode'])){$repeat_mode=$popup_options['repeat_mode'];}else{$repeat_mode="day";}
			if(isset($popup_options['cookie_duration'])){$cookie_duration=$popup_options['cookie_duration'];}else{$cookie_duration=7;}
			if(isset($popup_options['cookie_num_view'])){$cookie_num_view=$popup_options['cookie_num_view'];}else{$cookie_num_view=1;}
			?>
			<input type="radio" name="repeat_mode" id="repeat_mode_day" value="day" <?php echo ($repeat_mode=="day")? ' checked="checked"':''; ?> /> <label for="repeat_mode_day">After Every</label> &nbsp;<input type="text" name="cookie_duration" size="2" value="<?php echo $cookie_duration; ?>" /> Days<br />
			<input type="radio" name="repeat_mode" id="repeat_mode_view" value="view" <?php echo ($repeat_mode=="view")? ' checked="checked"':''; ?> /> <label for="repeat_mode_view"> View only</label> <input type="text" name="cookie_num_view" size="2" value="<?php echo $cookie_num_view;?>" /> Times<br />
			<input type="radio" name="repeat_mode" id="repeat_mode_always" value="always" <?php echo ($repeat_mode=="always")? ' checked="checked"':''; ?> /> <label for="repeat_mode_always"> Always</label>
		</td>
	</tr>	
	<tr>
		<td><label>Window Options</label></td>
		<td>
			
			<?php $popup_options=$camp['popup_options'];?>
			<input type="checkbox" name="scrollbar" id="scrollbar" value="1" <?php echo ($popup_options['scrollbar'])?' checked="checked"':''?> /> <label for="scrollbar">Scroll Bar</label><br />
			<input type="checkbox" name="locationbar" id="locationbar" value="1" <?php echo ($popup_options['locationbar'])?' checked="checked"':''?> /> <label for="locationbar">Location Bar</label><br />
			<input type="checkbox" name="directories" id="directories" value="1" <?php echo ($popup_options['directories'])?' checked="checked"':''?> /> <label for="directories">Directories</label><br />
			<input type="checkbox" name="statusbar" id="statusbar" value="1" <?php echo ($popup_options['statusbar'])?' checked="checked"':''?> /> <label for="statusbar">Status Bar</label><br />
			<input type="checkbox" name="menubar" id="menubar" value="1" <?php echo ($popup_options['menubar'])?' checked="checked"':''?> /> <label for="menubar">Menu Bar</label><br />
			<input type="checkbox" name="toolbar" id="toolbar" value="1" <?php echo ($popup_options['toolbar'])?' checked="checked"':''?> /> <label for="toolbar">Tool Bar</label><br />
			<input type="checkbox" name="resizable" id="resizable" value="1" <?php echo ($popup_options['resizable'])?' checked="checked"':''?> /> <label for="resizable">Resizable</label><br />
		</td>
	
	</tr>
		
	<tr>
		<td colspan="2">
		<input disabled="disabled" type="checkbox" value="1" id="enable_stealth_mode" name="enable_stealth_mode" <?php echo  ($camp['enable_stealth_mode'])? 'checked="checked"':''?> /><label for="enable_stealth_mode"> Enable Stealth Mode</label> 
		<a href="#" class="tooltip" title="When stealth mode is enabled, the popup will not be display normally. To display popup you have to use a quary parameter kw with page url. eg. example.com/?kw=something "><img class="tipBtn" src="<?php echo ADGURU_PLUGIN_URL;?>images/tip.png" align="bottom" /></a>
		<br /><br />
		</td>
	</tr>	
	<tr>
		<td colspan="2">
		<input type="submit" name="save" class="button-primary" value="Save" style="width:100px;" />
		</td>
	</tr>
</table>
</form>