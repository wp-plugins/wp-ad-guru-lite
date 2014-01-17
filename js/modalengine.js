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
	var disableExitAlert=false;
	var exitAlertText = "Before you go, please take a look at this special offer...";
	var disbaleVideoPlayerControl=false;//added from version 2.5 . There was an error when popup_source is only enter a link
	var maxModalFires = 2;
	var dialog = false;
	var dialogOpened = false;
	var documentLoads = 0;
	function start() {		
			initModal();
	}

	function initModal() {
			if (!dialog) {
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
				modalOpen(dialog);		 
		}
		clearTimeout(timeOut);
	
	}

	function stopPlayer() { 
		if(!disbaleVideoPlayerControl)
		{
		var $f = $("#exit-content");
		$f[0].contentWindow.stopPlayer(); 
		}
	}
	function play_if_not_playing() {
		if(!disbaleVideoPlayerControl)
		{
		var $f = $("#exit-content");
		$f[0].contentWindow.play_if_not_playing();  //function declared in the content of iFrame
		}
		
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
		stopPlayer(); //return false;
		dialogOpened = false;
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
		if ($("#exit-content").is(":visible")) {
			play_if_not_playing()
		}
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
