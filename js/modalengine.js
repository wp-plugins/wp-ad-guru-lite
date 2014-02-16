	var adg_yt_api_script_loaded=false;
	var adg_videoSrcBackup="";
	var adg_videoFrameBackup=null;
	var adg_yt_player=null;
	var adg_disbaleVideoPlayerControl=false;
	var adg_is_yt=false;
	var adg_yt_playing=false;
	var adg_yt_paused=false;
	var adg_yt_is_autoplay=false;
	var adg_dialogOpened=false;
	
	function adg_stopPlayer() { 
		if(!adg_disbaleVideoPlayerControl)
		{
			
			if(adg_is_yt && adg_yt_player!=null)
			{
				try {
					
					adg_yt_player.pauseVideo();
					adg_yt_paused=true;
				} catch(err) {}
			return;
			}			
			//do non youtube vidoe or any other iframe	
			adg_backup_video_frames();		
		
		}//end if(!adg_disbaleVideoPlayerControl)
	}
	function adg_play_if_not_playing() { 
		if(!adg_disbaleVideoPlayerControl && adg_dialogOpened==true)
		{
			
			if(adg_is_yt && adg_yt_player != null)
			{
			//============do for youtbe=============
				var isiPad = navigator.userAgent.match(/iPad/i) != null;
				var isiPod = navigator.userAgent.match(/iPod/i) != null;
				if (isiPad || isiPod) {} 
				else 
				{
					if(adg_yt_is_autoplay==true)
					{
						try {
						adg_yt_player.playVideo();	
						adg_yt_playing=true;
						} catch(err) {}
					}					
				}				
			return;
			}
			//do non youtube vidoe or any other iframe
			if(adg_videoFrameBackup != null){
				jQuery("#adg_vframe_place").replaceWith(adg_videoFrameBackup);					
			}
		
		
		
		}//end if(!adg_disbaleVideoPlayerControl)
		
	}

function adg_onPlayerStateChange(event) {
   if(event.data == YT.PlayerState.PLAYING && adg_dialogOpened==false)
   {
   event.target.pauseVideo();
   adg_yt_paused=true;
   }
   else
   {
   		if(event.data == YT.PlayerState.PLAYING){adg_yt_playing=true;}
   }
}

function adg_onPlayerReady(event) {
	if(adg_yt_is_autoplay==true || adg_yt_paused==true)
	{	
		event.target.playVideo();
	}	
}

function onYouTubePlayerAPIReady() {
	var frames=jQuery("#adguru_modal_content iframe");
	
	if(frames.size())
	{
		for(i=0; i<frames.size(); i++)
		{	
			if (/^https?:\/\/(?:www\.)?youtube(?:-nocookie)?\.com(\/|$)/i.test(frames[i].src))
			{
				adg_is_yt=true;
				if(frames[i].src.search("autoplay=1") !=-1){adg_yt_is_autoplay=true;}
				var frm=frames[i];
				var frameID;
				if(frm.id){frameID=frm.id;}else{frm.id="adg_yt_video"; frameID = frm.id;}
				adg_yt_player = new YT.Player(frameID, {
				  events: {
					'onReady': adg_onPlayerReady,
					'onStateChange': adg_onPlayerStateChange
				  	}
				});				
				
				break;	
			}
		}
	}
	
}

function adg_backup_video_frames()
{	
	var vframes=jQuery("#adguru_modal_content iframe");
	if(vframes.size())
	{
	var vframe=vframes[0]; var vsrc=vframe.src;	
	var isYoutube = vsrc.match(/youtube.com/i) != null;
		if(!isYoutube)
		{			
			adg_videoFrameBackup=vframe;
			jQuery(vframe).replaceWith('<span id="adg_vframe_place">Place for Video</span>');
			adg_videoSrcBackup=vsrc;	
		}
		else
		{
			adg_is_yt=true;
			if(adg_yt_api_script_loaded==false)
			{
			//including youtube player api script
			//http://www.youtube.com/player_api
				  var tag = document.createElement('script');
				  tag.src = "https://www.youtube.com/iframe_api";
				  var firstScriptTag = document.getElementsByTagName('script')[0];
				  firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);
			//=======end youtube player api script==========
			adg_yt_api_script_loaded=true;
			}		
		}
	
	}
	
}

