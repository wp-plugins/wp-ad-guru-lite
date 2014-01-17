<?php
echo '<div class="wrap">';
echo '<div id="icon-edit-pages" class="icon32"></div><h2>WP Ad Guru Lite</h2>';
?>

<style type="text/css" >.button-primary.adguru-button{ margin-bottom:10px;}</style>
<br />
<a href="admin.php?page=adguru_adzones"><input type="button" class="button-primary adguru-button" value="All Zones" /></a>
<a href="admin.php?page=adguru_adzones&tab=2"><input type="button" class="button-primary adguru-button" value="Create new zone" /></a>

<a href="admin.php?page=adguru_banners"><input type="button" class="button-primary adguru-button" value="All Banners" /></a>
<a href="admin.php?page=adguru_banners&tab=2"><input type="button" class="button-primary adguru-button" value="Create new banner" /></a>
<a href="admin.php?page=adguru_bannerzonelinks"><input type="button" class="button-primary adguru-button" value="Set banner to zone" /></a>

<a href="admin.php?page=adguru_modal_popups"><input type="button" class="button-primary adguru-button" value="All modal popup" /></a>
<a href="admin.php?page=adguru_modal_popups&tab=2"><input type="button" class="button-primary adguru-button" value="Create new modal popup" /></a>
<a href="admin.php?page=adguru_modalpopuplinks"><input type="button" class="button-primary adguru-button" value="Set modal popup to pages" /></a>

<a href="admin.php?page=adguru_window_popups"><input type="button" class="button-primary adguru-button" value="All window popup" /></a>
<a href="admin.php?page=adguru_window_popups&tab=2"><input type="button" class="button-primary adguru-button" value="Create new window popup" /></a>
<a href="admin.php?page=adguru_windowpopuplinks"><input type="button" class="button-primary adguru-button" value="Set window popup to pages" /></a>
<script type="text/javascript">
	jQuery(document).ready(function($){
		$( ".accordian_box" ).accordion({
		heightStyle: "content", 
		collapsible:true
		});
	
	
	});
</script>

<h2 style="border:1px solid #eeeeee; text-align:center; font-size:30px;">User Guide(basic)</h2>
<div id="user_guide_box" class="accordian_box">
	<h3><strong>How to steup and show a banner ad?</strong></h3>
	<div>
		<strong>Step 1: Setup a zone</strong><br />
		A zone is a place where you want to show your banners. A zone is the container of banners. A zone can contain multiple banners. 
		If you did not add any zone yet, <a href="admin.php?page=adguru_adzones&tab=2"><strong>add a new zone</strong></a> first. When you create new zone, must check the checkbox for <strong>Active</strong>.  
		<br /><br />
		<strong>Step 2: Create a banner</strong><br />
		A banner is the block of ad codes. Go to <a href="admin.php?page=adguru_banners&tab=2"><strong>add new banner</strong></a> page and create a new banner. 
		<br /><br />
		<strong>Step 3: Set a banner to a zone</strong><br />
		To show your banner you have to specify the banner for a specific zone. 
		<ul style="list-style-type:disc; padding-left:30px;">
			<li>Go to <a href="admin.php?page=adguru_bannerzonelinks"><strong>set banner to zone</strong></a> page.</li>
			<li>There is a list of zone names. Select a zone where you want to show your banner.</li>
			<li>You will see some tab like default, home, singular......etc. A zone may be shown in many pages in your website. You can specify different ads set for different type of pages.
			    Chose a type of page. As beginner click on default tab.
			</li>
			<li>Click on "Add new banner" link</li>
			<li>A popup will come with a list of banners you created. Select a banner and click insert</li>
			<li>Your selected banner name will be shown with a text input field and a delete button. <strong>Put 100 in the % field</strong>.</li>
			<li>Click on <strong>Save Change</strong> button</li>
		</ul><br />
		<strong>Step 4: Show zone in website</strong><br />
		You have done zone and banner setting. Now you have to set the zone to a place of your website. There are three method to set a zone in a place of website.
		<ul style="list-style-type:disc; padding-left:30px;">
			<li><strong>Method-1 Using Widget :</strong> Go to <a href="widgets.php" target="_blank"><strong>widget settings</strong></a> page. Add <strong>"adGuru Zone"</strong> widget in your sidebar and choose a zone from the zone list.</li>
			<li>
			<strong>Method-2 Using PHP function :</strong>
					Add following php code anywhere in your site front-end pages. Replace the word <strong>'zone_id'</strong> with the <strong>id</strong> of the zone you want to show<br />
					<code>
						&lt;?php if(function_exists('adguru_zone')){adguru_zone(zone_id);} ?&gt;
					</code><br />
					<strong><em>Example:</em></strong><br />
					<code>
						&lt;?php if(function_exists('adguru_zone')){adguru_zone(1);} ?&gt;
					</code>			
			
			</li>
			<li><strong>Method-3 Using Shortcode :</strong>
					Use following shortcode in your post content<br />
					<code>
						[adguru zoneid="zone_id"]
					</code><br />
					<strong><em>Example:</em></strong><br />	
					<code>
						[adguru zoneid="1"]
					</code>				
			</li>
			
			
		</ul>
		<strong>Step 5: You are done</strong><br />
	
	</div>
	
	
	<h3>How to setup and show modal popups</h3>
	<div>
		<strong>Step 1: <a href="admin.php?page=adguru_modal_popups&tab=2">Add new modal popups</a></strong><br />
		<strong>Step 2: <a href="admin.php?page=adguru_modalpopuplinks">Set modal popups to pages</a></strong><br />
	</div>
	<h3>How to setup and show window popups</h3>
	<div>
		<strong>Step 1: <a href="admin.php?page=adguru_window_popups&tab=2">Add new window popups</a></strong><br />
		<strong>Step 2: <a href="admin.php?page=adguru_windowpopuplinks">Set window popups to pages</a></strong><br />
	</div>
	<h3>Ads and pages link editor guide</h3>
	<div>
		<div style="background:#F0F0F0; width:700px; padding:0px 5px 15px 5px; border:1px solid #DFDFDF;">
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
	</div>

</div>

<h2 style="border:1px solid #eeeeee; text-align:center; font-size:30px; margin-top:30px;"><a href="http://wpadguru.com" target="_blank" title="Detail guide">User Guide(Advance)</a></h2>

<?php 				
echo '</div>'; #end wrap
?>