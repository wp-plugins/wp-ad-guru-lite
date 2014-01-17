<?php	
echo '<div class="wrap adguru_page">';
echo '<div id="icon-edit-pages" class="icon32"></div><h2>Modal Popups</h2>';
	if(isset($_GET['tab'])){$tab=intval($_GET['tab']);}
	if(!$tab){$tab=1;}
?>
		<h2 class="nav-tab-wrapper">
			<a class='nav-tab<?php echo ($tab==1)?" nav-tab-active":""?>' href="admin.php?page=adguru_modal_popups&tab=1">All Modal Popups</a>
			<a class='nav-tab<?php echo ($tab==2)?" nav-tab-active":""?>' href="admin.php?page=adguru_modal_popups&tab=2">Add New Modal Popup</a>
			<a class='nav-tab' href="admin.php?page=adguru_modalpopuplinks">Set Modal Popups to Pages</a>
		</h2>
<?php 
	if($tab==1){
	include_once(ADGURU_PLUGIN_DIR."/includes/admin_pages/modal_popups_tab1.php");
	}else{include_once(ADGURU_PLUGIN_DIR."/includes/admin_pages/modal_popups_tab2.php");}
 echo '</div>'; #end wrap 
?>