//===================================MAIN FUNCTIONS========================================================
(function ($) {
	var root = this;
	var timeOut = Number.NaN;
	var width = Number.NaN;
	var height = Number.NaN;
	var delay = Number.NaN;
	var log = [];
	var SENS = 20;
	var hasFired = 0;
	var showPopup=true;
	var enableExitAlert = false;
	var disableExitAlert=true;
	var exitAlertText = "Before you go, please take a look at this special offer...";
	
	var maxModalFires = 2;
	var dialog = false;
	var dialogOpened = false;
	var documentLoads = 0;



	function start() {		
			initModal();
	}

	function initModal() {
			if (!dialog) {
				adg_backup_video_frames();
				$('#adguru_modal_content').show();
				$('#adguru_modal_content').modal({
					onOpen: function (dl) {
						dialog = dl
					},
					onClose: modalClose
				});
				if (width && height) {
					$('#simplemodal-container').width(width).height(height)
				}
			}
		$(document).ready(documentLoad);
		windowUnloadTimeout = setInterval(setWindowUnload, 330);
	}
	
	function destroyModal(){
		$('#simplemodal-container').remove();
		$('#simplemodal-overlay').remove();
		$('#simplemodal-placeholder').remove();
		$('#adguru_modal_content').remove();
		
	}

	function setWindowUnload() { //alert('oneTarek');
		if (window.onbeforeunload != triggerExitAlert) {
			window.onbeforeunload = triggerExitAlert
		}
	}
	function clearWindowUnload() {
		clearInterval(windowUnloadTimeout);
		windowUnloadTimeout = null;
		window.onbeforeunload = null
	}
	function setWindowAlert() {
		window.alert = nullalert
	}
	function nullalert(message) {}

	function documentLoad() {
		documentLoads++;
		switch (documentLoads) {
		case 1:
			if (!isNaN(delay)) {
				if (delay > 0) { 
					timeOut = setTimeout(triggerModal, 1000 * delay)
				} else if (delay == 0) {
					triggerModal()
				}
				else if(delay == -1)
				{
				//timeOut = setTimeout(triggerModal, 1000 * delay)	// No need to set time
				}
			}
			break;
		default:
			clearWindowUnload()
		}
		
		if(enableExitAlert)//exit popup will work if only browser close button is clicked. Remove exit alert if any link in the page is clicked.
		{
		$('a').click(function(){
			window.onbeforeunload = null;					  
		});
		}
		
		
	}
	
	function triggerExitAlert() {
		if (enableExitAlert) {
			if(showPopup==true)
			{
				triggerModal();
				/*we want not to show modal again if browser exit button clicked */
				hasFired=maxModalFires;
				clearWindowUnload();
			}
			enableExitAlert = false; 
				if (bowser.firefox) {
				alert("WAIT! Before you go we have something special... \n\n Click the 'Stay on Page' Button on the NEXT Window.");
				}

			return exitAlertText + "\n\nClick the 'Stay on this Page' button";
		}
	}	
	
	function triggerModal() {
		if (!dialogOpened && hasFired < maxModalFires) {
			dialogOpened = true;
			adg_dialogOpened=true;
			modalOpen(dialog);
			adg_play_if_not_playing();//added 16-02-2014
		}
		clearTimeout(timeOut);
	
	}


	function modalOpen(dialog, speed) {
		enableExitAlert=false; //to disable window message when opening modal.
		window.scrollTo(0, 0);
		$(window).trigger('resize');
			if (speed == "fast") {
				dialog.overlay.show();
				dialog.container.show();
				dialog.data.show()
			} else {
				dialog.overlay.fadeIn('fast', function () {
					dialog.container.fadeIn('fast', function () {
						dialog.data.show().slideDown('fast', function () {})
					})
				})
			}
			
		
	}
	function modalClose(dialog) {
		$('.simplemodal-close').click(function(){modalCloseSecondTime();});
		adg_stopPlayer(); //return false;
		dialogOpened = false;
		adg_dialogOpened=false;
		hasFired++; // disabled by oneTarek. We need to show the modal window for every time when user clkck the "stay on tis page" alert button
			dialog.data.fadeOut('fast', function () {
				dialog.container.hide('fast', function () {
					dialog.overlay.slideUp('fast', function () {
						if(disableExitAlert!=true){
							enableExitAlert=true; //to enable window message after closing modal.
						}
					})
				})
			})		
	}
	
	function modalCloseSecondTime()
	{ 
		destroyModal();	
	}
	
	root.adguru_modal_engine = root.adguru_modal_engine || {};
	
	root.adguru_modal_engine.call = function () {
		//if ($("#adguru_modal_content").is(":visible")) {
			adg_play_if_not_playing()
		//}
	};
	root.adguru_modal_engine.configure = function (p) {

		if (typeof p.disableExitAlert != "undefined" && p.disableExitAlert != '' && p.disableExitAlert != null) {
			if(p.disableExitAlert=='1'){disableExitAlert = true;}
		}

		if (typeof p.enableExitAlert != "undefined" && p.enableExitAlert != '' && p.enableExitAlert != null) {
			if(p.enableExitAlert=='1'){enableExitAlert = true;}
		}
		
		if (typeof p.exitAlertText != "undefined" && p.exitAlertText != '' && p.exitAlertText != null) {
			exitAlertText = p.exitAlertText;
			exitAlertText = exitAlertText.split("<br>").join("\n");
			exitAlertText = exitAlertText.split("<br />").join("\n");
			exitAlertText = exitAlertText.split("<br/>").join("\n");
			exitAlertText = exitAlertText.split("\r").join("\n")
		}
		if (typeof p.width != "undefined" && p.width != '' && p.width != null) {
			width = Number(p.width)
		}
		if (typeof p.height != "undefined" && p.height != '' && p.height != null) {
			height = Number(p.height)
		}
		if (typeof p.delay != "undefined" && p.delay != '' && p.delay != null) {
			delay = Number(p.delay)
		}


		start();
	}
}).call(this, jQuery);
