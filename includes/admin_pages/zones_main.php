<?php
		global $wpdb;
		echo '<div class="wrap adguru_page">';
		echo '<div id="icon-edit-pages" class="icon32"></div><h2>Ad Zones</h2>';
		if(isset($_GET['tab'])){$tab=intval($_GET['tab']);}
		if(!$tab){$tab=1;}		
		?>
		<h2 class="nav-tab-wrapper">
			<a class='nav-tab<?php echo ($tab==1)?" nav-tab-active":""?>' href="admin.php?page=adguru_adzones&tab=1">All Zones</a>
			<a class='nav-tab<?php echo ($tab==2)?" nav-tab-active":""?>' href="admin.php?page=adguru_adzones&tab=2">Add New Zone</a>
			<a class='nav-tab' href="admin.php?page=adguru_bannerzonelinks">Set Banners to Zones</a>
		</h2><br />
		
	<?php
	if($tab==1){
	include_once(ADGURU_PLUGIN_DIR."/includes/admin_pages/zones_tab1.php");
	}else{include_once(ADGURU_PLUGIN_DIR."/includes/admin_pages/zones_tab2.php");}
	
	echo '</div>'; #end wrap

?>