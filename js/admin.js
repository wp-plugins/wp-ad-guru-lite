
jQuery(document).ready(function($){

	$("#size_list").on('change',function(){
		var val=$(this).val();
		if(val=="custom")
		{
			$("#width").removeAttr("readonly");
			$("#height").removeAttr("readonly");
		}
		else
		{
			var wh=val.split("x");
			$("#width").val(wh[0]);
			$("#height").val(wh[1]);
			$("#width").attr("readonly","readonly");
			$("#height").attr("readonly","readonly");
		}
	});



});//end jQuery(document).ready

var adGuru;//global object
(function($){
 
 
adGuru={
		
	set_overlay:function(element)
	{
		var height = $(element).height();
		$(element).prepend("<div class='overlay'></div>");
	    $(element).children(".overlay").first()
		  .height(height)
		  .css({
			 'opacity' : 0.4,
			 'position': 'absolute',
			 'background-color': 'black',
			 'width': '100%',
			 'z-index': 5000
		  });		
	},//end set_overlay
	
	remove_overlay:function(element)
	{
		$(element).children(".overlay").remove();		
	}//end remove_overlay
	
	
  }//end  adguru
})(jQuery);