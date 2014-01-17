<?php

	function adguru_admin_tabs( $tabs, $current = 'default', $var="tab",  $query_vars=array(), $maintabs=true ) { 
		$page=trim($_GET['page']);
		$links = array();
		echo ($maintabs)?'<div id="icon-link-manager" class="icon32"><br></div>':"";
		
		echo ($maintabs)?'<h2 class="nav-tab-wrapper">':'<h3 class="nav-tab-wrapper">';
		foreach( $tabs as $tab => $name ){
			$class = ( $tab == $current ) ? ' nav-tab-active' : '';
			echo "<a class='nav-tab$class' href='?page=$page&$var=$tab"; foreach($query_vars as $key=>$val){echo "&".$key."=".$val;} echo "'>$name</a>";
			
		}
		echo ($maintabs)?'</h2>':'</h3>';
	}

	global $wpdb;
	$page=trim($_GET['page']);
	if($page=="adguru_modalpopuplinks"){$ad_type="modal_popup"; $ad_type_name="modal popups"; }
	if($page=="adguru_windowpopuplinks"){$ad_type="window_popup"; $ad_type_name="window popups";}

	echo '<div class="wrap">';
	echo '<div id="icon-edit-pages" class="icon32"></div><h2>Set '; echo ($ad_type=="modal_popup")?'Modal':'Window'; echo ' Popups to Pages</h2>';	
	
		$tabs = array( '--'=>'Default', 'home' => 'Home', 'singular'=>'Singular', 'taxonomy' => 'Taxonomy', 'author'=>"Author", '404_not_found'=>'404', 'search' => 'Search' ); 
		
		$tab=$_GET['tab']; if($tab==""|| !array_key_exists($tab,$tabs))$tab="--";
		
		adguru_admin_tabs($tabs, $tab);
		switch($tab)
		{
			case "--": #default
			{
				$this->print_msg("Set ".$ad_type_name." only for <strong>all pages</strong> of this site");
				$this->popup_links_controller(array("ad_type"=>$ad_type, "page_type"=>"--", "taxonomy"=>"--", "term"=>"--", "post_id"=>0));
			
			break;
			}
			case "home":
			{
				$this->print_msg("Set ".$ad_type_name." only for <strong>home</strong> page");
				$this->popup_links_controller(array("ad_type"=>$ad_type, "page_type"=>"home", "taxonomy"=>"--", "term"=>"--", "post_id"=>0));
			
			break;
			}
			case "singular":
			{
			
			$typeswehave = array('post,revision'); //oneTarek
			$post_types=get_post_types('','names'); 
			$rempost = array('attachment','revision','nav_menu_item');
			$post_types = array_diff($post_types,$rempost);	
			$post_types = array_merge(array('--'=>"Any Type Post"),$post_types,array('specific_term'=>"Specific Term"));
			$tab2=$_GET['tab2']; if($tab2=="" || !array_key_exists($tab2,$post_types))$tab2="--";
			$post_type=$tab2;
			
			?>
				<div style="padding-left:20px;">
				<?php 
				adguru_admin_tabs($post_types, $tab2 , 'tab2', array('tab'=>'singular'),  false); 
				if($tab2!="specific_term")
				{
				if($post_type=="--"){$msg="Set ".$ad_type_name." for <strong>any type</strong> of single page";}else{$msg="Set ".$ad_type_name." for <strong>".$post_type."</strong> single page";}
				$this->print_msg($msg);
				$this->print_permium_offer_box();
				}
				else
				{
				?>
					<div style="padding-left:20px;">
					<?php 
					
					$taxonomies = get_taxonomies(array()); 
					$remTax=array("nav_menu","link_category","post_format","single", "Single"); #we remove "single" because it a reserve word for this plugin. This word "Single" we are using to store as a taxonomy for when  post types are stored as terms.
					$taxonomies = array_diff($taxonomies,$remTax);						
					$tab3=$_GET['tab3']; if($tab3=="" || !array_key_exists($tab3,$taxonomies))$tab3="category";
					
					adguru_admin_tabs($taxonomies, $tab3 , 'tab3', array('tab2'=>$tab2,'tab'=>'singular'),  false); 
					$taxonomy_name=$taxonomies[$tab3];
						$taxonomy=get_taxonomy($taxonomy_name);
						$selected_term_slug=trim($_GET['term_slug']);
						if($selected_term_slug!="")
						{
							$selected_term_exists=term_exists( $selected_term_slug, $taxonomy_name);
							$selected_term=get_term( $selected_term_exists['term_id'], $taxonomy_name);#user may input term name instead of term slug, so we ensuring the term slug.
							$selected_term_slug=$selected_term->slug;
							$selected_term_name=$selected_term->name;
						}
						else
						{
						$selected_term_exists=0;
						}#end if($selected_term_slug!="")


						if($taxonomy->hierarchical)
						{
						  $categories = get_categories(array('hide_empty'=>0,'taxonomy'=>$taxonomy_name)); 
				
						?>
						<form action="admin.php" method="get">
						 	<input type="hidden" name="page" value="<?php echo $page ?>" />
							<input type="hidden" name="tab3" value="<?php echo $tab3 ?>" />
							<input type="hidden" name="tab2" value="specific_term" />
							<input type="hidden" name="tab" value="singular" />						
										
						<select name="term_slug"  onchange="this.form.submit()"> 
						 <option value=""><?php echo 'Select a '.$taxonomy_name; ?></option> 
						 <?php 
						  foreach ($categories as $category) {
							$option = '<option value="'.$category->slug.'"'; $option .= ($category->slug==$selected_term_slug)? ' selected="selected" ':''; $option .= '>';
							$option .= $category->cat_name;
							$option .= ' ('.$category->category_count.')';
							$option .= '</option>';
							echo $option;
						  }
						 ?>
						</select>
						</form>
						<?php 	
							
							if($selected_term_exists)
							{
							$this->print_msg("Set ".$ad_type_name." for a single post which is in <strong>".$selected_term_slug." ".$taxonomy_name."</strong>");
							$this->print_permium_offer_box();
							}				
						}
						else
						{
						  

							if($selected_term_exists)
							{
							$this->print_msg("Set ".$ad_type_name." for a single post which is in <strong>".$selected_term_slug." ".$taxonomy_name."</strong>");
							$this->print_permium_offer_box();
							echo "<br><br>";
							}
							else
							{
								if(isset($_GET['term_slug']))
								{
								echo '<span style="color:#ff0000;">Your given term does not exists. Enter a valid term slug</span><br><br>';
								}
							}
						  
						  
						  
						  $sql="SELECT DISTINCT term FROM ".ADGURU_LINKS_TABLE." WHERE zone_id=0 AND ad_type='".$ad_type."' AND page_type='singular' AND taxonomy='".$taxonomy_name."'";
						  $used_term_list=$wpdb->get_results($sql);
						  
						  ?>
						 <form action="admin.php" method="get">
						 	<input type="hidden" name="page" value="<?php echo $page ?>" />
							<input type="hidden" name="tab3" value="<?php echo $tab3 ?>" />
							<input type="hidden" name="tab2" value="specific_term" />
							<input type="hidden" name="tab" value="singular" />
							Add New term slug :<input type="text" size="15"  name="term_slug" /><input type="submit" class="button" name="add_term" value="Add and Select" />
						  </form><br />
						  OR choose any previous used term to edit.
						  <div id="used_term_list">
						  	<?php 
								
								if(count($used_term_list))
								{
									foreach($used_term_list as $t)
									{
										$link='admin.php?page='.$page.'&tab3='.$tab3.'&tab2=specific_term&tab=singular&term_slug='.$t->term;
										echo '<a href="'.$link.'">'.$t->term.'</a>';
									}
								}
								else
								{
								echo 'You did not use any term for this taxonomy and zone yet';
								}
							?>
						  </div>
						   
						  <?php 												  
						 						  					
						}#if($taxonomy->hierarchical)
					?>
					</div>
				<?php }#end of if($tab2!="specific_taerm") ?>	
				</div>
			<?php 			
				
			
			break;
			}
			case "taxonomy":
			{
				$taxonomies = get_taxonomies(array()); 
				$remTax=array("nav_menu","link_category","post_format","single", "Single"); #we remove "single" because it a reserve word for this plugin. This word "Single" we are using to store as a taxonomy for when  post types are stored as terms. 
				$taxonomies = array_diff($taxonomies,$remTax);
				$taxonomies = array_merge(array("--"=>"All"),$taxonomies);			
				$tab2=$_GET['tab2']; if($tab2=="" || !array_key_exists($tab2,$taxonomies))$tab2="--";
			?>
				<div style="padding-left:20px;">
				<?php 

				adguru_admin_tabs($taxonomies, $tab2 , 'tab2', array('tab'=>'taxonomy'),  false); 
				
				$taxonomy_name=$taxonomies[$tab2];
				
				if($tab2=='--')
				{
				$this->print_msg("Set ".$ad_type_name." for <strong>category</strong>, <strong>tag</strong> or any kind of <strong>custom taxonomy</strong> archive pages");
				$this->print_permium_offer_box();
				}
				else
				{
				
					$tab3=$_GET['tab3']; if($tab3=="" || ($tab3!="--" && $tab3!="specific_term"))$tab3="--";?>
					<div style="padding-left:20px;">
					<?php
					adguru_admin_tabs(array("--"=>"All Terms", "specific_term"=>"Specific Term"), $tab3 , 'tab3', array('tab2'=>$tab2,'tab'=>'taxonomy'),  false); 
					if($tab3=="--")
					{
					$this->print_msg("Set ".$ad_type_name." for <strong>".$taxonomy_name." archive</strong> pages");
					$this->print_permium_offer_box();
					}
					else
					{
					
					
						$taxonomy=get_taxonomy($taxonomy_name);
						$selected_term_slug=trim($_GET['term_slug']);
						if($selected_term_slug!="")
						{
							$selected_term_exists=term_exists( $selected_term_slug, $taxonomy_name);
							$selected_term=get_term( $selected_term_exists['term_id'], $taxonomy_name);#user may input term name instead of term slug, so we ensuring the term slug.
							$selected_term_slug=$selected_term->slug;
							$selected_term_name=$selected_term->name;
						}
						else
						{
						$selected_term_exists=0;
						}#end if($selected_term_slug!="")
					
						if($taxonomy->hierarchical)
						{
						  $categories = get_categories(array('hide_empty'=>0,'taxonomy'=>$taxonomy_name)); 
				
						?>
						
						<form action="admin.php" method="get">
						 	<input type="hidden" name="page" value="<?php echo $page ?>" />
							<input type="hidden" name="tab2" value="<?php echo $tab2 ?>" />
							<input type="hidden" name="tab3" value="specific_term" />
							<input type="hidden" name="tab" value="taxonomy" />
																	
						<select name="term_slug"  onchange="this.form.submit()"> 
						 <option value=""><?php echo 'Select a '.$taxonomy_name; ?></option> 
						 <?php 
						  foreach ($categories as $category) {
							$option = '<option value="'.$category->slug.'"'; $option .= ($category->slug==$selected_term_slug)? ' selected="selected" ':''; $option .= '>';
							$option .= $category->cat_name;
							$option .= ' ('.$category->category_count.')';
							$option .= '</option>';
							echo $option;
						  }
						 ?>
						</select>
						</form>
						<?php 	
							
							if($selected_term_exists)
							{
							$this->print_msg("Set ".$ad_type_name." for <strong>".$taxonomy_name."</strong> archive page when term is <strong>".$selected_term_slug."</strong>");
							$this->print_permium_offer_box();
							}				
						}
						else
						{
						 
							if($selected_term_exists)
							{
							$this->print_msg("Set ".$ad_type_name." for <strong>".$taxonomy_name."</strong> archive page when term is <strong>".$selected_term_slug."</strong>");
							$this->print_permium_offer_box();
							echo "<br><br>";
							}
							else
							{
								if(isset($_GET['term_slug']))
								{
								echo '<span style="color:#ff0000;">Your given term does not exists. Enter a valid term slug</span><br><br>';
								}
							}						  

						  $sql="SELECT DISTINCT term FROM ".ADGURU_LINKS_TABLE." WHERE zone_id=0 AND ad_type='".$ad_type."' AND page_type='taxonomy' AND taxonomy='".$taxonomy_name."'";
						  $used_term_list=$wpdb->get_results($sql);					  

						  ?>
						  
						 <form action="admin.php" method="get">
						 	<input type="hidden" name="page" value="<?php echo $page ?>" />
							<input type="hidden" name="tab2" value="<?php echo $tab2 ?>" />
							<input type="hidden" name="tab3" value="specific_term" />
							<input type="hidden" name="tab" value="taxonomy" />						 
							Add New term slug :<input type="text" size="15"  name="term_slug" /><input type="submit" class="button" name="add_term" value="Add and Select" />
						  </form><br />
						  OR choose any previous used term to edit.
						  <div id="used_term_list">
						  	<?php 
								
								if(count($used_term_list))
								{
									foreach($used_term_list as $t)
									{
										
										$link='admin.php?page='.$page.'&tab2='.$tab2.'&tab3=specific_term&tab=taxonomy&term_slug='.$t->term;
										echo '<a href="'.$link.'">'.$t->term.'</a>';
									}
								}
								else
								{
								echo 'You did not use any term for this taxonomy and zone yet';
								}
							?>
						  </div>
						  
						  <?php 
					  
					  }#end if($tab3=="default")					
					
					}#if($taxonomy->hierarchical)
				}#if($tab2=='default')	
				?>
				</div>
			<?php 			
			
			break;
			}
			case "author":
			{
				$this->print_msg("Set ".$ad_type_name." for <strong> Author</strong> archive page");
				$this->print_permium_offer_box();
			
			break;
			}									
			case "404_not_found":
			{
				$this->print_msg("Set ".$ad_type_name." for <strong> 404 not found</strong> page");
				$this->print_permium_offer_box();
			
			break;
			}
			case "search":
			{
				$this->print_msg("Set ".$ad_type_name." for <strong> Search Result</strong> page");
				$this->print_permium_offer_box();
			
			break;
			}								
		
		}#end switch($tab)
		
	echo '</div>'; #end wrap

?>