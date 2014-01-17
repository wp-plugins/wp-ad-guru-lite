	//ad_zone_links_controller.js
	var selected_ad_set;
	
	jQuery(document).ready(function ($) {

		$( ".ad_slide_set_box" ).accordion({
				heightStyle: "content", 
				collapsible:true
				}) .sortable({
				axis: "y",
				handle: "h3",
				stop: function( event, ui ) {
					// IE doesn't register the blur when sorting
					// so trigger focusout handlers to remove .ui-state-focus
					ui.item.children( "h3" ).triggerHandler( "focusout" );
					}
				});
	
//----------------------------------DRAGGING AND SHORTING------------------------------------------------	
		$(".ad_set").sortable();
		
//-----------------------------DIALOG---------------------------------------
		$( "#ad_list_modal" ).dialog({
		height: 355,
		width: 500,
		modal: true,
		autoOpen: false,
		buttons: {
			"Insert": function() {
			
				if($('.ads_list_item.selected').size()==0){ alert("Please select an item"); return false;}
				var selected=$('.ads_list_item.selected').first();
				var h=$(selected).html();
				var adid=$(selected).attr('adid');
				var html="";
					html+='<div class="ad_item" adid="'+adid+'">';
						html+='<div class="ad_item_left">'+h+'</div>';
						html+='<div class="ad_item_middle"><input type="text" size="3" class="percentage" value="100" readonly="readonly" /> %</div>';
						html+='<div class="ad_item_right"><span class="remove_ad_btn" title="Remove this ad"></span></div>';
						html+='<div class="clear"></div>';
					html+='</div>';
				
				var already_added_one=$(selected_ad_set).children('.ad_item').size();
				if(already_added_one==1){$( this ).dialog( "close" ); return;}
				var already_added=$(selected_ad_set).children('[adid='+adid+']').size();
				if(already_added==0){$(selected_ad_set).append(html);}
				$( this ).dialog( "close" );
			
			},
			Cancel: function() {$( this ).dialog( "close" );}
				}		
		
		});//END DIALOG

		$( "#premium_box_modal" ).dialog({
		height: 355,
		width: 500,
		modal: true,
		autoOpen: false,
		buttons: {
			Hide: function() {$( this ).dialog( "close" );}
				}		
		
		});//END DIALOG
		
//---------------------------end dialog stystem--------------------------
	
		$(".add_slide_btn").live('click', function(){show_premium_box_modal();});	
			
		$('.ads_list_item').click(function(){
			$('.ads_list_item').removeClass('selected');
			$(this).addClass('selected');
		});
	
		$(".remove_ad_btn").live("click", function(){
			$(this).parent().parent().remove();	
		
		});	
	
	
		//END TABBING..................
		
		$(".save_ad_zone_links").click(function(){
		});//$(".save_ad_zone_links").click
		
		$("#add_new_country_btn").click(function(){show_premium_box_modal();});	
				
		$(".equal_button").live("click",function(){return true;});

		//*******************************START SEARCH TECHNIQUE **************************************
			//search technique help: http://www.marceble.com/2010/02/simple-jquery-table-row-filter/
			//Declare the custom selector 'containsIgnoreCase'.
		  jQuery.expr[':'].containsIgnoreCase = function(n,i,m){
			  return jQuery(n).text().toUpperCase().indexOf(m[3].toUpperCase())>=0;
		  };
		  
		  jQuery("#search_ad_list").keyup(function(){
			//hide all the rows
			  jQuery("#ads_list").find("div").hide();
			//split the current value of searchInput
			  var data = this.value.split(" ");
			//create a jquery object of the rows
			  var jo = jQuery("#ads_list").find("div");
			  //Recusively filter the jquery object to get results.
			  jQuery.each(data, function(i, v){
				  jo = jo.filter("*:containsIgnoreCase('"+v+"')");
			  });
			//show the rows that match.
			  jo.show();
		 //Removes the placeholder text  
	   
		  }).focus(function(){
			  this.value="";
			  jQuery(this).unbind('focus');
		  })
		  //*******************************END SEARCH TECHNIQUE **************************************



	}); //end document ready


	function show_ad_list_modal(p)
	{
	 selected_ad_set=jQuery(p).parent().parent().parent().children('.ad_set').first();
	var already_added_one=jQuery(selected_ad_set).children('.ad_item').size();
		if(already_added_one==1){ alert("You already added one ad. To add one more ad buy premium version"); return;}
		else
		{	 
		 jQuery( "#ad_list_modal" ).dialog( "open" );
		}
	
	}	

	function show_premium_box_modal()
	{
		 jQuery( "#premium_box_modal" ).dialog( "open" );
	}

	function update_ad_zone_link(b)
	{
		
		var zone_id=parseInt(jQuery("#set_zone_id").val());
		var post_id=parseInt(jQuery("#set_post_id").val()); //default = 0
		var page_type=jQuery("#set_page_type").val();
		var taxonomy=jQuery("#set_taxonomy").val();
		var term=jQuery("#set_term").val();
		
		var qData={"action":"save_ad_zone_links", "ad_type":"banner", "zone_id":zone_id, "post_id":post_id, "page_type":page_type, "taxonomy":taxonomy, "term":term, "ad_zone_link_set":[]};
		jQuery.each(jQuery('.ad_zone_link_set') , function(){
			var ad_zone_link_set_item={}
			
			var country_code="--";
			var ad_zone_link_set_item={'country_code':country_code, "ad_slide_set":[]};
			
			var ad_slide_set_box=jQuery(this).children('div').children('.ad_slide_set_box').first();
			
			jQuery.each(jQuery(ad_slide_set_box).find('.ad_slide'), function(){
				var ad_slide_set_item=[];
				
				var ad_set=jQuery(this).children('.ad_set').first();
				jQuery.each(jQuery(ad_set).children('.ad_item'), function(){
					var adid = jQuery(this).attr('adid');
					var percentage=parseInt(jQuery(this).find('div > .percentage').first().val());
					var ad_item={"ad_id":adid, "percentage":percentage};
					ad_slide_set_item.push(ad_item);
				});
				ad_zone_link_set_item.ad_slide_set.push(ad_slide_set_item);
			});
			qData.ad_zone_link_set.push(ad_zone_link_set_item);
			
		});
		
		jQuery(".ad_zone_link_loading").show();		
		adGuru.set_overlay(jQuery("#link_editor_body"));		
		
		 jQuery.ajax({
		   url: ADGURU_ADMIN_API_URL,
		   type: "POST",
		   global: false,
		   cache: false,
		   async: true,
		   data:qData,
			success: function(json_result){				
				
				var response=jQuery.parseJSON( json_result )
					if(response.status=='success')
					{
					jQuery(".ad_zone_link_msg").html("Saved");
					jQuery(".ad_zone_link_msg").addClass('success');					
					}
					else
					{
					alert(response.message);
					jQuery(".ad_zone_link_msg").html(response.message);
					jQuery(".ad_zone_link_msg").addClass('fail');
					}

					jQuery(".ad_zone_link_loading").hide();
					
					adGuru.remove_overlay(jQuery("#link_editor_body"));
				},
			error: function(xhr,errorThrown){}
			   
		  });//end jQuery.ajax	
		
	}//end update_ad_zone_link
